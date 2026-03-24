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
        // Honeypot: reject if bot filled hidden field
        if ($request->filled('website')) {
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'تم إرسال رسالتك بنجاح.']);
            }
            return redirect()->route('site.contact')->with('success', 'تم إرسال رسالتك بنجاح.');
        }

        $message = ContactMessage::create($request->validated());

        // إرسال إيميل (اختياري)
        try {
            $settings = SiteSetting::first();
            $contactEmail = $settings->contact_email ?? $settings->email ?? config('mail.from.address');

            if ($contactEmail) {
                // Sanitize fields to prevent email header injection
                $safeName = str_replace(["\r", "\n"], ' ', $message->name);
                $safeEmail = str_replace(["\r", "\n"], ' ', $message->email);
                $safeSubject = str_replace(["\r", "\n"], ' ', $message->subject);

                Mail::raw(
                    "رسالة جديدة من نموذج الاتصال\n\n" .
                    "الاسم: {$safeName}\n" .
                    "البريد الإلكتروني: {$safeEmail}\n" .
                    "الهاتف: " . ($message->phone ?? 'غير محدد') . "\n" .
                    "الموضوع: {$safeSubject}\n\n" .
                    "الرسالة:\n{$message->message}",
                    function ($mail) use ($contactEmail, $safeSubject) {
                        $mail->to($contactEmail)
                            ->subject('رسالة جديدة من نموذج الاتصال: ' . $safeSubject);
                    }
                );
            }
        } catch (\Exception $e) {
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
