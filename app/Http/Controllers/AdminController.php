<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Monolog\Utils;

class AdminController extends Controller
{
    public function getAmendAttendance()
    {
        $employees = Employee::notDeleted()
            ->select('uid', 'surname', 'firstname', 'role')
            ->bySurname()
            ->get();
        return view('global.master', [
                'content' => 'admin/select',
                'employees' => $employees,
            ]
        );
    }

    public function postAmendAttendance(Request $request)
    {
        //print_r($request->all()); exit;
        $uid = intval($request->uid);
        $day = $request->amend_date;
        $res = DB::table('activity_log AS al')
            ->select('al.*', 'e.firstname', 'e.surname', 'e.role')
            ->selectRaw('TIME(al.time_logged) AS clock_time')
            ->join('employees as e', 'e.uid', '=', 'al.employee_fk')
            ->where('e.deleted', '=', false)
            ->whereBetween('al.time_logged', [$day . ' 00:00:00', $day . ' 23:59:59'])
            ->where('al.employee_fk', '=', $uid)
            ->orderBy('al.time_logged')
            ->get()
            ->toArray();
        //print_r($res); exit;
        if (!$res) {
            return redirect()->to('/admin/amend-attendance');
        }
        return view('global.master', [
                'content' => 'admin/amend',
                'amend_date' => $day,
                'recordset' => $res,
                'selected' => reset($res),
            ]
        );
    }

    public function executeAmendAttendance(Request $request)
    {
        //print_r($request->all()); exit;
        DB::beginTransaction();
        foreach ($request->changes as $uid => $t) {
            DB::table('activity_log')
                ->where('uid', '=', $uid)
                ->where('time_logged', '<>', "{$request->day} {$t}")
                ->update([
                    'original_value' => DB::raw('`time_logged`'),
                    'updated' => date('Y-m-d H:i:s'),
                    'time_logged' => "{$request->day} {$t}",
                    'update_reason' => $request->reason[$uid],
                    'record_type' => 'MANUAL'
                ]);
        }
        DB::commit();
        Utils::chlog("Amended employee attendance", $request->except('_token'));
        $request->session()->flash('alert', ['type' => 'success', 'msg' => 'The selected employee attendance has been updated in the system.']);
        return redirect()->to('/dashboard');
    }

    public function checkAttendanceErrors()
    {
        $res = DB::table('activity_log AS al')
            ->select('al.time_logged', 'al.activity', 'al.employee_fk', 'e.uid', 'e.firstname', 'e.surname')
            ->selectRaw('DATE(al.time_logged) AS day,TIME(al.time_logged) AS clock_time')
            ->join('employees AS e', 'e.uid', '=', 'al.employee_fk')
            ->where('al.time_logged', '<', date('Y-m-d'))
            ->where('e.deleted', '=', false)
            ->orderBy('al.time_logged')
            ->orderBy('e.surname')
            ->orderBy('e.firstname')
            ->get()
            ->toArray();
        $data = array();
        foreach ($res as $r) {
            $n = $r->firstname . ' ' . $r->surname;
            $data[$n][$r->day]['activity'][] = array('employee_fk' => $r->employee_fk, 'time' => $r->clock_time, 'activity' => $r->activity);
        }
        //pp($data);
        $errors = array();
        foreach ($data as $name => $days) {
            foreach ($days as $day => $activity) {
                $array = array_values($activity);
                $arr = reset($array);
                if (count($arr) % 2 != 0) {
                    $errors[$name][$day] = $arr;
                }
            }
        }
        return view('global.master', [
                'content' => 'admin/error-check',
                'recordset' => $errors,
            ]
        );

    }

    public function getRepairErrors($id, $date)
    {
        $res = DB::table('activity_log AS al')
            ->select('al.employee_fk', 'al.time_logged', 'al.activity', 'e.firstname', 'e.surname')
            ->selectRaw('TIME(al.time_logged) AS clock_time')
            ->join('employees AS e', 'e.uid', '=', 'al.employee_fk')
            ->where('al.employee_fk', '=', intval($id))
            ->where('e.deleted', '=', false)
            ->whereRaw('DATE(al.time_logged)=?', [$date])
            ->orderBy('al.time_logged')
            ->get()
            ->toArray();
        return view('global.master', [
                'content' => 'admin/repair',
                'recordset' => $res,
                'amend_date' => $date,
                'selected' => reset($res),
            ]
        );
    }

    public function postRepairErrors(Request $request, $id, $date)
    {
        //print_r($request->all()); exit;
        $dataObj = new ActivityLog();
        $dataObj->activity = $request->activity;
        $dataObj->time_logged = "{$date} " . $request->clock_time;
        $dataObj->employee_fk = intval($id);
        $dataObj->record_type = 'MANUAL';
        $dataObj->save();
        Utils::chlog('Repaired employee attendance record', $dataObj->toArray());
        $request->session()->flash('alert', ['type' => 'success', 'msg' => 'The attendance record was repaired successfully.']);
        return redirect()->to('/admin/check-attendance');
    }

    public function getManualEntry()
    {
        $employees = Employee::notDeleted()->isActive()
            ->select('uid', 'surname', 'firstname', 'role')
            ->bySurname()
            ->get();
        return view('global.master', [
                'content' => 'admin/manual',
                'employees' => $employees,
            ]
        );
    }

    public function postManualEntry(Request $request)
    {
        //print_r($request->all()); exit;
        $dataObj = new ActivityLog();
        $dataObj->employee_fk = intval($request->employee_fk);
        $dataObj->activity = $request->activity;
        $dataObj->time_logged = date('Y-m-d H:i:s');
        $dataObj->record_type = 'MANUAL';
        $dataObj->save();
        Utils::chlog('Manually entered employee attendance record', $request->except('_token'));
        $request->session()->flash('alert', ['type' => 'success', 'msg' => 'Manual entry recorded successfully.']);
        return redirect()->route('home');
    }

}
