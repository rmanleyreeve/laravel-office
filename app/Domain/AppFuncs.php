<?php

namespace App\Domain;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

define('FORMAT_CURRENCY_GBP_SIMPLE', '"£"#,##0.00_-');
define('FORMAT_DATE_DATETIME_CUSTOM', 'd/m/yy hh:mm:ss');

class AppFuncs
{

    // APP-SPECIFIC FUNCTIONS ===================================

    // toastr.js alert options
    public static function toastr_options(): string
    {
        return '
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "250",
        "hideDuration": "250",
        "timeOut": "2000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
        ';
    }

    public static function _up($c): bool
    {
        if (!Auth::check()) {
            return false;
        } else {
            if (Auth::user()->administrator) {
                return true;
            } else {
                $tmp = [];
                $uprs = DB::table('link_user_permission AS lup')
                    ->join('user_permissions AS up', 'up.id', '=', 'lup.permission_fk')
                    ->where('lup.user_fk', '=', Auth::id())
                    ->select('up.permission_code')
                    ->get()
                    ->toArray();
                foreach ($uprs as $r) {
                    $tmp[] = $r->permission_code;
                }
                //pp($tmp); exit;
                Session::put('user.permissions', $tmp);
                if (is_array($c)) {
                    return (count(array_intersect($c, Session::get('user.permissions'))) > 0);
                } else {
                    return (in_array($c, Session::get('user.permissions')));
                }
            }
        }
    }

    // calculate minutes present per day
    public function calcMinsPresent($day_activity_log): int
    {
        $mins_pres = 0;
        if ($day_activity_log && 'ENTRY' == reset($day_activity_log)['activity'] && 'EXIT' == end($day_activity_log)['activity']) {
            foreach ($day_activity_log as $i => $e) {
                if ('EXIT' == $e['activity']) {
                    $t_in = strtotime($day_activity_log[$i - 1]['time_logged']);
                    $t_out = strtotime($e['time_logged']);
                    $mins_pres += ceil(($t_out - $t_in) / 60);
                }
            }
        }
        return $mins_pres;
    }

    // calculate minutes break per day
    public function calcMinsBreak($day_activity_log): int
    {
        $mins_break = 0;
        if ($day_activity_log && 'ENTRY' == reset($day_activity_log)['activity'] && 'EXIT' == end($day_activity_log)['activity']) {
            foreach ($day_activity_log as $i => $e) {
                if ('ENTRY' == $e['activity']) {
                    if (isset($day_activity_log[$i - 1])) { // previous exit
                        $t_out = strtotime($day_activity_log[$i - 1]['time_logged']);
                        $t_in = strtotime($e['time_logged']);
                        $mins_break += ceil(($t_in - $t_out) / 60);
                    }
                }
            }
        }
        return $mins_break;
    }

}


