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
    /**
     * تطبيق الترتيب على الاستعلام حسب المعامل sort.
     * القيم المدعومة: name_asc, name_desc, emp_asc, emp_desc, date_asc, date_desc (افتراضي)
     */
    private function applySort($query, ?string $sort)
    {
        return match ($sort) {
            'name_asc'  => $query->orderBy('full_name', 'asc'),
            'name_desc' => $query->orderBy('full_name', 'desc'),
            'emp_asc'   => $query->orderByRaw('CAST(employee_number AS UNSIGNED) ASC'),
            'emp_desc'  => $query->orderByRaw('CAST(employee_number AS UNSIGNED) DESC'),
            'date_asc'  => $query->orderBy('created_at', 'asc'),
            default     => $query->orderBy('created_at', 'desc'),
        };
    }

    public function index(Request $request)
    {
        $query = StaffProfile::query();

        // بحث منفصل لكل حقل (AND بين الحقول)
        if ($nid = trim((string) $request->input('national_id'))) {
            $query->where('national_id', 'like', "%{$nid}%");
        }
        if ($emp = trim((string) $request->input('employee_number'))) {
            $query->where('employee_number', 'like', "%{$emp}%");
        }
        if ($name = trim((string) $request->input('name'))) {
            $query->where('full_name', 'like', "%{$name}%");
        }

        // فلترة حسب الحالة (مقيم/نازح)
        if ($status = $request->input('status')) {
            if (in_array($status, ['resident', 'displaced'])) {
                $query->where('status', $status);
            }
        }

        // فلترة حسب الجاهزية
        if ($readiness = $request->input('readiness')) {
            if (in_array($readiness, ['working', 'ready', 'not_ready'])) {
                $query->where('readiness', $readiness);
            }
        }

        // فلترة حسب عمر الأطفال (المعالين)
        $ageFrom = $request->input('age_from');
        $ageTo = $request->input('age_to');
        if ($ageFrom !== null && $ageFrom !== '' || $ageTo !== null && $ageTo !== '') {
            $query->whereHas('dependents', function ($q) use ($ageFrom, $ageTo) {
                $q->whereIn('relation', ['son', 'daughter']);
                $q->whereNotNull('birth_date');

                $today = Carbon::today();
                // العمر "من" يعني أقصى تاريخ ميلاد (الأصغر عمراً)
                if ($ageFrom !== null && $ageFrom !== '') {
                    $maxBirthDate = $today->copy()->subYears((int) $ageFrom);
                    $q->where('birth_date', '<=', $maxBirthDate->format('Y-m-d'));
                }
                // العمر "إلى" يعني أدنى تاريخ ميلاد (الأكبر عمراً)
                if ($ageTo !== null && $ageTo !== '') {
                    $minBirthDate = $today->copy()->subYears((int) $ageTo + 1)->addDay();
                    $q->where('birth_date', '>=', $minBirthDate->format('Y-m-d'));
                }
            });
        }

        $profiles = $this->applySort($query, $request->input('sort'))
            ->paginate(200)
            ->withQueryString();

        // طلب AJAX لتحميل صفحة إضافية (infinite scroll)
        if ($request->ajax() || $request->boolean('ajax')) {
            $locations   = ['1'=>__('admin.staff_profiles_data.location_1'),'2'=>__('admin.staff_profiles_data.location_2'),'3'=>__('admin.staff_profiles_data.location_3'),'4'=>__('admin.staff_profiles_data.location_4'),'6'=>__('admin.staff_profiles_data.location_6'),'7'=>__('admin.staff_profiles_data.location_7'),'8'=>__('admin.staff_profiles_data.location_8')];
            $statusMap   = ['resident'=>['label'=>__('admin.staff_profiles_data.status_resident_label'),'class'=>'bg-success-subtle text-success'], 'displaced'=>['label'=>__('admin.staff_profiles_data.status_displaced_label'),'class'=>'bg-danger-subtle text-danger']];
            $readinessMap = ['working'=>['label'=>__('admin.staff_profiles_data.readiness_working_label'),'class'=>'bg-success text-white'], 'ready'=>['label'=>__('admin.staff_profiles_data.readiness_ready_label'),'class'=>'bg-primary text-white'], 'not_ready'=>['label'=>__('admin.staff_profiles_data.readiness_not_ready_label'),'class'=>'bg-warning text-dark']];

            return response()->json([
                'html'      => view('admin.staff_profiles._rows', compact('profiles', 'locations', 'statusMap', 'readinessMap'))->render(),
                'has_more'  => $profiles->hasMorePages(),
                'loaded_to' => $profiles->lastItem(),
                'total'     => $profiles->total(),
                'count'     => $profiles->count(),
            ]);
        }

        // حساب الإحصائيات - فقط الحالات المحددة
        $stats = [
            'total'       => StaffProfile::count(),
            // حالات الجاهزية (3 حالات فقط)
            'working'     => StaffProfile::where('readiness', 'working')->count(),
            'ready'       => StaffProfile::where('readiness', 'ready')->count(),
            'not_ready'   => StaffProfile::where('readiness', 'not_ready')->count(),
            // حالات الحالة (حالتان فقط)
            'resident'    => StaffProfile::where('status', 'resident')->count(),
            'displaced'   => StaffProfile::where('status', 'displaced')->count(),
        ];

        return view('admin.staff_profiles.index', compact('profiles', 'stats'));
    }


    public function export(Request $request)
    {
        $query = StaffProfile::query();

        if ($nid = trim((string) $request->input('national_id'))) {
            $query->where('national_id', 'like', "%{$nid}%");
        }
        if ($emp = trim((string) $request->input('employee_number'))) {
            $query->where('employee_number', 'like', "%{$emp}%");
        }
        if ($name = trim((string) $request->input('name'))) {
            $query->where('full_name', 'like', "%{$name}%");
        }

        if ($status = $request->input('status')) {
            if (in_array($status, ['resident', 'displaced'])) {
                $query->where('status', $status);
            }
        }

        if ($readiness = $request->input('readiness')) {
            if (in_array($readiness, ['working', 'ready', 'not_ready'])) {
                $query->where('readiness', $readiness);
            }
        }

        // فلترة حسب عمر الأطفال
        $ageFrom = $request->input('age_from');
        $ageTo = $request->input('age_to');
        if ($ageFrom !== null && $ageFrom !== '' || $ageTo !== null && $ageTo !== '') {
            $query->whereHas('dependents', function ($q) use ($ageFrom, $ageTo) {
                $q->whereIn('relation', ['son', 'daughter']);
                $q->whereNotNull('birth_date');
                $today = Carbon::today();
                if ($ageFrom !== null && $ageFrom !== '') {
                    $maxBirthDate = $today->copy()->subYears((int) $ageFrom);
                    $q->where('birth_date', '<=', $maxBirthDate->format('Y-m-d'));
                }
                if ($ageTo !== null && $ageTo !== '') {
                    $minBirthDate = $today->copy()->subYears((int) $ageTo + 1)->addDay();
                    $q->where('birth_date', '>=', $minBirthDate->format('Y-m-d'));
                }
            });
        }

        $query->with(['dependents' => function ($q) use ($ageFrom, $ageTo) {
            $q->whereIn('relation', ['son', 'daughter'])
              ->orderBy('birth_date', 'asc');

            // لو تم تحديد فلتر العمر، نعرض فقط الأبناء ضمن النطاق
            if (($ageFrom !== null && $ageFrom !== '') || ($ageTo !== null && $ageTo !== '')) {
                $q->whereNotNull('birth_date');
                $today = Carbon::today();

                if ($ageFrom !== null && $ageFrom !== '') {
                    $maxBirthDate = $today->copy()->subYears((int) $ageFrom);
                    $q->where('birth_date', '<=', $maxBirthDate->format('Y-m-d'));
                }
                if ($ageTo !== null && $ageTo !== '') {
                    $minBirthDate = $today->copy()->subYears((int) $ageTo + 1)->addDay();
                    $q->where('birth_date', '>=', $minBirthDate->format('Y-m-d'));
                }
            }
        }]);

        $profiles = $this->applySort($query, $request->input('sort'))->get();

        $statusMap = ['resident' => 'مقيم', 'displaced' => 'نازح'];
        $readinessMap = ['working' => 'باشر العمل', 'ready' => 'جاهز', 'not_ready' => 'غير جاهز'];
        $relationMap = ['son' => 'ابن', 'daughter' => 'ابنة'];
        $locations = ['1'=>'المقر الرئيسي','2'=>'مقر غزة','3'=>'مقر الشمال','4'=>'مقر الوسطى','6'=>'مقر خانيونس','7'=>'مقر رفح','8'=>'مقر الصيانة - غزة'];

        $filename = 'staff-profiles-' . now()->format('Y-m-d') . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $columnHeaders = [
            '#', 'الاسم الكامل', 'رقم الهوية', 'الرقم الوظيفي', 'المسمى الوظيفي',
            'المقر', 'الإدارة', 'القسم', 'الحالة', 'الجاهزية',
            'الجوال', 'جوال بديل', 'واتساب', 'البريد',
            'العنوان الأصلي', 'العنوان الحالي', 'تاريخ التسجيل',
            'عدد الأبناء', 'الأبناء وتواريخ الميلاد'
        ];

        $callback = function () use ($profiles, $statusMap, $readinessMap, $relationMap, $locations, $columnHeaders) {
            echo chr(0xEF) . chr(0xBB) . chr(0xBF);
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta charset="UTF-8"><style>td,th{mso-number-format:\@;text-align:right;vertical-align:top;}</style></head>';
            echo '<body><table border="1">';

            // Header row
            echo '<tr>';
            foreach ($columnHeaders as $h) {
                echo '<th style="background:#4472C4;color:#fff;font-weight:bold;padding:6px 10px;">' . e($h) . '</th>';
            }
            echo '</tr>';

            // صف واحد لكل موظف — الأبناء في عمودين منفصلين
            foreach ($profiles as $i => $p) {
                $childrenCount = $p->dependents->count();

                // بناء نص الأبناء: كل ابن بسطر داخل الخلية
                $childrenText = $p->dependents->map(function ($child) use ($relationMap) {
                    $rel   = $relationMap[$child->relation] ?? '';
                    $birth = $child->birth_date?->format('Y-m-d') ?? '—';
                    $name  = $child->name ?? '';
                    return trim(($rel ? $rel . ': ' : '') . $name . ' (' . $birth . ')');
                })->implode("\n");

                $row = [
                    $i + 1,
                    $p->full_name,
                    $p->national_id,
                    $p->employee_number,
                    $p->job_title,
                    $locations[$p->location] ?? $p->location,
                    $p->department,
                    $p->section,
                    $statusMap[$p->status] ?? $p->status,
                    $readinessMap[$p->readiness] ?? $p->readiness,
                    $p->mobile,
                    $p->mobile_alt,
                    $p->whatsapp,
                    $p->gmail,
                    $p->original_address,
                    $p->current_address,
                    $p->created_at?->format('Y-m-d'),
                    $childrenCount,
                    $childrenText,
                ];

                echo '<tr>';
                foreach ($row as $idx => $cell) {
                    $style = 'padding:4px 8px;';
                    // العمود الأخير (الأبناء) — استخدم <br> للأسطر في إكسل
                    if ($idx === count($row) - 1 && $cell) {
                        $style .= 'mso-data-placement:same-cell;max-width:400px;';
                        $html = nl2br(e($cell), false);
                        echo '<td style="' . $style . '">' . $html . '</td>';
                    } else {
                        echo '<td style="' . $style . '">' . e($cell ?? '') . '</td>';
                    }
                }
                echo '</tr>';
            }

            echo '</table></body></html>';
        };

        return response()->stream($callback, 200, $headers);
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
