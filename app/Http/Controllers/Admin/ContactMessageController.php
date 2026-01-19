<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        // البحث
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // فلتر حسب حالة القراءة
        if ($request->has('is_read')) {
            $isRead = $request->boolean('is_read');
            $query->where('is_read', $isRead);
        }

        // الترتيب
        $sort = $request->get('sort', 'created_at');
        $dir = $request->get('dir', 'desc');
        $query->orderBy($sort, $dir);

        // Pagination
        $messages = $query->paginate(15)->appends($request->query());

        // عدد الرسائل غير المقروءة
        $unreadCount = ContactMessage::where('is_read', false)->count();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('admin.contact-messages.partials.table', compact('messages'))->render(),
                'pagination' => view('admin.contact-messages.partials.pagination', compact('messages'))->render(),
                'total' => $messages->total(),
                'unread_count' => $unreadCount,
            ]);
        }

        return view('admin.contact-messages.index', compact('messages', 'unreadCount'));
    }

    public function show(ContactMessage $contactMessage)
    {
        // تحديد الرسالة كمقروءة عند فتحها
        if (!$contactMessage->is_read) {
            $contactMessage->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function markAsRead(Request $request, ContactMessage $contactMessage)
    {
        $contactMessage->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديد الرسالة كمقروءة',
            ]);
        }

        return back()->with('success', 'تم تحديد الرسالة كمقروءة');
    }

    public function markAsUnread(Request $request, ContactMessage $contactMessage)
    {
        $contactMessage->update([
            'is_read' => false,
            'read_at' => null,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديد الرسالة كغير مقروءة',
            ]);
        }

        return back()->with('success', 'تم تحديد الرسالة كغير مقروءة');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الرسالة بنجاح',
            ]);
        }

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'تم حذف الرسالة بنجاح');
    }
}
