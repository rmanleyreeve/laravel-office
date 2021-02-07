<?php

namespace App\Http\Middleware;

use App\Domain\AppFuncs as Funcs;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionAuth
{
    /**
     * Handle an incoming request, check session, if permissions check these
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed $permissions
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        if (
            empty($permissions)
            || Funcs::_up($permissions)
            || (
                in_array('SELF', $permissions)
                && Auth::id() == $request->route('id')
            )
        ) {
            return $next($request);
        }
        abort(403);
    }
}


