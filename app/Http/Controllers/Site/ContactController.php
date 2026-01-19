<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\ContactRequest;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ContactController extends Controller
{
    public function index()
    {
        $currentDir = session('direction', 'rtl');
        $settings = SiteSetting::first();
        
        return view('site.contact.index', [
            'currentDir' => $currentDir,
            'settings' => $settings,
        ]);
    }

    public function store(ContactRequest $request)
    {
        $message = ContactMessage::create($request->validated());

        // إرسال إيميل (اختياري)
        try {
            $settings = SiteSetting::first();
            $contactEmail = $settings->contact_email ?? $settings->email ?? config('mail.from.address');
            
            if ($contactEmail) {
                Mail::raw(
                    "رسالة جديدة من نموذج الاتصال\n\n" .
                    "الاسم: {$message->name}\n" .
                    "البريد الإلكتروني: {$message->email}\n" .
                    "الهاتف: " . ($message->phone ?? 'غير محدد') . "\n" .
                    "الموضوع: {$message->subject}\n\n" .
                    "الرسالة:\n{$message->message}",
                    function ($mail) use ($contactEmail, $message) {
                        $mail->to($contactEmail)
                            ->subject('رسالة جديدة من نموذج الاتصال: ' . $message->subject);
                    }
                );
            }
        } catch (\Exception $e) {
            // لا نوقف العملية إذا فشل الإيميل
            \Log::warning('Failed to send contact email', ['error' => $e->getMessage()]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.',
            ]);
        }

        return redirect()->route('site.contact')
            ->with('success', 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.');
    }
}
