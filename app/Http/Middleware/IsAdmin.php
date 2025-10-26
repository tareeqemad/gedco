<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle($request, \Closure $next)
    {
        $u = $request->user();
        if (!$u) abort(403);

        if ($u->hasAnyRole(['super-admin','admin']) || $u->is_admin) {
            return $next($request);
        }
        abort(403);
    }
}
