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

        $existing = \App\Models\StaffProfile::query()
            ->where('employee_number', (int)$data['employee_number'])
            ->orWhere('national_id',   (int)$data['national_id'])
            ->first();

        if ($existing) {
            // نحاول تخمين أفضل “by/value” نمررها للفورم:
            $by    = $existing->national_id == (int)$data['national_id'] ? 'national_id' : 'employee_number';
            $value = $by === 'national_id' ? $existing->national_id : $existing->employee_number;

            return redirect()->route('staff.profile.verify.form', ['by' => $by, 'value' => $value])
                ->with('info', 'الرقم الوظيفي/رقم الهوية مستخدم مسبقًا. أدخل كلمة المرور لمتابعة التعديل.')
                ->withInput();
        }
        // فحص مبكر (بانر قفل أجمل من رسالة حمراء)
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
                    ->filter(fn($r) => filled($r['name'] ?? null) || filled($r['relation'] ?? null) || filled($r['birth_date'] ?? null) || filled($r['is_student'] ?? null));

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

                    // إعداد التعديل
                    'password_hash'        => Hash::make($data['password']),
                    'edits_allowed'        => 1,
                    'edits_remaining'      => 1,
                ]);

                foreach ($familyRows as $row) {
                    $profile->dependents()->create([
                        'name'       => $row['name'] ?? '',
                        'relation'   => $row['relation'] ?? 'other',
                        'birth_date' => $row['birth_date'] ?? null,
                        'is_student' => ($row['is_student'] ?? '') === 'yes',
                    ]);
                }

                $profile->update(['family_members_count' => max(1, $familyRows->count())]);
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
}
