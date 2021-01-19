<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Monolog\Utils;


class AuthController extends Controller
{
    public function default(Request $request)
    {
        if(Session::get('user_id')) {
            return redirect()->route('home');
        } else {
            return view('auth/login');
        }
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            $login = Auth::user();
            $request->session()->regenerate();
            $sa = DB::table('settings')->get();
            $settings = [];
            foreach($sa as $s) {
                $settings[$s->k] = $s->v;
            }
            $request->session()->put('settings',$settings);
            $login_t = date('Y-m-d H:i:s',time());
            $login->last_login = $login_t;
            $login->save();
            //Utils::chlog('Logged in');
            $user = $login->toArray();
            unset($user['password']);
            if($user['administrator']) {
                $uprs = DB::table('user_permissions')->select('permission_code')->get()->toArray();
            } else {
                $uprs = DB::table('link_user_permission AS lup')
                    ->join('user_permissions AS up','up.id','='.'lup.permission_fk')
                    ->where('lup.user_fk','=',intval($user['user_id']))
                    ->get()
                    ->toArray();
            }
            $tmp = [];
            foreach($uprs as $up) {
                $tmp[] = $up->permission_code;
            }
            //pp($tmp); exit;
            $user['permissions'] = $tmp;
            $request->session()->put('user',$user);
            $request->session()->put('user_id',$user['user_id']);
            $request->session()->put('alert', ['type'=>'success','msg'=>'You are now logged in to the system']);
            return redirect()->route('home');
        }
        $request->session()->put('alert', ['type'=>'error','msg'=>'Login Failed']);
        return redirect()->route('default');
    }

    public function logout(Request $request){
        $request->session()->flush();
        $request->session()->put('alert', ['type'=>'info','msg'=>'You are now logged out of the system']);
        return redirect()->route('default');
    }
}
