<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\JoinRequestStoreRequest;
use App\Models\JoinRequest;
use Illuminate\Http\JsonResponse;

class JoinRequestController extends Controller
{
    public function store(JoinRequestStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (($data['source'] ?? null) !== 'friend_employee') {
            $data['referrer_name'] = null;
        }

        $data['locale']     = session('direction', 'rtl') === 'rtl' ? 'ar' : 'en';
        $data['ip_address'] = $request->ip();

        JoinRequest::create($data);

        return response()->json([
            'success' => true,
            'message' => __('admin.join_requests.public.success'),
        ]);
    }
}
