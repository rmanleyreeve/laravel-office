<?php

namespace App\Http\Controllers;

use App\Models\ChangeLog;
use App\Models\User;
use App\Domain\AppFuncs as Funcs;
use App\Domain\AppUtils as Utils;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function getUsers(){
        $recordset = User::where('deleted','=',false)
            ->select('*')
            ->selectRaw("(SELECT GROUP_CONCAT(permission_name ORDER BY permission_name SEPARATOR ', ') FROM user_permissions WHERE id IN (SELECT permission_fk FROM link_user_permission WHERE user_fk=users.user_id)) AS permissions")
            ->selectRaw('(SELECT COUNT(*) FROM change_log WHERE user_fk=users.user_id) AS activity_count')
            ->orderBy('user_id','DESC')
            ->get();
        //print_r($recordset); exit;
        return view('global.master',[
                'content'=>'users/list',
                'recordset'=>$recordset,
                'funcs' => new Funcs(),
            ]
        );
    }

    public function getAddUser(){
        $permissions = DB::table('user_permissions')->select('id','permission_name')->get()->toArray();
        return view('global.master',[
                'content'=>'users/add-edit',
                'action'=>'Add New',
                'selected' => ['active'=>1],
                'user_permissions' => $permissions,
                'sel_user_permissions' => [],
                'utils'=> new Utils(),
            ]
        );
    }
    public function postAddUser(Request $request){
        //print_r($request->all()); exit;
        $request->request->add(['password_reset_token'=>NULL]);
        $request->merge(['password' => password_hash($request->password,PASSWORD_BCRYPT)]);
        $dataObj = User::create($request->except(['_token']));
        $id = $dataObj->user_id;
        DB::beginTransaction();
        foreach(array_unique($request->permission_fk) as $fk) {
            DB::table('link_user_permission')->insert([
              'user_fk' => $id,
                'permission_fk' => $fk
            ]);
        }
        DB::commit();
        Utils::chlog('Added user',$dataObj->toArray());
        $dir = storage_path("app/public/media/user");
        if($request->hasfile('user_image')) {
            $request->validate([
                'user_image.*' => 'mimes:png,gif,jpg,jpeg'
            ]);
            $file = $request->file('user_image');
            //var_dump($file); exit;
            if(!file_exists($dir)){ mkdir($dir,0777,true); }
            $pi = pathinfo($file->getClientOriginalName());
            $img = Image::make($file->getRealPath());
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save("{$dir}/user_{$id}.jpg", 90,'jpeg');
            Utils::chlog("Uploaded image for user {$id}",$file->toArray());
        }
        $request->session()->put('alert', ['type'=>'success','msg'=>'The new user was added successfully.']);
        return redirect()->to('/users');
    }

    public function viewUser($id){
        $user = User::where('deleted','=',false)->find($id);
        if(!$user) {
            return response('<h1 class="error">No matching user!</h1>');
        } else {
            $pna = array_reduce($user->permission_names->toArray(),function($foo,$a){
                $foo[] = $a['permission_name'];
                return $foo;
            },[]);
            $user->permission_names_array = implode(', ',$pna);
            return view('global.modal',[
                    'content'=>'users/view',
                    'selected'=>$user,
                    'utils' => new Utils(),
                ]
            );
        }
    }

    public function getEditUser(Request $request, $id) {
        $user = User::where('deleted','=',false)->find($id);
        if(!$user) {
            $request->session()->put('alert', ['type'=>'error','msg'=>'No matching user!']);
            return redirect()->to('/users');
        } else {
            $user_permissions = DB::table('user_permissions')->select('id','permission_name')->get();
            $sel_user_permissions=[];
            if ($user->administrator) {
                foreach ($user_permissions as $p) {
                    $sel_user_permissions[] = $p->id;
                }
            } else {
                $up = $user->permissions->toArray();
                $sel_user_permissions = array_reduce($up,function($foo,$a){
                    $foo[] = $a['permission_fk'];
                    return $foo;
                },[]);
            }
            //print_r($sel_user_permissions); exit;
        }
        return view('global.master',[
                'content'=>'users/add-edit',
                'action'=>'Edit',
                'selected' => $user,
                'user_permissions' => $user_permissions,
                'sel_user_permissions' => $sel_user_permissions,
                'utils'=> new Utils(),
            ]
        );
    }
    public function postEditUser(Request $request, $id) {
        //print_r($request->all()); exit;
        $dataObj = User::where('deleted','=',false)->find($id);
        if(!$dataObj) {
            $request->session()->put('alert', ['type'=>'error','msg'=>'No matching user!']);
            return redirect()->to('/users');
        } else {
            $prev = $dataObj->toArray();
            if('' == $request->password) {
                $request->request->remove('password');
            } else {
                $request->merge(['password' => password_hash($request->password,PASSWORD_BCRYPT)]);
            }
            $dataObj->update($request->except('_token'));
            DB::beginTransaction();
            DB::table('link_user_permission')->where('user_fk','=',$id)->delete();
            foreach(array_unique((array)$request->permission_fk) as $fk) {
                DB::table('link_user_permission')->insert([
                    'user_fk' => $id,
                    'permission_fk' => $fk
                ]);
            }
            DB::commit();
            Utils::chlog('Added user',$dataObj->toArray());
            $dir = storage_path("app/public/media/user");
            if($request->hasfile('user_image')) {
                $request->validate([
                    'user_image.*' => 'mimes:png,gif,jpg,jpeg'
                ]);
                $file = $request->file('user_image');
                //var_dump($file); exit;
                if(!file_exists($dir)){ mkdir($dir,0777,true); }
                $pi = pathinfo($file->getClientOriginalName());
                $img = Image::make($file->getRealPath());
                $img->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save("{$dir}/user_{$id}.jpg", 90,'jpeg');
                Utils::chlog("Uploaded image for user {$id}",$file->toArray());
            }
            $request->session()->put('alert', ['type'=>'success','msg'=>'The selected user has been updated in the system.']);
            return redirect()->to('/users');
        }
    }

    public function deleteUser(Request $request, $id){
        $dataObj = User::where('deleted','=',false)->find($id);
        if(!$dataObj) {
            $request->session()->put('alert', ['type'=>'error','msg'=>'No matching user!']);
        } else {
            $dataObj->deleted = true;
            $dataObj->save();
            $request->session()->put('alert', ['type'=>'success','msg'=>'The selected user has been deleted from the system.']);
        }
        return redirect()->to('/users');
    }

    public function getProfile(Request $request, $id){
        $user = User::where('deleted','=',false)->find($id);
        if(!$user) {
            $request->session()->put('alert', ['type'=>'error','msg'=>'No matching user!']);
            return redirect()->to('/users');
        } else {
            $pna = array_reduce($user->permission_names->toArray(),function($foo,$a){
                $foo[] = $a['permission_name'];
                return $foo;
            },[]);
            $user->permission_names_array = implode(', ',$pna);
            return view('global.master',[
                    'content'=>'users/profile',
                    'selected'=>$user,
                    'menu' => '/users'
                ]
            );
        }
    }

    public function getUserImage(Request $request, $id){
        $user = User::where('deleted','=',false)->find($id);
        if(!$user) {
            return response('<h1 class="error">No matching user!</h1>');
        } else {
            return view('global.modal',[
                    'content'=>'users/image',
                    'selected'=>$user,
                ]
            );
        }

    }
    public function postUserImage(Request $request, $id){
        $dir = storage_path("app/public/media/user");
        if($request->hasfile('user_image')) {
            $request->validate([
                'user_image.*' => 'mimes:png,gif,jpg,jpeg'
            ]);
            $file = $request->file('user_image');
            //var_dump($file); exit;
            if(!file_exists($dir)){ mkdir($dir,0777,true); }
            $pi = pathinfo($file->getClientOriginalName());
            $img = Image::make($file->getRealPath());
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save("{$dir}/user_{$id}.jpg", 90,'jpeg');
            Utils::chlog("Uploaded image for user {$id}",$file->toArray());
            $request->session()->put('alert', ['type'=>'success','msg'=>'The selected user profile image has been updated in the system.']);
        }
        return redirect()->to("/users/{$id}/profile");
    }

    public function exportUsers(Request $request){
        $recordset = User::where('deleted','=',false)
            ->orderBy('surname')->orderBy('firstname')
            ->get();
        if(!$recordset) {
            $request->session()->put('alert', ['type'=>'error','msg'=>'No records found!']);
            return redirect()->to('/users');
        } else {
            $fields=Schema::getColumnListing('users');
            unset($fields[array_search('user_image',$fields,TRUE)]);
            return response()
                ->view('global.export',[
                    'fields'=>$fields,
                    'recordset'=>$recordset,
                    'utils'=>new Utils(),
                ],200)
                ->header('Content-Type','text/csv');
        }
    }

    public function getUserActivity(Request $request, $id){
        $recordset = DB::table('users AS u')
            ->select('cl.uid','cl.created_at','cl.activity','cl.url','u.fullname','u.username')
            ->selectRaw('(SELECT IF(`data` IS NULL, 0, 1)) AS hasdata')
            ->join('change_log AS cl','cl.user_fk','=','u.user_id')
            ->where('u.user_id','=',$id)
            ->orderBy('cl.created_at','DESC')
            ->get()
            ->toArray();
        //print_r($recordset); exit();
        if(!$recordset) {
            $request->session()->put('alert', ['type'=>'error','msg'=>'No user activity found!']);
            return redirect()->to('/users');
        }
        return view('global.master',[
                'content'=>'users/user-activity',
                'recordset'=>$recordset,
                'funcs' => new Funcs(),
                'menu' => '/users',
                'selected' => reset($recordset),
            ]
        );
    }

    public function getActivityByDate(Request $request, $d) {
        $recordset = DB::table('users AS u')
            ->select('cl.uid','cl.created_at','cl.activity','cl.url','u.fullname')
            ->selectRaw('(SELECT IF(`data` IS NULL, 0, 1)) AS hasdata')
            ->join('change_log AS cl','cl.user_fk','=','u.user_id')
            ->whereRaw('cl.created_at BETWEEN ? AND ? + INTERVAL 1 DAY',[$d,$d])
            ->orderBy('cl.created_at','DESC')
            ->get()
            ->toArray();
        //print_r($recordset); exit();
        if(!$recordset) {
            $request->session()->put('alert', ['type'=>'warning','msg'=>'No user activity found!']);
        }
        return view('global.master',[
                'content'=>'users/activity',
                'date' => $d,
                'timestamp'=> strtotime($d),
                'recordset'=>$recordset,
                'funcs' => new Funcs(),
                'menu' => '/users/activity/date/'.date('Y-m-d'),
            ]
        );
    }

    public function viewData($id){
        $cl = ChangeLog::find($id);
        if(!$cl) {
            return response('<h1 class="error">No matching record!</h1>');
        } else {
            return view('global.modal',[
                    'content'=>'users/activity-data',
                    'selected'=>$cl,
                    'utils' => new Utils(),
                ]
            );
        }
    }

    public function exportActivity(Request $request){
        $recordset = DB::table('users AS u')
            ->select('cl.uid','cl.created_at','cl.activity','cl.url','u.fullname','u.username')
            ->selectRaw("REPLACE(cl.`data`,'\"','') AS `data`")
            ->join('change_log AS cl','cl.user_fk','=','u.user_id')
            ->orderBy('cl.created_at','DESC')
            ->get()
            ->toArray();
        //print_r($recordset); exit();
        if(!$recordset) {
            $request->session()->put('alert', ['type'=>'error','msg'=>'No user activity found!']);
            return redirect()->to('/users');
        } else {
            $fields = ['username','fullname','created_at','activity','url','data'];
            $recordset = json_decode(json_encode($recordset), true);
            return response()
                ->view('global.export',[
                    'fields' => $fields,
                    'recordset'=>$recordset,
                    'utils' => new Utils(),
                ], 200)
                ->header('Content-Type', 'text/csv');
        }

    }

}



