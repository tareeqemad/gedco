<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StaffProfile;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProfileEditAuthController extends Controller
{
    /**
     * عرض فورم إدخال رقم الهوية قبل التعديل / التسجيل
     */
    public function showVerifyForm(Request $request): View
    {
        $prefill = old('national_id', $request->query('id'));

        return view('staff.profile_dependents.verify', [
            'prefill_national_id' => $prefill,
        ]);
    }

    /**
     * منطق التحقق:
     *  - لو الهوية موجودة في API وغير موجودة في DB -> تحويل لصفحة create
     *  - لو الهوية غير موجودة في API -> رسالة خطأ
     *  - لو الهوية موجودة في DB + في API -> فتح صفحة edit
     */
    public function verify(Request $request)
    {
        // تنظيف + تحقق
        $request->merge([
            'national_id' => preg_replace('/\D/', '', (string) $request->input('national_id')),
        ]);

        $data = $request->validate([
            'national_id' => ['required', 'digits:9'],
        ], [
            'national_id.required' => 'رقم الهوية مطلوب.',
            'national_id.digits'   => 'رقم الهوية يجب أن يكون 9 أرقام.',
        ]);

        $nationalId = $data['national_id'];

        // 1) نتأكد أولاً إن رقم الهوية موجود في الـ API
        if (! $this->apiHasEmployee($nationalId)) {
            return back()
                ->withErrors(['national_id' => 'رقم الهوية غير صحيح أو غير موجود في النظام.'])
                ->withInput();
        }

        // 2) هل يوجد ملف للموظف في قاعدة البيانات؟
        $profile = StaffProfile::where('national_id', $nationalId)->first();

        // حالة: موجود في الـ API وغير موجود في DB -> نوديه على create
        if (! $profile) {
            return redirect()
                ->route('staff.profile.create')
                ->withInput(['national_id' => $nationalId])
                ->with('info', 'تم التحقق من رقم الهوية، الرجاء استكمال تسجيل البيانات.');
        }

        // حالة: موجود في DB + موجود في API -> نسمح له بالتعديل
        // نضبط السيشن بما يتوافق مع EnsureStaffEditSession
        session([
            'allowed_edit_profile_id' => $profile->id,
        ]);

        return redirect()
            ->route('staff.profile.edit', $profile->id)
            ->with('info', 'تم التحقق من رقم الهوية، يمكنك الآن تعديل بياناتك.');
    }


    protected function apiHasEmployee(string $nationalId): bool
    {
        $apiUrl = config('staff.employee_lookup_api_url', 'https://eservices.gedco.ps/api/employees/search');

        try {
            // جرب POST أولاً
            $response = Http::timeout(10)
                ->acceptJson()
                ->asJson()
                ->post($apiUrl, ['id' => $nationalId]);

            // fallback: GET ?id=
            if (! $response->ok()) {
                $response = Http::timeout(10)
                    ->acceptJson()
                    ->get($apiUrl, ['id' => $nationalId]);
            }

            // fallback: GET ?national_id=
            if (! $response->ok()) {
                $response = Http::timeout(10)
                    ->acceptJson()
                    ->get($apiUrl, ['national_id' => $nationalId]);
            }

            if (! $response->ok()) {
                \Log::error('Employee verify API failed', [
                    'url'    => $apiUrl,
                    'id'     => $nationalId,
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return false;
            }

            $payload = $response->json();


            $row = null;
            if (isset($payload['data_rows']) && is_array($payload['data_rows'])) {
                $row = $payload['data_rows'][0] ?? null;
            } elseif (isset($payload['data']) && is_array($payload['data'])) {
                $row = is_array($payload['data'][0] ?? null)
                    ? $payload['data'][0]
                    : $payload['data'];
            } elseif (isset($payload[0]) && is_array($payload[0])) {
                $row = $payload[0];
            } elseif (is_array($payload) && isset($payload['NAME'])) {
                $row = $payload;
            }

            if (! is_array($row)) {
                \Log::warning('Employee verify: No data found for id', [
                    'url'      => $apiUrl,
                    'id'       => $nationalId,
                    'payload'  => is_array($payload) ? array_keys($payload) : 'not_array',
                ]);
                return false;
            }

             if (! empty($row['ID']) && preg_replace('/\D/', '', (string) $row['ID']) !== $nationalId) {
                \Log::warning('Employee verify: ID mismatch', [
                    'requested_id' => $nationalId,
                    'api_id'       => $row['ID'],
                ]);
                return false;
            }

            return true;

        } catch (\Throwable $e) {
            \Log::error('Employee verify API unexpected error', [
                'id'      => $nationalId,
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
