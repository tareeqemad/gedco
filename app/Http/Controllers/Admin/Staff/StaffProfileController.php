<?php

namespace App\Http\Controllers\Admin\Staff;

use App\Http\Controllers\Controller;
use App\Models\StaffProfile;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class StaffProfileController extends Controller
{


    public function index(Request $request)
    {
        $query = StaffProfile::query();


        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('national_id', 'like', "%{$search}%")
                    ->orWhere('employee_number', 'like', "%{$search}%");
            });
        }


        $profiles = $query->latest()->paginate(25);

        return view('admin.staff_profiles.index', compact('profiles', 'search'));
    }


    public function show(StaffProfile $profile)
    {
        return view('admin.staff_profiles.show', compact('profile'));
    }

    public function edit(StaffProfile $profile)
    {
        $locations      = config('staff_enums.locations');
        $maritalStatus  = config('staff_enums.marital_status');
        $houseStatus    = config('staff_enums.house_status');
        $residentStatus = config('staff_enums.status');
        $housingTypes   = config('staff_enums.housing_type');
        $readinessList  = config('staff_enums.readiness');
        $relations      = config('staff_enums.relation');

        return view('admin.staff_profiles.edit', compact(
            'profile',
            'locations',
            'maritalStatus',
            'houseStatus',
            'residentStatus',
            'housingTypes',
            'readinessList',
            'relations',
        ));
    }

    public function update(Request $request, StaffProfile $profile)
    {
        $data = $request->validate([
            'national_id'          => ['required', 'digits:9'],
            'full_name'            => ['required', 'string', 'max:255'],
            'employee_number'      => ['required', 'digits_between:1,4'],
            'job_title'            => ['nullable', 'string', 'max:255'],
            'location'             => ['required'],
            'department'           => ['nullable', 'string', 'max:255'],
            'directorate'          => ['nullable', 'string', 'max:255'],
            'section'              => ['nullable', 'string', 'max:255'],
            'marital_status'       => ['nullable', 'string'],
            'birth_date'           => ['nullable', 'string'],

            'original_address'     => ['nullable', 'string', 'max:255'],
            'house_status'         => ['nullable', 'string'],
            'status'               => ['nullable', 'string'],
            'current_address'      => ['nullable', 'string', 'max:255'],
            'housing_type'         => ['nullable', 'string'],

            'mobile'               => ['required', 'regex:/^\d{8,10}$/'],
            'mobile_alt'           => ['nullable', 'regex:/^\d{8,10}$/'],
            'whatsapp_prefix'      => ['nullable', 'in:970,972'],
            'whatsapp'             => ['nullable', 'regex:/^\d{8,10}$/'],
            'telegram'             => ['nullable', 'string', 'max:255'],
            'gmail'                => ['nullable', 'email', 'max:255'],

            'readiness'            => ['nullable', 'string'],
            'readiness_notes'      => ['nullable', 'string'],

            'has_family_incidents' => ['nullable', 'in:yes,no'],
            'family_notes'         => ['nullable', 'string'],

            'family'               => ['nullable', 'array'],


            'edits_allowed'        => ['nullable', 'integer', 'min:0'],
            'edits_remaining'      => ['nullable', 'integer', 'min:0'],
        ]);


        if (isset($data['edits_allowed'], $data['edits_remaining'])
            && $data['edits_remaining'] > $data['edits_allowed']) {

            return back()
                ->withErrors(['edits_remaining' => 'عدد المحاولات المتبقية لا يمكن أن يتجاوز إجمالي المسموح به.'])
                ->withInput();
        }

        try {
            DB::transaction(function () use ($data, $profile) {


                $familyRows = collect($data['family'] ?? [])
                    ->filter(function ($r) {
                        return filled($r['name'] ?? null)
                            || filled($r['relation'] ?? null)
                            || filled($r['birth_date'] ?? null)
                            || filled($r['is_student'] ?? null);
                    })
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


                $birthDate = null;
                if (!empty($data['birth_date'])) {
                    $dateStr = trim($data['birth_date']);

                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) {
                        $birthDate = $dateStr;
                    } else {
                        try {
                            $birthDate = Carbon::parse($dateStr)->format('Y-m-d');
                        } catch (\Throwable $e) {
                            $birthDate = null;
                        }
                    }
                }


                $profile->update([
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


                    'edits_allowed'        => array_key_exists('edits_allowed', $data)
                        ? $data['edits_allowed']
                        : $profile->edits_allowed,
                    'edits_remaining'      => array_key_exists('edits_remaining', $data)
                        ? $data['edits_remaining']
                        : $profile->edits_remaining,
                ]);


                $allowedRelations = array_keys(config('staff_enums.relation', []));

                $profile->dependents()->delete();

                foreach ($familyRows as $row) {
                    $relation = $row['relation'] ?? null;

                    if (!in_array($relation, $allowedRelations, true)) {
                        $relation = 'other';
                    }

                    $dependentBirthDate = null;
                    if (!empty($row['birth_date'])) {
                        try {
                            $dependentBirthDate = Carbon::parse($row['birth_date'])->format('Y-m-d');
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
            });

        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'duplicate_family_members') {
                return back()
                    ->withErrors([
                        'family' => 'يوجد تكرار في إدخال أحد أفراد الأسرة (نفس الاسم وتاريخ الميلاد مكرر أكثر من مرة).',
                    ])
                    ->withInput();
            }

            throw $e;
        } catch (QueryException $e) {
            $errorMessage = $e->getMessage();

            if (str_contains($errorMessage, 'Duplicate entry')
                && (str_contains($errorMessage, 'staff_profiles_employee_number_unique')
                    || str_contains($errorMessage, 'staff_profiles_national_id_unique')
                    || ($e->errorInfo[1] ?? null) == 1062)
            ) {
                return back()
                    ->withErrors([
                        'national_id' => 'رقم الهوية أو الرقم الوظيفي مستخدم في سجل آخر.',
                    ])
                    ->withInput();
            }

            return back()
                ->withErrors(['general' => 'حدث خطأ أثناء حفظ البيانات. يرجى المحاولة مرة أخرى.'])
                ->withInput();
        }

        return redirect()
            ->route('admin.staff-profiles.show', $profile->id)
            ->with('success', 'تم تعديل بيانات الموظف بنجاح ✅');
    }
}
