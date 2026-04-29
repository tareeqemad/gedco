<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JoinRequest;
use Illuminate\Http\Request;

class JoinRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = JoinRequest::query();

        if ($search = trim((string) $request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', "%{$search}%")
                  ->orWhere('applicant_phone', 'like', "%{$search}%")
                  ->orWhere('applicant_email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('referrer_name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($source = $request->get('source')) {
            if (in_array($source, JoinRequest::SOURCES, true)) {
                $query->where('source', $source);
            }
        }

        if ($request->filled('is_read')) {
            $query->where('is_read', $request->boolean('is_read'));
        }

        $sort = in_array($request->get('sort'), ['created_at', 'source'], true)
            ? $request->get('sort') : 'created_at';
        $dir  = $request->get('dir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $dir);

        $requests = $query->paginate(15)->appends($request->query());

        $unreadCount = JoinRequest::where('is_read', false)->count();

        $sourceCounts = JoinRequest::selectRaw('source, COUNT(*) as total')
            ->groupBy('source')
            ->pluck('total', 'source')
            ->toArray();

        return view('admin.join-requests.index', compact('requests', 'unreadCount', 'sourceCounts'));
    }

    public function show(JoinRequest $joinRequest)
    {
        if (!$joinRequest->is_read) {
            $joinRequest->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return view('admin.join-requests.show', ['joinRequest' => $joinRequest]);
    }

    public function markAsRead(Request $request, JoinRequest $joinRequest)
    {
        $joinRequest->update(['is_read' => true, 'read_at' => now()]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', __('admin.join_requests.flash.marked_read'));
    }

    public function markAsUnread(Request $request, JoinRequest $joinRequest)
    {
        $joinRequest->update(['is_read' => false, 'read_at' => null]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', __('admin.join_requests.flash.marked_unread'));
    }

    public function export(Request $request)
    {
        $query = JoinRequest::query();

        if ($search = trim((string) $request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', "%{$search}%")
                  ->orWhere('applicant_phone', 'like', "%{$search}%")
                  ->orWhere('applicant_email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('referrer_name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }
        if (($source = $request->get('source')) && in_array($source, JoinRequest::SOURCES, true)) {
            $query->where('source', $source);
        }
        if ($request->filled('is_read')) {
            $query->where('is_read', $request->boolean('is_read'));
        }

        $sort = in_array($request->get('sort'), ['created_at', 'source'], true)
            ? $request->get('sort') : 'created_at';
        $dir  = $request->get('dir') === 'asc' ? 'asc' : 'desc';
        $rows = $query->orderBy($sort, $dir)->get();

        $sourceLabels = [];
        foreach (JoinRequest::SOURCES as $src) {
            $sourceLabels[$src] = __('admin.join_requests.sources.' . $src);
        }

        $filename = 'join-requests-' . now()->format('Y-m-d_His') . '.xls';
        $headers = [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control'       => 'max-age=0',
        ];

        $columnHeaders = [
            '#',
            __('admin.join_requests.applicant_name'),
            __('admin.join_requests.applicant_phone'),
            __('admin.join_requests.applicant_email'),
            __('admin.join_requests.company_name'),
            __('admin.join_requests.source'),
            __('admin.join_requests.referrer_name'),
            __('admin.join_requests.locale'),
            __('admin.labels.status'),
            __('admin.join_requests.ip_address'),
            __('admin.join_requests.submitted_at'),
        ];

        $callback = function () use ($rows, $columnHeaders, $sourceLabels) {
            echo chr(0xEF) . chr(0xBB) . chr(0xBF); // UTF-8 BOM
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta charset="UTF-8">';
            echo '<style>td,th{mso-number-format:\@;text-align:right;vertical-align:top;font-family:Tahoma,Arial,sans-serif;font-size:12px;}</style>';
            echo '</head><body><table border="1" cellspacing="0" cellpadding="6">';

            echo '<tr>';
            foreach ($columnHeaders as $h) {
                echo '<th style="background:#F85C00;color:#fff;font-weight:bold;padding:8px 10px;border:1px solid #d4d4d4;">'
                    . e($h) . '</th>';
            }
            echo '</tr>';

            foreach ($rows as $i => $jr) {
                $row = [
                    $i + 1,
                    $jr->applicant_name,
                    $jr->applicant_phone,
                    $jr->applicant_email,
                    $jr->company_name,
                    $sourceLabels[$jr->source] ?? $jr->source,
                    $jr->referrer_name,
                    strtoupper($jr->locale ?? ''),
                    $jr->is_read ? __('admin.ui.read') : __('admin.ui.unread'),
                    $jr->ip_address,
                    optional($jr->created_at)->format('Y-m-d H:i'),
                ];
                echo '<tr>';
                foreach ($row as $cell) {
                    echo '<td style="padding:5px 8px;border:1px solid #e5e7eb;">' . e((string)($cell ?? '')) . '</td>';
                }
                echo '</tr>';
            }

            echo '</table></body></html>';
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy(JoinRequest $joinRequest)
    {
        $joinRequest->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.join-requests.index')
            ->with('success', __('admin.join_requests.flash.deleted'));
    }
}
