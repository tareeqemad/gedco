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
use Illuminate\Support\Facades\Http;

class ProfileDependentsController extends Controller
{
    /**
     * عرض صفحة إنشاء بيانات الموظف وأسرته
     */
    public function create(): View
    {
        $locked  = session('locked', false);
        $profile = null;

        return view('staff.profile_dependents.add_dependent', compact('locked', 'profile'));
    }

    /**
     * حفظ بيانات الموظف + أفراد الأسرة
     */
    public function store(StoreDependentsRequest $request): RedirectResponse
    {
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


                    'readiness' => $data['readiness'] ?? null,
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

        return view('staff.profile_dependents.edit', compact('profile'));
    }

    /**
     * تحديث بيانات الموظف + أفراد الأسرة
     */
    public function update(UpdateDependentsRequest $request, StaffProfile $profile): RedirectResponse
    {
        $data = $request->validated();

        // إدارة محاولات التعديل
        if (($profile->edits_remaining ?? 0) < 1) {
            return redirect()
                ->route('staff.profile.show', $profile->id)
                ->with('locked_msg', 'انتهت محاولات التعديل.');
        }

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
                    'edits_remaining'      => max(0, ($profile->edits_remaining ?? 1) - 1),
                ]);

                session()->forget("staff_edit_allowed_{$profile->id}");
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

        return redirect()
            ->route('staff.profile.show', $profile->id)
            ->with('success', 'تم تحديث البيانات بنجاح ✅');
    }


    /**
     * خدمة lookup لبيانات الموظف من الـ API الخارجي
     */
    public function lookup(Request $request)
    {
        $id = preg_replace('/\D/', '', (string) $request->query('id', ''));
        if (strlen($id) !== 9) {
            return response()->json(['ok' => false, 'message' => 'رقم الهوية غير صالح.'], 422);
        }

        try {
            $apiUrl = config('staff.employee_lookup_api_url', 'https://eservices.gedco.ps/api/employees/search');

            // جرب POST مع body أولاً
            $response = Http::timeout(10)
                ->acceptJson()
                ->asJson()
                ->post($apiUrl, ['id' => $id]);

            // إذا فشل، جرب POST مع national_id
            if (!$response->ok()) {
                $response = Http::timeout(10)
                    ->acceptJson()
                    ->asJson()
                    ->post($apiUrl, ['national_id' => $id]);
            }

            // إذا فشل، جرب GET مع path parameter
            if (!$response->ok()) {
                $response = Http::timeout(10)->acceptJson()->get("{$apiUrl}/{$id}");
            }

            // إذا فشل، جرب GET مع query parameter
            if (!$response->ok()) {
                $response = Http::timeout(10)->acceptJson()->get($apiUrl, ['id' => $id]);
            }

            // إذا فشل، جرب GET مع national_id query parameter
            if (!$response->ok()) {
                $response = Http::timeout(10)->acceptJson()->get($apiUrl, ['national_id' => $id]);
            }

            if (!$response->ok()) {
                \Log::error('Employee lookup API failed', [
                    'url'    => $apiUrl,
                    'id'     => $id,
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return response()->json(['ok' => false, 'message' => 'تعذر الاتصال بالخدمة.'], 502);
            }

            $payload = $response->json();

            // تسجيل نتائج API للتحقق
            \Log::info('Employee lookup API response', [
                'url'           => $apiUrl,
                'id'            => $id,
                'status'        => $response->status(),
                'payload_keys'  => is_array($payload) ? array_keys($payload) : 'not_array',
                'payload_sample'=> is_array($payload) ? json_encode(array_slice($payload, 0, 1, true)) : $payload,
            ]);

            // دعم بنيات مختلفة للاستجابة
            $row = null;
            if (isset($payload['data_rows']) && is_array($payload['data_rows'])) {
                $row = $payload['data_rows'][0] ?? null;
            } elseif (isset($payload['data']) && is_array($payload['data'])) {
                $row = is_array($payload['data'][0] ?? null) ? $payload['data'][0] : $payload['data'];
            } elseif (isset($payload[0]) && is_array($payload[0])) {
                $row = $payload[0];
            } elseif (is_array($payload) && isset($payload['NAME'])) {
                $row = $payload;
            }

            if (!$row) {
                \Log::warning('Employee lookup: No data found', [
                    'url'                => $apiUrl,
                    'id'                 => $id,
                    'payload_structure'  => is_array($payload) ? array_keys($payload) : 'not_array',
                ]);

                return response()->json(['ok' => false, 'message' => 'لا توجد بيانات مطابقة.'], 404);
            }

            $normalizeMarital = function (?string $status): ?string {
                $status = trim((string) $status);
                if ($status === '') return null;
                if (str_contains($status, 'أعزب'))   return 'single';
                if (str_contains($status, 'متزوج'))  return 'married';
                if (str_contains($status, 'أرمل'))   return 'widowed';
                if (str_contains($status, 'مطلق'))   return 'divorced';
                return null;
            };

            $normalizeLocation = function (?string $branch): ?string {
                $b = mb_strtolower(trim((string) $branch));
                if ($b === '') return null;
                if (str_contains($b, 'الرئيس'))  return '1';
                if (str_contains($b, 'غزة'))     return '2';
                if (str_contains($b, 'الشمال'))  return '3';
                if (str_contains($b, 'الوسطى'))  return '4';
                if (str_contains($b, 'خانيونس')) return '6';
                if (str_contains($b, 'رفح'))     return '7';
                if (str_contains($b, 'الصيانة')) return '8';
                return null;
            };

            // تسجيل البيانات المستخرجة
            \Log::info('Employee lookup extracted data', [
                'row_keys'  => is_array($row) ? array_keys($row) : 'not_array',
                'row_sample'=> is_array($row) ? json_encode($row) : $row,
            ]);

            $data = [
                'full_name'       => $row['NAME'] ?? null,
                'birth_date'      => isset($row['BIRTH_DATE']) ? substr((string) $row['BIRTH_DATE'], 0, 10) : null,
                'marital_status'  => $normalizeMarital($row['STATUS_NAME'] ?? null),
                'job_title'       => $row['W_NO_ADMIN_NAME'] ?? null,
                'location'        => $normalizeLocation($row['BRAN_NAME'] ?? null),
                'department'      => $row['HEAD_DEPARTMENT_NAME'] ?? $row['DEPT_NAME'] ?? $row['DEPARTMENT'] ?? $row['ADMIN_NAME'] ?? $row['DEPT'] ?? null,
                'national_id'     => $row['ID'] ?? null,
                'employee_number' => $row['NO'] ?? null,
            ];

            $responseData = [
                'ok'   => true,
                'data' => $data,
                '_debug' => [
                    'raw_payload_keys' => is_array($payload) ? array_keys($payload) : 'not_array',
                    'raw_row_keys'     => is_array($row) ? array_keys($row) : 'not_array',
                    'raw_row'          => $row,
                ],
            ];

            return response()->json($responseData);
        } catch (\Throwable $e) {
            \Log::error('Employee lookup unexpected error', [
                'id'      => $id,
                'message' => $e->getMessage(),
            ]);

            return response()->json(['ok' => false, 'message' => 'حدث خطأ غير متوقع.'], 500);
        }
    }
}
