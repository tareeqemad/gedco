<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDependentsRequest;
use App\Http\Requests\UpdateDependentsRequest;
use App\Models\StaffProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\EmployeeDirectory;

class ProfileDependentsController extends Controller
{
    /**
     * عرض صفحة إنشاء بيانات الموظف وأسرته
     * يتطلب التحقق أولاً من صفحة verify
     */
    public function create(): View|RedirectResponse
    {
        if (! session('verified_national_id')) {
            return redirect()->route('staff.profile.verify.form')
                ->with('info', 'يجب التحقق من الهوية أولاً.');
        }

        $locked  = session('locked', false);

        return view('staff.profile_dependents.add_dependent', [
            'locked'    => $locked,
            'verified'  => [
                'national_id'      => session('verified_national_id'),
                'employee_number'  => session('verified_employee_number'),
                'full_name'        => session('verified_full_name'),
                'job_title'        => session('verified_job_title'),
                'mobile'           => session('verified_mobile'),
                'email'            => session('verified_email'),
                'spouse_name'      => session('verified_spouse_name'),
                'spouse_national_id' => session('verified_spouse_national_id'),
                'family_count'     => session('verified_family_count'),
                'governorate'      => session('verified_governorate'),
            ],
        ]);
    }

    /**
     * حفظ بيانات الموظف + أفراد الأسرة
     */
    public function store(StoreDependentsRequest $request): RedirectResponse
    {
        if (! session('verified_national_id')) {
            return redirect()->route('staff.profile.verify.form')
                ->with('info', 'يجب التحقق من الهوية أولاً.');
        }

        $data = $request->validated();

        // لو موجود مسبقًا نحوله لفورم التحقق للتعديل
        $existing = StaffProfile::query()
            ->where(function ($query) use ($data) {
                $query->where('employee_number', (int) $data['employee_number'])
                    ->orWhere('national_id', (int) $data['national_id']);
            })
            ->first();

        if ($existing) {
            $by    = $existing->national_id == (int) $data['national_id'] ? 'national_id' : 'employee_number';
            $value = $by === 'national_id' ? $existing->national_id : $existing->employee_number;

            return redirect()
                ->route('staff.profile.verify.form', ['by' => $by, 'value' => $value])
                ->with('info', 'الرقم الوظيفي/رقم الهوية مستخدم مسبقًا. أدخل رقم الهوية لمتابعة التعديل.')
                ->withInput();
        }

        try {
            $profile = DB::transaction(function () use ($data) {

                // فلترة المعالين: أي صف فاضي بالكامل ينشال
                $familyRows = collect($data['family'] ?? [])
                    ->filter(function ($r) {
                        return filled($r['name'] ?? null)
                            || filled($r['relation'] ?? null)
                            || filled($r['birth_date'] ?? null)
                            || filled($r['is_student'] ?? null);
                    })
                    ->values();

                // منع تكرار نفس فرد الأسرة (نفس الاسم + نفس تاريخ الميلاد)
                $duplicates = $familyRows
                    ->map(function ($r) {
                        $name  = mb_strtolower(trim($r['name'] ?? ''));
                        $birth = trim($r['birth_date'] ?? '');
                        return $name . '|' . ($birth ?: 'NULL');
                    })
                    ->groupBy(fn($key) => $key)
                    ->filter(fn($group) => $group->count() > 1);

                if ($duplicates->isNotEmpty()) {
                    throw new \RuntimeException('duplicate_family_members');
                }

                // 🔹 تحويل تاريخ الميلاد للموظف إلى Y-m-d
                $birthDate = null;
                if (!empty($data['birth_date'])) {
                    $dateStr = trim($data['birth_date']);

                    // لو جاي من الفورم كـ MM/DD/YYYY (مثال: 08/20/1994)
                    if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $dateStr, $matches)) {
                        $month = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                        $day   = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                        $year  = $matches[3];
                        $birthDate = "$year-$month-$day"; // 1994-08-20
                    } else {
                        // أي فورمات ثانية: نخلي Carbon يحاول يقرأها
                        try {
                            $birthDate = \Carbon\Carbon::parse($dateStr)->format('Y-m-d');
                        } catch (\Throwable $e) {
                            $birthDate = null;
                        }
                    }
                }

                // إنشاء ملف الموظف
                $profile = StaffProfile::create([
                    'full_name'            => $data['full_name'],
                    'employee_number'      => (int) $data['employee_number'],
                    'national_id'          => (int) $data['national_id'],
                    'mobile'               => $data['mobile'],

                    'birth_date'           => $birthDate,
                    'job_title'            => $data['job_title'] ?? null,
                    'location'             => $data['location'],
                    'department'           => $data['department'] ?? null,
                    'directorate'          => $data['directorate'] ?? null,
                    'section'              => $data['section'] ?? null,
                    'marital_status'       => $data['marital_status'],

                    'has_family_incidents' => $data['has_family_incidents'] ?? 'no',
                    'family_notes'         => $data['family_notes'] ?? null,

                    'original_address'     => $data['original_address'] ?? null,
                    'house_status'         => $data['house_status'] ?? null,
                    'status'               => $data['status'] ?? null,
                    'current_address'      => $data['current_address'] ?? null,
                    'housing_type'         => $data['housing_type'] ?? null,

                    'mobile_alt'           => $data['mobile_alt'] ?? null,
                    'whatsapp'             => ($data['whatsapp_prefix'] ?? '') . ($data['whatsapp'] ?? ''),
                    'telegram'             => $data['telegram'] ?? null,
                    'gmail'                => $data['gmail'] ?? null,

                    'readiness'            => $data['readiness'] ?? null,
                    'readiness_notes'      => $data['readiness_notes'] ?? null,

                    // الباسورد = رقم الهوية
                    'password_hash'        => Hash::make((string) $data['national_id']),
                    'edits_allowed'        => 1,
                    'edits_remaining'      => 1,
                ]);

                $allowedRelations = array_keys(config('staff_enums.relation', []));

                foreach ($familyRows as $row) {
                    $relation = $row['relation'] ?? null;

                    if (!in_array($relation, $allowedRelations, true)) {
                        $relation = 'other';
                    }

                    // 🔹 تحويل تاريخ ميلاد المعال إلى Y-m-d
                    $dependentBirthDate = null;
                    if (!empty($row['birth_date'])) {
                        try {
                            $dependentBirthDate = \Carbon\Carbon::parse($row['birth_date'])->format('Y-m-d');
                        } catch (\Throwable $e) {
                            $dependentBirthDate = null;
                        }
                    }

                    $profile->dependents()->create([
                        'name'       => $row['name'] ?? '',
                        'relation'   => $relation,
                        'birth_date' => $dependentBirthDate,
                        'is_student' => ($row['is_student'] ?? '') === 'yes',
                    ]);
                }

                $profile->update([
                    'family_members_count' => max(0, $familyRows->count()),
                ]);

                return $profile;
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'duplicate_family_members') {
                return back()
                    ->with('locked', true)
                    ->with('locked_msg', 'يوجد تكرار في إدخال أحد أفراد الأسرة (نفس الاسم وتاريخ الميلاد مكرر أكثر من مرة).')
                    ->withInput();
            }

            throw $e;
        } catch (\Illuminate\Database\QueryException $e) {

            $errorMessage = $e->getMessage();
            $sqlState     = $e->errorInfo[0] ?? null;
            $sqlCode      = $e->errorInfo[1] ?? null;

            \Log::error('Error creating staff profile', [
                'error'           => $errorMessage,
                'code'            => $e->getCode(),
                'sql_state'       => $sqlState,
                'sql_code'        => $sqlCode,
                'employee_number' => $data['employee_number'] ?? null,
                'national_id'     => $data['national_id'] ?? null,
            ]);

            // تكرار في الأرقام الأساسية
            if (str_contains($errorMessage, 'Duplicate entry')
                && (str_contains($errorMessage, 'staff_profiles_employee_number_unique')
                    || str_contains($errorMessage, 'staff_profiles_national_id_unique')
                    || $sqlCode == 1062)) {

                return back()
                    ->with('locked', true)
                    ->with('locked_msg', 'الأرقام المدخلة موجودة مسبقًا.')
                    ->withInput();
            }

            // تكرار في أفراد الأسرة لنفس الموظف
            if (str_contains($errorMessage, 'staff_dependents_staff_profile_id_name_birth_date_unique')) {
                return back()
                    ->with('locked', true)
                    ->with('locked_msg', 'لا يمكن إدخال نفس فرد الأسرة مرتين بنفس الاسم وتاريخ الميلاد.')
                    ->withInput();
            }

            // Data too long
            if ($sqlCode == 1406 || str_contains($errorMessage, 'Data too long')) {
                return back()
                    ->with('locked', true)
                    ->with('locked_msg', 'أحد الحقول المدخلة أطول من المسموح به. تأكد من طول رقم الجوال واسم المستخدم في تيليجرام وبريد Gmail.')
                    ->withInput();
            }

            // fallback عام
            return back()
                ->with('locked', true)
                ->with('locked_msg', 'حدث خطأ أثناء حفظ البيانات. يرجى المحاولة مرة أخرى.')
                ->withInput();
        }

