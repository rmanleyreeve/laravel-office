<?php

namespace App\Providers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

define('FORMAT_CURRENCY_GBP_SIMPLE','"£"#,##0.00_-');
define('FORMAT_DATE_DATETIME_CUSTOM','d/m/yy hh:mm:ss');

class AppFuncsProvider {

    // APP-SPECIFIC FUNCTIONS ===================================

    // alert JS options
    public static function toastr_options(){
        return '"closeButton": false,"debug": false,"newestOnTop": false,"progressBar": false,"positionClass": "toast-top-center","preventDuplicates": false,"onclick": null,"showDuration": "250","hideDuration": "250","timeOut": "2000","extendedTimeOut": "1000","showEasing": "swing","hideEasing": "linear","showMethod": "fadeIn","hideMethod": "fadeOut"';
    }

    public static function _up($c) {
        $id = intval(Session::get('user_id'));
        $res = DB::table('users')
            ->where('user_id','=',$id)
            ->select('administrator')
            ->first();
        if(!$res) {
            return false;
        } else {
            if($res->administrator) {
                return true;
            } else {
                $tmp = [];
                $uprs = DB::table('link_user_permission AS lup')
                    ->join('user_permissions AS up','up.id','=','lup.permission_fk')
                    ->where('lup.user_fk','=',$id)
                    ->select('up.permission_code')
                    ->get()
                    ->toArray();
                foreach($uprs as $r) {
                    $tmp[] = $r->ermission_code;
                }
                //pp($tmp); exit;
                Session::put('user.permissions',$tmp);
                if(is_array($c)) {
                    return (count(array_intersect($c,Session::get('user.permissions')))>0);
                } else {
                    return(in_array($c,Session::get('user.permissions')));
                }
            }
        }
    }

    // calculate minutes present per day
    public function calcMinsPresent($day_activity_log) {
        $mins_pres = 0;
        if($day_activity_log && 'ENTRY' == reset($day_activity_log)['activity'] && 'EXIT' == end($day_activity_log)['activity']) {
            foreach($day_activity_log as $i=>$e) {
                if('EXIT' == $e['activity']) {
                    $t_in = strtotime($day_activity_log[$i-1]['time_logged']);
                    $t_out = strtotime($e['time_logged']);
                    $mins_pres += ceil(($t_out - $t_in)/60);
                }
            }
        }
        return $mins_pres;
    }
    // calculate minutes break per day
    function calcMinsBreak($day_activity_log) {
        $mins_break = 0;
        if($day_activity_log && 'ENTRY' == reset($day_activity_log)['activity'] && 'EXIT' == end($day_activity_log)['activity']) {
            foreach($day_activity_log as $i=>$e) {
                if('ENTRY' == $e['activity']) {
                    if($day_activity_log[$i-1]) { // previous exit
                        $t_out = strtotime($day_activity_log[$i-1]['time_logged']);
                        $t_in = strtotime($e['time_logged']);
                        $mins_break += ceil(($t_in - $t_out)/60);
                    }
                }
            }
        }
        return $mins_break;
    }

}


