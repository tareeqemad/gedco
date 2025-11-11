<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StaffProfile;
use App\Models\StaffDependent;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class ProfileDependents extends Controller
{
    public function create(): View
    {
        // صفحة عامة: فقط نعرض الفورم. مافي Auth.
        // نحترم أي جلسة سابقة لو صار تكرار وتم قفل النموذج.
        $locked = session('locked', false);
        $profile = null; // ما في بيانات مستخدم لعرضها
        return view('staff.profile_dependents.add_dependent', compact('locked','profile'));
    }

    public function store(Request $request): RedirectResponse
    {
        // 1) فالديشن خفيف على الضروري فقط
        $validated = $request->validate([
            'full_name'       => 'required|string|max:255',
            'employee_number' => 'required|string|max:100',
            'mobile'          => 'required|string|max:50',

            // كل ما يلي اختياري — ما بنوقف فورم عليه
            'birth_date'           => 'nullable|date',
            'national_id'          => 'nullable|string|max:100',
            'job_title'            => 'nullable|string|max:255',
            'location'             => 'nullable|string|max:255',
            'department'           => 'nullable|string|max:255',
            'directorate'          => 'nullable|string|max:255',
            'section'              => 'nullable|string|max:255',
            'marital_status'       => 'nullable|in:single,married,widowed,divorced',
            'family_members_count' => 'nullable|integer|min:1|max:10',

            'has_family_incidents' => 'nullable|in:no,yes',
            'family_notes'         => 'nullable|string|max:2000',

            'original_address' => 'nullable|string|max:500',
            'house_status'     => 'nullable|in:intact,partial,demolished',
            'status'           => 'nullable|in:resident,displaced',
            'current_address'  => 'nullable|string|max:500',
            'housing_type'     => 'nullable|in:house,apartment,tent,other',

            'mobile_alt' => 'nullable|string|max:50',
            'whatsapp'   => 'nullable|string|max:50',
            'telegram'   => 'nullable|string|max:100',
            'gmail'      => 'nullable|email|max:255',

            'readiness'       => 'nullable|in:ready,not_ready',
            'readiness_notes' => 'nullable|string|max:2000',

            // المعالين — مرن جدًا
            'family'                => 'nullable|array',
            'family.*.name'         => 'nullable|string|max:255',
            'family.*.relation'     => 'nullable|in:spouse,son,daughter,other',
            'family.*.birth_date'   => 'nullable|date',
            'family.*.is_student'   => 'nullable|in:yes,no',
        ]);

        // 2) منع التكرار: نفس الرقم الوظيفي أو رقم الهوية
        $exists = StaffProfile::query()
            ->where('employee_number', $request->string('employee_number'))
            ->when($request->filled('national_id'), fn($q) =>
            $q->orWhere('national_id', $request->string('national_id'))
            )
            ->exists();

        if ($exists) {
            return back()
                ->with('locked', true)
                ->with('locked_msg', 'أنت مسجّل مسبقًا، لا يمكن تحديث البيانات من هذه الصفحة العامة.')
                ->withInput();
        }

        // 3) حفظ مرن بدون ما "يضرب" لو في خانات ناقصة
        DB::transaction(function () use ($validated, $request) {
            $profileData = array_filter([
                'full_name'            => $validated['full_name'],
                'employee_number'      => $validated['employee_number'],
                'mobile'               => $validated['mobile'],

                'birth_date'           => $validated['birth_date'] ?? null,
                'national_id'          => $validated['national_id'] ?? null,
                'job_title'            => $validated['job_title'] ?? null,
                'location'             => $validated['location'] ?? null,
                'department'           => $validated['department'] ?? null,
                'directorate'          => $validated['directorate'] ?? null,
                'section'              => $validated['section'] ?? null,
                'marital_status'       => $validated['marital_status'] ?? null,
                'family_members_count' => $validated['family_members_count']
                    ?? max(1, count($request->input('family', []))),

                'has_family_incidents' => $validated['has_family_incidents'] ?? 'no',
                'family_notes'         => $validated['family_notes'] ?? null,

                'original_address'     => $validated['original_address'] ?? null,
                'house_status'         => $validated['house_status'] ?? null,
                'status'               => $validated['status'] ?? null,
                'current_address'      => $validated['current_address'] ?? null,
                'housing_type'         => $validated['housing_type'] ?? null,

                'mobile_alt'           => $validated['mobile_alt'] ?? null,
                'whatsapp'             => $validated['whatsapp'] ?? null,
                'telegram'             => $validated['telegram'] ?? null,
                'gmail'                => $validated['gmail'] ?? null,

                'readiness'            => $validated['readiness'] ?? null,
                'readiness_notes'      => $validated['readiness_notes'] ?? null,
            ], fn($v) => !is_null($v) && $v !== '');

            $profile = StaffProfile::create($profileData);

            $family = collect($request->input('family', []))
                ->filter(fn($row) => filled($row['name'] ?? null) || filled($row['relation'] ?? null));

            foreach ($family as $row) {
                StaffDependent::create([
                    'staff_profile_id' => $profile->id,
                    'name'       => $row['name'] ?? '',
                    'relation'   => $row['relation'] ?? 'other',
                    'birth_date' => $row['birth_date'] ?? null,
                    'is_student' => ($row['is_student'] ?? '') === 'yes',
                ]);
            }
        });

        return back()->with('success', 'تم حفظ البيانات بنجاح ✅');
    }
}