        // 👈 هنا نضبط السيشن عشان الميدل وير يسمح له يشوف / يعدّل هذا البروفايل
        session(['allowed_edit_profile_id' => $profile->id]);

        return redirect()
            ->route('staff.profile.show', $profile->id)
            ->with('success', 'تم حفظ البيانات بنجاح ✅');
    }



    /**
     * عرض بيانات الموظف + الأسرة
     */
    public function show(StaffProfile $profile): View
    {
        $profile->load('dependents');

        return view('staff.profile_dependents.show', compact('profile'));
    }

    /**
     * عرض صفحة التعديل
     */
    public function edit(StaffProfile $profile): View
    {
        $profile->load('dependents');

        $directoryEntry = EmployeeDirectory::where('employee_number', $profile->employee_number)->first();

        return view('staff.profile_dependents.edit', [
            'profile'      => $profile,
            'companyEmail' => $directoryEntry->email ?? null,
        ]);
    }

    /**
     * تحديث بيانات الموظف + أفراد الأسرة
     */
    public function update(UpdateDependentsRequest $request, StaffProfile $profile): RedirectResponse
    {
        $data = $request->validated();


        try {
            DB::transaction(function () use ($data, $profile, $request) {

                // 🔹 تحويل تاريخ الميلاد للموظف (مع إن الفورم input[type=date] لكن أمان زيادة)
                $birthDate = null;
                if (!empty($data['birth_date'])) {
                    try {
                        $birthDate = \Carbon\Carbon::parse($data['birth_date'])->format('Y-m-d');
                    } catch (\Throwable $e) {
                        $birthDate = null;
                    }
                }

                $payload = [
                    'full_name'            => $data['full_name'],
                    'employee_number'      => (int) $data['employee_number'],
                    'national_id'          => (int) $data['national_id'],
                    'mobile'               => $data['mobile'],
                    'birth_date'           => $birthDate,
                    'job_title'            => $data['job_title'] ?? null,
                    'location'             => $data['location'],
                    'department'           => $data['department'] ?? null,
                    'directorate'          => $data['directorate'] ?? null,
                    'section'              => $data['section'] ?? null,
                    'marital_status'       => $data['marital_status'] ?? null,
                    'has_family_incidents' => $data['has_family_incidents'] ?? 'no',
                    'family_notes'         => $data['family_notes'] ?? null,
                    'original_address'     => $data['original_address'] ?? null,
                    'house_status'         => $data['house_status'] ?? null,
                    'status'               => $data['status'] ?? null,
                    'current_address'      => $data['current_address'] ?? null,
                    'housing_type'         => $data['housing_type'] ?? null,
                    'mobile_alt'           => $data['mobile_alt'] ?? null,
                    'whatsapp'             => ($data['whatsapp_prefix'] ?? '') . ($data['whatsapp'] ?? ''),
                    'telegram'             => $data['telegram'] ?? null,
                    'gmail'                => $data['gmail'] ?? null,
                    'readiness'            => $data['readiness'] ?? null,
                    'readiness_notes'      => $data['readiness_notes'] ?? null,
                ];

                if ($request->filled('password')) {
                    $payload['password_hash'] = Hash::make($data['password']);
                }

                // تحديث بيانات الموظف
                $profile->update($payload);

                // حذف المعالين القدامى
                $profile->dependents()->delete();

                // إعادة بناء قائمة المعالين
                $familyRows = collect($data['family'] ?? [])
                    ->filter(fn ($r) =>
                        filled($r['name'] ?? null) ||
                        filled($r['relation'] ?? null) ||
                        filled($r['birth_date'] ?? null) ||
                        filled($r['is_student'] ?? null)
                    )
                    ->values();

                // منع تكرار نفس فرد الأسرة (نفس الاسم + نفس تاريخ الميلاد)
                $duplicates = $familyRows
                    ->map(function ($r) {
                        $name  = mb_strtolower(trim($r['name'] ?? ''));
                        $birth = trim($r['birth_date'] ?? '');
                        return $name . '|' . ($birth ?: 'NULL');
                    })
                    ->groupBy(fn($key) => $key)
                    ->filter(fn($group) => $group->count() > 1);

                if ($duplicates->isNotEmpty()) {
                    throw new \RuntimeException('duplicate_family_members');
                }

                $allowedRelations = array_keys(config('staff_enums.relation', []));
                foreach ($familyRows as $row) {
                    $relation = $row['relation'] ?? null;

                    if (!in_array($relation, $allowedRelations, true)) {
                        $relation = 'other';
                    }

                    $dependentBirthDate = null;
                    if (!empty($row['birth_date'])) {
                        try {
                            $dependentBirthDate = \Carbon\Carbon::parse($row['birth_date'])->format('Y-m-d');
                        } catch (\Throwable $e) {
                            $dependentBirthDate = null;
                        }
                    }

                    $profile->dependents()->create([
                        'name'       => $row['name'] ?? '',
                        'relation'   => $relation,
                        'birth_date' => $dependentBirthDate,
                        'is_student' => ($row['is_student'] ?? '') === 'yes',
                    ]);
                }

                $profile->update([
                    'family_members_count' => max(0, $familyRows->count()),
                    'last_edited_at'       => now(),
                ]);

                // هنا ما عاد نمسح أي سيشن له علاقة بالصلاحية
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'duplicate_family_members') {
                return back()
                    ->with('locked', true)
                    ->with('locked_msg', 'يوجد تكرار في إدخال أحد أفراد الأسرة (نفس الاسم وتاريخ الميلاد مكرر أكثر من مرة).')
                    ->withInput();
            }
            throw $e;
        } catch (\Illuminate\Database\QueryException $e) {

            $errorMessage = $e->getMessage();
            $sqlState     = $e->errorInfo[0] ?? null;
            $sqlCode      = $e->errorInfo[1] ?? null;

            \Log::error('Error updating staff profile', [
                'error'      => $errorMessage,
                'code'       => $e->getCode(),
                'sql_state'  => $sqlState,
                'sql_code'   => $sqlCode,
                'profile_id' => $profile->id,
            ]);

            if (str_contains($errorMessage, 'Duplicate entry')
                && (str_contains($errorMessage, 'staff_profiles_employee_number_unique')
                    || str_contains($errorMessage, 'staff_profiles_national_id_unique')
                    || $sqlCode == 1062)) {

                return back()
                    ->with('locked', true)
                    ->with('locked_msg', 'الأرقام المدخلة موجودة مسبقًا لموظف آخر.')
                    ->withInput();
            }

            if (str_contains($errorMessage, 'staff_dependents_staff_profile_id_name_birth_date_unique')) {
                return back()
                    ->with('locked', true)
                    ->with('locked_msg', 'لا يمكن إدخال نفس فرد الأسرة مرتين بنفس الاسم وتاريخ الميلاد.')
                    ->withInput();
            }

            if ($sqlCode == 1406 || str_contains($errorMessage, 'Data too long')) {
                return back()
                    ->with('locked', true)
                    ->with('locked_msg', 'أحد الحقول المدخلة أطول من المسموح به. تأكد من طول رقم الجوال واسم المستخدم في تيليجرام وبريد Gmail.')
                    ->withInput();
            }

            return back()
                ->with('locked', true)
                ->with('locked_msg', 'حدث خطأ أثناء حفظ البيانات. يرجى المحاولة مرة أخرى.')
                ->withInput();
        }


        session(['allowed_edit_profile_id' => $profile->id]);

        return redirect()
            ->route('staff.profile.show', $profile->id)
            ->with('success', 'تم تحديث البيانات بنجاح ✅');
    }


    /**
     * خدمة lookup لبيانات الموظف من جدول الدليل المحلي
     */
    public function lookup(Request $request)
    {
        $id = preg_replace('/\D/', '', (string) $request->query('id', ''));
        if (strlen($id) !== 9) {
            return response()->json([
                'ok'      => false,
                'message' => 'رقم الهوية غير صالح.',
            ], 422);
        }

        $entry = EmployeeDirectory::where('national_id', $id)->first();

        if (! $entry) {
            return response()->json([
                'ok'      => false,
                'message' => 'لا توجد بيانات مطابقة لهذا الرقم.',
            ], 404);
        }

        return response()->json([
            'ok'   => true,
            'data' => [
                'full_name'       => $entry->full_name,
                'national_id'     => $entry->national_id,
                'employee_number' => $entry->employee_number,
            ],
        ]);
    }
}
