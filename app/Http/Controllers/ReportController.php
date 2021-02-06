<?php

namespace App\Http\Controllers;

use App\Domain\AppFuncs as Funcs;
use App\Domain\AppUtils as Utils;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getOverall()
    {
        return view('global.master', [
                'content' => 'reports/overall',
                'posted' => false,
            ]
        );
    }

    public function postOverall(Request $request)
    {
        //print_r($request->all()); exit;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $res = DB::table('employees AS e')
            ->select('e.uid', 'e.firstname', 'e.surname', 'e.role', 'al.time_logged', 'al.activity')
            ->selectRaw('DATE(al.time_logged) AS day')
            ->leftJoin('activity_log AS al', function ($join) use ($start_date, $end_date) {
                $join->on('al.employee_fk', '=', 'e.uid');
                $join->whereBetween('al.time_logged', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            })
            ->where('e.deleted', '=', false)
            ->orderBy('e.surname')->orderBy('e.firstname')->orderBy('al.time_logged')
            ->get()
            ->toArray();
        //print_r($res); exit;
        $data = [];
        foreach ($res as $r) {
            if ($r->day) {
                $n = $r->firstname . ' ' . $r->surname;
                $data[$n][date('D j M', strtotime($r->day))][] = array('time_logged' => $r->time_logged, 'activity' => $r->activity);
            }
        }
        //print_r($data); exit;
        return view('global.master', [
                'content' => 'reports/overall',
                'posted' => true,
                'data' => $data,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'funcs' => new Funcs(),
            ]
        );

    }

    public function getIndividual()
    {
        $employees = Employee::notDeleted()->bySurname()->get();
        return view('global.master', [
                'content' => 'reports/individual',
                'posted' => false,
                'employees' => $employees,
                'utils' => new Utils(),
                'uid' => NULL,
                'chart_data' => []
            ]
        );
    }

    public function postIndividual(Request $request)
    {
        //print_r($request->all()); exit;
        $uid = intval($request->uid);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $employees = Employee::notDeleted()->bySurname()
            ->select('uid', 'surname', 'firstname', 'role')
            ->get();
        $res = DB::table('employees AS e')
            ->select('e.uid', 'e.firstname', 'e.surname', 'e.role', 'al.time_logged', 'al.activity')
            ->selectRaw('DATE(al.time_logged) AS day')
            ->leftJoin('activity_log AS al', function ($join) use ($start_date, $end_date) {
                $join->on('al.employee_fk', '=', 'e.uid');
                $join->whereBetween('al.time_logged', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            })
            ->where('e.deleted', '=', false)
            ->where('e.uid', '=', $uid)
            ->orderBy('e.surname')->orderBy('e.firstname')->orderBy('al.time_logged')
            ->get()
            ->toArray();
        //print_r($res); exit;
        $data = [];
        foreach ($res as $r) {
            if ($r->day) {
                $data[date('D j M', strtotime($r->day))][] = array('time_logged' => $r->time_logged, 'activity' => $r->activity);
            }
        }
        //print_r($data); exit;
        $r = reset($res);
        return view('global.master', [
                'content' => 'reports/individual',
                'posted' => true,
                'uid' => $uid,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'employees' => $employees,
                'funcs' => new Funcs(),
                'utils' => new Utils(),
                'chart_data' => [],
                'data' => $data,
                'employee_name' => $r->firstname . ' ' . $r->surname,
            ]
        );
    }

    public function getAmended()
    {
        $recordset = DB::table('activity_log AS al')
            ->select('al.*', 'e.uid AS employee_id', 'e.firstname', 'e.surname', 'e.role')
            ->join('employees AS e', 'e.uid', '=', 'al.employee_fk')
            ->where('e.deleted', '=', false)
            ->whereNotNull('al.updated')
            ->whereNotNull('al.update_reason')
            ->orderBy('al.updated', 'DESC')
            ->get()
            ->toArray();
        //print_r($recordset); exit;
        return view('global.master', [
                'content' => 'reports/amended',
                'recordset' => $recordset,
            ]
        );
    }


}
