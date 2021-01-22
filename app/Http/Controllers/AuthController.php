<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Domain\AppUtils as Utils;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function default()
    {
        return redirect()->route('home');
    }
    public function login(){
        return view('auth/login');
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
                    ->join('user_permissions AS up','up.id','=','lup.permission_fk')
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

    public function getForgotPassword(){
        return view('auth/forgot-password');
    }
    public function postForgotPassword(Request $request){
        $dataObj = User::where('active','=',1)->where('username','=',$request->username)->first();
        if(!$dataObj) {
            $request->session()->put('alert', ['type'=>'error','msg'=>'No matching user!']);
            return redirect()->back();
        }
        //build token
        $u = $dataObj->user_id;
        $t = time();
        $k = env('reset_key');
        $hash = md5("u={$u}&t={$t}&k={$k}");
        $dataObj->password_reset_token = "{$u}@{$hash}@{$t}";
        $dataObj->save();
        // email
        $vars = [
            'email' => $dataObj->user_email,
            'fullname' => $dataObj->fullname,
            'url' => $request->getSchemeAndHttpHost() . "/reset/{$u}/{$t}/{$hash}",
            'host' => $request->getHttpHost(),
        ];
        Mail::send(
            'auth/reset-email',
            $vars,
            function($message) use ($vars) {
                $message
                    ->to($vars['email'])
                    ->from("web@{$vars['host']}", 'RMR')
                    ->subject('Password Reset');
            }
        );
        if (Mail::failures()) {
            var_dump(Mail::failures()); exit;
        }
        return view('auth/forgot-pass-ok');
    }

    public function getResetPassword($u,$t,$h, Request $request){
        if(($t+86400) < time()) {
            // check 24 hour expiry
            $request->session()->put('alert', ['type'=>'error','msg'=>'This reset code has expired!']);
            return redirect()->route('home');
        } else {
            $dataObj = User::find($u);
            if(!$dataObj) {
                $request->session()->put('alert', ['type'=>'error','msg'=>'Invalid reset code!']);
                return redirect()->route('home');
            }
            // check the hash & database token
            $k = env('reset_key');
            $hash = md5("u={$u}&t={$t}&k={$k}");
            if(($hash === $h) && "{$u}@{$hash}@{$t}" === $dataObj->password_reset_token)  {
                return view('auth/reset-password',[
                        'user_id'=>$u,
                        'token' => "{$u}@{$hash}@{$t}",
                    ]
                );
            } else {
                $request->session()->put('alert', ['type'=>'error','msg'=>'No matching user!']);
                return redirect()->route('home');
            }
        }

    }
    public function postResetPassword(Request $request){
        $dataObj = User::where('active','=',1)
            ->where('user_id','=',$request->user_id)
            ->where('password_reset_token','=',$request->token)
            ->first();
        if(!$dataObj) {
            $request->session()->put('alert', ['type'=>'error','msg'=>'Invalid reset code!']);
            return redirect()->route('home');
        }
        $dataObj->password = password_hash($request->Password,PASSWORD_BCRYPT);
        $dataObj->password_reset_token = NULL;
        $dataObj->save();
        $request->session()->put('alert', ['type'=>'success','msg'=>'Password updated. Please log in.']);
        return redirect()->route('home');
    }

    public function getChangePassword(){
        return view('auth/change-password');
    }
    public function postChangePassword(Request $request){
        $dataObj = User::find(Session::get('user_id'));
        if($dataObj && password_verify($request->oldPassword,$dataObj->password)) {
            $dataObj->password = password_hash($request->newPassword,PASSWORD_BCRYPT);
            $dataObj->password_reset_token = NULL;
            $dataObj->save();
            $request->session()->flush();
            $request->session()->put('alert', ['type'=>'success','msg'=>'Password updated. Please log in.']);
            return redirect()->route('default');
        } else {
            $request->session()->put('alert', ['type'=>'error','msg'=>'No matching user!']);
            return redirect()->route('home');
        }
    }

}
