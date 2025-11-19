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
            ->where(function($query) use ($data) {
                $query->where('employee_number', (int)$data['employee_number'])
                    ->orWhere('national_id', (int)$data['national_id']);
            })
            ->first();

        if ($existing) {
            $by    = $existing->national_id == (int)$data['national_id'] ? 'national_id' : 'employee_number';
            $value = $by === 'national_id' ? $existing->national_id : $existing->employee_number;

            return redirect()->route('staff.profile.verify.form', ['by' => $by, 'value' => $value])
                ->with('info', 'الرقم الوظيفي/رقم الهوية مستخدم مسبقًا. أدخل كلمة المرور لمتابعة التعديل.')
                ->withInput();
        }

        $profile = null;
        try {
            $profile = DB::transaction(function () use ($data) {

                $familyRows = collect($data['family'] ?? [])
                    ->filter(function ($r) {
                        return filled($r['name'] ?? null)
                            || filled($r['relation'] ?? null)
                            || filled($r['birth_date'] ?? null)
                            || filled($r['is_student'] ?? null);
                    })
                    ->values();

                // تحويل صيغة تاريخ الميلاد من MM/DD/YYYY إلى YYYY-MM-DD
                $birthDate = null;
                if (!empty($data['birth_date'])) {
                    $dateStr = trim($data['birth_date']);
                    // إذا كانت الصيغة MM/DD/YYYY
                    if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $dateStr, $matches)) {
                        $month = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                        $day = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                        $year = $matches[3];
                        $birthDate = "$year-$month-$day";
                    } else {
                        // محاولة تحويل مباشرة (إذا كانت بالفعل YYYY-MM-DD)
                        try {
                            $birthDate = \Carbon\Carbon::parse($dateStr)->format('Y-m-d');
                        } catch (\Exception $e) {
                            $birthDate = null;
                        }
                    }
                }

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
                    'whatsapp'             => $data['whatsapp'] ?? null,
                    'telegram'             => $data['telegram'] ?? null,
                    'gmail'                => $data['gmail'] ?? null,

                    // تحويل readiness: 'working' -> 'ready'
                    'readiness'            => ($data['readiness'] ?? null) === 'working' ? 'ready' : ($data['readiness'] ?? null),
                    'readiness_notes'      => $data['readiness_notes'] ?? null,

                    // هنا الباسورد = رقم الهوية
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

                    // تحويل القيم لتتوافق مع enum في قاعدة البيانات
                    $dbRelation = match($relation) {
                        'self' => 'other',      // الموظف نفسه -> other (لأن DB لا يدعم self)
                        'husband', 'wife' => 'spouse',  // زوج/زوجة -> spouse
                        'son' => 'son',
                        'daughter' => 'daughter',
                        'other' => 'other',
                        default => 'other'
                    };

                    // تحويل صيغة تاريخ الميلاد لأفراد الأسرة
                    $dependentBirthDate = null;
                    if (!empty($row['birth_date'])) {
                        $dateStr = trim($row['birth_date']);
                        // إذا كانت الصيغة MM/DD/YYYY
                        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $dateStr, $matches)) {
                            $month = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                            $day = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                            $year = $matches[3];
                            $dependentBirthDate = "$year-$month-$day";
                        } else {
                            // محاولة تحويل مباشرة
                            try {
                                $dependentBirthDate = \Carbon\Carbon::parse($dateStr)->format('Y-m-d');
                            } catch (\Exception $e) {
                                $dependentBirthDate = null;
                            }
                        }
                    }

                    $profile->dependents()->create([
                        'name'       => $row['name'] ?? '',
                        'relation'   => $dbRelation, // استخدام القيمة المحولة
                        'birth_date' => $dependentBirthDate,
                        'is_student' => ($row['is_student'] ?? '') === 'yes',
                    ]);
                }

                $profile->update([
                    'family_members_count' => max(1, $familyRows->count()),
                ]);

                return $profile; // إرجاع الـ profile من الـ transaction
            });
        } catch (QueryException $e) {
            // التحقق من نوع الخطأ
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();

            // تسجيل الخطأ للتحقق
            \Log::error('Error creating staff profile', [
                'error' => $errorMessage,
                'code' => $errorCode,
                'sql_state' => $e->errorInfo[0] ?? null,
                'sql_code' => $e->errorInfo[1] ?? null,
                'employee_number' => $data['employee_number'] ?? null,
                'national_id' => $data['national_id'] ?? null,
            ]);

            // التحقق من وجود السجلات في قاعدة البيانات
            $checkEmployee = StaffProfile::where('employee_number', (int)$data['employee_number'])->exists();
            $checkNational = StaffProfile::where('national_id', (int)$data['national_id'])->exists();

            \Log::info('Database check', [
                'employee_exists' => $checkEmployee,
                'national_exists' => $checkNational,
                'total_records' => StaffProfile::count(),
            ]);

            // إذا كان الخطأ بسبب unique constraint
            if (str_contains($errorMessage, 'Duplicate entry') ||
                str_contains($errorMessage, 'UNIQUE constraint') ||
                str_contains($errorMessage, '1062') ||
                ($e->errorInfo[1] ?? 0) == 1062) {
                return back()
                    ->with('locked', true)
                    ->with('locked_msg', 'الأرقام المدخلة موجودة مسبقًا.')
                    ->withInput();
            }

            // لأخطاء أخرى، نعرض رسالة عامة
            return back()
                ->with('locked', true)
                ->with('locked_msg', 'حدث خطأ أثناء حفظ البيانات. يرجى المحاولة مرة أخرى.')
                ->withInput();
        }

        // التوجيه إلى صفحة عرض البيانات بعد الحفظ الناجح
        return redirect()
            ->route('staff.profile.show', $profile->id)
            ->with('success', 'تم حفظ البيانات بنجاح ✅');
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

            // تحديث بيانات الموظف الأساسية
            $profile->update($payload);

            // استبدال أفراد الأسرة بنفس منطق store()
            $profile->dependents()->delete();

            $familyRows = collect($data['family'] ?? [])
                ->filter(fn($r) =>
                    filled($r['name'] ?? null) ||
                    filled($r['relation'] ?? null) ||
                    filled($r['birth_date'] ?? null) ||
                    filled($r['is_student'] ?? null)
                )
                ->values();

            foreach ($familyRows as $index => $row) {
                $relation = $row['relation'] ?? null;

                // ضمان أن الصف الأول هو الموظف نفسه
                if ($index === 0) {
                    $relation = 'self';
                } elseif (!in_array($relation, ['self','husband','wife','son','daughter','other'], true)) {
                    $relation = 'other';
                }

                // نفس المابينغ الخاص بـ store()
                $dbRelation = match($relation) {
                    'self'            => 'other',   // الموظف نفسه -> other في DB
                    'husband', 'wife' => 'spouse',
                    'son'             => 'son',
                    'daughter'        => 'daughter',
                    'other'           => 'other',
                    default           => 'other'
                };

                // تحويل تاريخ الميلاد لأفراد الأسرة (يدعم MM/DD/YYYY أو أي صيغة يفهمها Carbon)
                $dependentBirthDate = null;
                if (!empty($row['birth_date'])) {
                    $dateStr = trim($row['birth_date']);
                    if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $dateStr, $matches)) {
                        $month = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                        $day   = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                        $year  = $matches[3];
                        $dependentBirthDate = "$year-$month-$day";
                    } else {
                        try {
                            $dependentBirthDate = \Carbon\Carbon::parse($dateStr)->format('Y-m-d');
                        } catch (\Exception $e) {
                            $dependentBirthDate = null;
                        }
                    }
                }

                $profile->dependents()->create([
                    'name'       => $row['name'] ?? '',
                    'relation'   => $dbRelation,
                    'birth_date' => $dependentBirthDate,
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
            $apiUrl = config('staff.employee_lookup_api_url', 'https://eservices.gedco.ps/api/employees/search');

            // جرب POST مع body أولاً (الأكثر شيوعاً في APIs الجديدة)
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
                    'url' => $apiUrl,
                    'id' => $id,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json(['ok' => false, 'message' => 'تعذر الاتصال بالخدمة.'], 502);
            }

            $payload = $response->json();

            // تسجيل نتائج API للتحقق
            \Log::info('Employee lookup API response', [
                'url' => $apiUrl,
                'id' => $id,
                'status' => $response->status(),
                'payload_keys' => is_array($payload) ? array_keys($payload) : 'not_array',
                'payload_sample' => is_array($payload) ? json_encode(array_slice($payload, 0, 1, true)) : $payload,
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
                    'url' => $apiUrl,
                    'id' => $id,
                    'payload_structure' => array_keys($payload ?? [])
                ]);
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

            // تسجيل البيانات المستخرجة
            \Log::info('Employee lookup extracted data', [
                'row_keys' => is_array($row) ? array_keys($row) : 'not_array',
                'row_sample' => is_array($row) ? json_encode($row) : $row,
            ]);

            $data = [
                'full_name'      => $row['NAME'] ?? null,
                'birth_date'     => isset($row['BIRTH_DATE']) ? substr((string)$row['BIRTH_DATE'], 0, 10) : null,
                'marital_status' => $normalizeMarital($row['STATUS_NAME'] ?? null),
                'job_title'      => $row['W_NO_ADMIN_NAME'] ?? null,
                'location'       => $normalizeLocation($row['BRAN_NAME'] ?? null),
                'department'     => $row['HEAD_DEPARTMENT_NAME'] ?? $row['DEPT_NAME'] ?? $row['DEPARTMENT'] ?? $row['ADMIN_NAME'] ?? $row['DEPT'] ?? null,
                'national_id'    => $row['ID'] ?? null,
                'employee_number'=> $row['NO'] ?? null,
            ];

            // إضافة البيانات الخام للاستجابة (للتشخيص)
            $responseData = [
                'ok' => true,
                'data' => $data,
                '_debug' => [
                    'raw_payload_keys' => is_array($payload) ? array_keys($payload) : 'not_array',
                    'raw_row_keys' => is_array($row) ? array_keys($row) : 'not_array',
                    'raw_row' => $row, // البيانات الخام من API
                ]
            ];

            return response()->json($responseData);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => 'حدث خطأ غير متوقع.'], 500);
        }
    }
}
