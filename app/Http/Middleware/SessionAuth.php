<?php

namespace App\Http\Middleware;

use App\Domain\AppFuncs as Funcs;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SessionAuth
{
    /**
     * Handle an incoming request, check session, if permissions check these
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param mixed $permissions
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        //var_dump($permissions); exit();
        if (!Session::get('user_id')) {
            return redirect()->route('login');
        } else {
            if(empty($permissions)) {
                return $next($request);
            } else if(Funcs::_up($permissions)) {
                return $next($request);
            } else {
                abort(403);
            }
        }

    }
}


