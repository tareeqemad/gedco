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
    public function create(): View
    {
        $locked  = session('locked', false);
        $profile = null;
        return view('staff.profile_dependents.add_dependent', compact('locked','profile'));
    }

    public function store(StoreDependentsRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // لو موجود مسبقًا نحوله لفورم التحقق للتعديل
        $existing = StaffProfile::query()
            ->where('employee_number', (int)$data['employee_number'])
            ->orWhere('national_id',   (int)$data['national_id'])
            ->first();

        if ($existing) {
            $by    = $existing->national_id == (int)$data['national_id'] ? 'national_id' : 'employee_number';
            $value = $by === 'national_id' ? $existing->national_id : $existing->employee_number;

            return redirect()->route('staff.profile.verify.form', ['by' => $by, 'value' => $value])
                ->with('info', 'الرقم الوظيفي/رقم الهوية مستخدم مسبقًا. أدخل كلمة المرور لمتابعة التعديل.')
                ->withInput();
        }

        // فحص قفل احتياطي
        $dupExists = StaffProfile::query()
            ->where('employee_number', $data['employee_number'])
            ->orWhere('national_id',   $data['national_id'])
            ->exists();

        if ($dupExists) {
            return back()
                ->with('locked', true)
                ->with('locked_msg', 'أنت مسجّل مسبقًا بهذه البيانات (الهوية/الرقم الوظيفي).')
                ->withInput();
        }

        try {
            DB::transaction(function () use ($data) {

                $familyRows = collect($data['family'] ?? [])
                    ->filter(function ($r) {
                        return filled($r['name'] ?? null)
                            || filled($r['relation'] ?? null)
                            || filled($r['birth_date'] ?? null)
                            || filled($r['is_student'] ?? null);
                    })
                    ->values();

                $profile = StaffProfile::create([
                    'full_name'            => $data['full_name'],
                    'employee_number'      => (int) $data['employee_number'],
                    'national_id'          => (int) $data['national_id'],
                    'mobile'               => $data['mobile'],

                    'birth_date'           => $data['birth_date'] ?? null,
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
                    'whatsapp'             => $data['whatsapp'] ?? null,
                    'telegram'             => $data['telegram'] ?? null,
                    'gmail'                => $data['gmail'] ?? null,

                    'readiness'            => $data['readiness'] ?? null,
                    'readiness_notes'      => $data['readiness_notes'] ?? null,

                    // هنا الباسورد = رقم الهوية (محول لنص ثم Hash)
                    'password_hash'        => Hash::make((string) $data['national_id']),
                    'edits_allowed'        => 1,
                    'edits_remaining'      => 1,
                ]);

                foreach ($familyRows as $index => $row) {
                    $relation = $row['relation'] ?? null;

                    // ضمان إضافي: أول صف = self دايمًا
                    if ($index === 0) {
                        $relation = 'self';
                    } elseif (!in_array($relation, ['self','husband','wife','son','daughter','other'], true)) {
                        $relation = 'other';
                    }

                    $profile->dependents()->create([
                        'name'       => $row['name'] ?? '',
                        'relation'   => $relation,
                        'birth_date' => $row['birth_date'] ?? null,
                        'is_student' => ($row['is_student'] ?? '') === 'yes',
                    ]);
                }

                $profile->update([
                    'family_members_count' => max(1, $familyRows->count()),
                ]);
            });
        } catch (QueryException $e) {
            return back()
                ->with('locked', true)
                ->with('locked_msg', 'الأرقام المدخلة موجودة مسبقًا.')
                ->withInput();
        }

        return back()->with('success', 'تم حفظ البيانات بنجاح ✅');
    }

    public function show(StaffProfile $profile): View
    {
        $profile->load('dependents');
        return view('staff.profile_dependents.show', compact('profile'));
    }

    public function edit(StaffProfile $profile): View
    {
        $profile->load('dependents');
        return view('staff.profile_dependents.edit', compact('profile'));
    }

    public function update(UpdateDependentsRequest $request, StaffProfile $profile): RedirectResponse
    {
        $data = $request->validated();

        // إدارة محاولات التعديل
        if (($profile->edits_remaining ?? 0) < 1) {
            return redirect()
                ->route('staff.profile.show', $profile->id)
                ->with('locked_msg','انتهت محاولات التعديل.');
        }

        DB::transaction(function () use ($data, $profile, $request) {
            $payload = [
                'full_name'            => $data['full_name'],
                'employee_number'      => (int) $data['employee_number'],
                'national_id'          => (int) $data['national_id'],
                'mobile'               => $data['mobile'],
                'birth_date'           => $data['birth_date'] ?? null,
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
                'whatsapp'             => $data['whatsapp'] ?? null,
                'telegram'             => $data['telegram'] ?? null,
                'gmail'                => $data['gmail'] ?? null,
                'readiness'            => $data['readiness'] ?? null,
                'readiness_notes'      => $data['readiness_notes'] ?? null,
            ];

            if ($request->filled('password')) {
                $payload['password_hash'] = \Hash::make($data['password']);
            }

            $profile->update($payload);

            // استبدال أفراد الأسرة (استراتيجية بسيطة)
            $profile->dependents()->delete();
            $familyRows = collect($data['family'] ?? [])
                ->filter(fn($r) => filled($r['name'] ?? null) || filled($r['relation'] ?? null) || filled($r['birth_date'] ?? null) || filled($r['is_student'] ?? null));

            foreach ($familyRows as $row) {
                $profile->dependents()->create([
                    'name'       => $row['name'] ?? '',
                    'relation'   => $row['relation'] ?? 'other',
                    'birth_date' => $row['birth_date'] ?? null,
                    'is_student' => ($row['is_student'] ?? '') === 'yes',
                ]);
            }

            $profile->update([
                'family_members_count' => max(1, $familyRows->count()),
                'last_edited_at'       => now(),
                'edits_remaining'      => max(0, ($profile->edits_remaining ?? 1) - 1),
            ]);

            // إنهاء جلسة التعديل
            session()->forget("staff_edit_allowed_{$profile->id}");
        });

        return redirect()->route('staff.profile.show', $profile->id)
            ->with('success', 'تم تحديث البيانات بنجاح ✅');
    }

    public function lookup(Request $request)
    {
        $id = preg_replace('/\D/', '', (string) $request->query('id', ''));
        if (strlen($id) !== 9) {
            return response()->json(['ok' => false, 'message' => 'رقم الهوية غير صالح.'], 422);
        }

        try {
            $response = Http::timeout(10)->acceptJson()->get("https://eservices.gedco.ps/employees/search/{$id}");
            if (!$response->ok()) {
                return response()->json(['ok' => false, 'message' => 'تعذر الاتصال بالخدمة.'], 502);
            }
            $payload = $response->json();
            $row = $payload['data_rows'][0] ?? null;
            if (!$row) {
                return response()->json(['ok' => false, 'message' => 'لا توجد بيانات مطابقة.'], 404);
            }

            $normalizeMarital = function (?string $status): ?string {
                $status = trim((string) $status);
                if ($status === '') return null;
                if (str_contains($status, 'أعزب'))    return 'single';
                if (str_contains($status, 'متزوج'))   return 'married';
                if (str_contains($status, 'أرمل'))    return 'widowed';
                if (str_contains($status, 'مطلق'))    return 'divorced';
                return null;
            };

            $normalizeLocation = function (?string $branch): ?string {
                $b = mb_strtolower(trim((string) $branch));
                if ($b === '') return null;
                if (str_contains($b, 'الرئيس'))   return '1';
                if (str_contains($b, 'غزة'))      return '2';
                if (str_contains($b, 'الشمال'))    return '3';
                if (str_contains($b, 'الوسطى'))    return '4';
                if (str_contains($b, 'خانيونس'))   return '6';
                if (str_contains($b, 'رفح'))       return '7';
                if (str_contains($b, 'الصيانة'))   return '8';
                return null;
            };

            $data = [
                'full_name'      => $row['NAME'] ?? null,
                'birth_date'     => isset($row['BIRTH_DATE']) ? substr((string)$row['BIRTH_DATE'], 0, 10) : null,
                'marital_status' => $normalizeMarital($row['STATUS_NAME'] ?? null),
                'job_title'      => $row['W_NO_ADMIN_NAME'] ?? null,
                'location'       => $normalizeLocation($row['BRAN_NAME'] ?? null),
                'national_id'    => $row['ID'] ?? null,
                'employee_number'=> $row['NO'] ?? null,
            ];

            return response()->json(['ok' => true, 'data' => $data]);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => 'حدث خطأ غير متوقع.'], 500);
        }
    }
}
