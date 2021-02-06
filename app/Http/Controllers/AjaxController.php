<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AjaxController extends Controller
{
    public function getDashboardAttendance()
    {
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:59');
        $res = DB::table('employees AS e')
            ->leftJoin('activity_log AS al', function ($join) use ($start, $end) {
                $join->on('al.employee_fk', 'e.uid')
                    ->whereBetween('al.time_logged', [$start, $end]);
            })
            ->where('e.active', '=', TRUE)
            ->where('e.deleted', '=', FALSE)
            ->select('e.uid', 'e.firstname', 'e.surname', 'e.role', 'al.time_logged',
                'al.activity')
            ->selectRaw('TIME(al.time_logged) AS time')
            ->orderBy('e.surname')
            ->orderBy('e.firstname')
            ->orderBy('al.time_logged')
            ->get()
            ->toArray();
        //var_dump($res); exit;
        $recordset = [];
        foreach ($res as $r) {
            $recordset[$r->uid]['uid'] = $r->uid;
            $recordset[$r->uid]['name'] = $r->firstname . ' ' . $r->surname;
            $recordset[$r->uid]['role'] = $r->role;
            if ($r->activity && $r->time_logged) {
                $recordset[$r->uid]['activity_log'][] = array('activity' => $r->activity, 'time_logged' => $r->time_logged, 'time' => $r->time);
            }
        }
        //print_r($recordset); exit;
        $content = json_encode(array_values($recordset), JSON_PRETTY_PRINT);
        return response($content)
            ->header('Content-Type', 'application/json; charset=utf-8')
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function getNotifications()
    {
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:59');
        $recordset = DB::table('activity_log AS al')
            ->join('employees AS e', 'e.uid', '=', 'al.employee_fk')
            ->where('e.active', '=', TRUE)
            ->where('e.deleted', '=', FALSE)
            ->whereBetween('al.time_logged', [$start, $end])
            ->select(
                'al.uid', 'al.time_logged', 'al.activity', 'al.notification_read',
                'e.uid AS employee_id', 'e.firstname', 'e.surname', 'e.role'
            )
            ->selectRaw('TIME(al.time_logged) AS time')
            ->orderBy('al.time_logged', 'DESC')
            ->get()
            ->toArray();
        Session::put('notification_count', count($recordset));
        $content = json_encode(array_values($recordset), JSON_PRETTY_PRINT);
        return response($content)
            ->header('Content-Type', 'application/json; charset=utf-8')
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function updateNotifications(Request $request)
    {
        //print_r($request->all()); exit;
        $uid = json_decode($request->get('uid'), true);
        DB::table('activity_log')
            ->whereIn('uid', $uid)
            ->update(['notification_read' => true]);
        return response('OK');
    }

    public function countNotifications()
    {
        $c = Session::get('notification_count') ?? '"0"';
        $content = '{ "count":' . $c . ' }';
        return response($content)
            ->header('Content-Type', 'application/json; charset=utf-8')
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function getAlerts()
    {
        $res = DB::table('activity_log AS al')
            ->join('employees AS e', 'e.uid', '=', 'al.employee_fk')
            ->where('e.active', '=', TRUE)
            ->where('e.deleted', '=', FALSE)
            ->where('al.time_logged', '<', date('Y-m-d'))
            ->select('al.time_logged', 'al.activity', 'e.uid', 'e.firstname', 'e.surname')
            ->selectRaw('DATE(al.time_logged) AS day,TIME(al.time_logged) AS clock_time')
            ->orderBy('al.time_logged')
            ->orderBy('e.surname')
            ->orderBy('e.firstname')
            ->get()
            ->toArray();
        $data = [];
        foreach ($res as $r) {
            $n = $r->firstname . ' ' . $r->surname;
            $data[$n][$r->day]['activity'][] = ['time' => $r->clock_time, 'activity' => $r->activity];
        }
        //print_r($data);
        $errors = [];
        $c = 1;
        foreach ($data as $name => $days) {
            foreach ($days as $day => $activity) {
                $vals = array_values($activity);
                $arr = reset($vals);
                if (count($arr) % 2 != 0) {
                    $errors[$c] = array('name' => $name, 'date' => $day, 'activity' => $arr);
                    $c++;
                }
            }
        }
        //print_r($errors); exit;
        $content = json_encode(array_values($errors), JSON_PRETTY_PRINT);
        return response($content)
            ->header('Content-Type', 'application/json; charset=utf-8')
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function getData()
    {
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:59');
        $res = DB::table('employees AS e')
            ->leftJoin('activity_log AS al', function ($join) use ($start, $end) {
                $join->on('al.employee_fk', 'e.uid')
                    ->whereBetween('al.time_logged', [$start, $end]);
            })
            ->where('e.active', '=', TRUE)
            ->select('e.uid', 'e.firstname', 'e.surname', 'e.role', 'al.time_logged',
                'al.activity')
            ->orderBy('e.surname')
            ->orderBy('e.firstname')
            ->orderBy('al.time_logged')
            ->get()
            ->toArray();
        $recordset = [];
        foreach ($res as $r) {
            $recordset[$r->uid]['name'] = $r->firstname . ' ' . $r->surname;
            $recordset[$r->uid]['role'] = $r->role;
            if ($r->activity && $r->time_logged) {
                $recordset[$r->uid]['activity_log'][] = array('activity' => $r->activity, 'time_logged' => $r->time_logged);
            }
        }
        $content = json_encode(array_values($recordset), JSON_PRETTY_PRINT);
        return response($content)
            ->header('Content-Type', 'application/json; charset=utf-8')
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function checkUsername(Request $request)
    {
        $res = DB::table('users')
            ->where('username', '=', $request->get('username'))
            ->first();
        $content = ($res) ? '"This username is already taken! Try another."' : '"true"';
        return response($content);
    }

    public function checkAttendance()
    {
        $res = DB::table('activity_log AS al')
            ->join('employees AS e', 'e.uid', '=', 'al.employee_fk')
            ->where('al.time_logged', '<', date('Y-m-d'))
            ->where('e.active', '=', TRUE)
            ->where('e.deleted', '=', FALSE)
            ->select('al.time_logged', 'al.activity', 'e.uid', 'e.firstname', 'e.surname')
            ->selectRaw('DATE(al.time_logged) AS day,TIME(al.time_logged) AS clock_time')
            ->orderBy('al.time_logged')
            ->orderBy('e.surname')
            ->orderBy('e.firstname')
            ->get()
            ->toArray();
        $data = [];
        foreach ($res as $r) {
            $n = $r->firstname . ' ' . $r->surname;
            $data[$n][$r->day]['activity'][] = array('employee_fk' => $r->employee_fk, 'time' => $r->clock_time, 'activity' => $r->activity);
        }
        //print_r($data);
        $errors = [];
        foreach ($data as $name => $days) {
            foreach ($days as $day => $activity) {
                $array = array_values($activity);
                $arr = reset($array);
                if (count($arr) % 2 != 0) {
                    $errors[$name][$day] = $arr;
                }
            }
        }
        //print_r($errors); exit;
        $content = json_encode(array_values($errors), JSON_PRETTY_PRINT);
        return response($content)
            ->header('Content-Type', 'application/json; charset=utf-8')
            ->header('Access-Control-Allow-Origin', '*');
    }

}
