<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Employee;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Domain\AppUtils as Utils;
use App\Domain\AppFuncs as Funcs;


class AttendanceController extends Controller {

    public function getDailyActivity($date,$id) {
        $recordset=DB::table('activity_log AS al')
            ->join('employees AS e',function($join) {
                $join->on('e.uid','al.employee_fk')->where('e.active','=',TRUE)
                    ->where('e.deleted','=',FALSE);
            })
            ->where('al.employee_fk','=',$id)
            ->whereRaw('DATE(al.time_logged)=?',[$date])
            ->select('al.time_logged','al.activity','e.uid','e.firstname','e.surname')
            ->selectRaw('TIME(al.time_logged) AS time')
            ->orderBy('al.time_logged')
            ->get()
            ->toArray();
        //var_dump($recordset); exit;
        return view('global.modal',[
                'content'=>'attendance/day',
                'date'=>$date,
                'recordset'=>$recordset,
            ]
        );
    }

    public function getWeeklyAttendance($year,$week) {
        $weekstart=strtotime(sprintf("%4dW%02d",$year,$week));
        $start=date('Y-m-d',$weekstart);
        $end=date('Y-m-d',strtotime('+1 week',$weekstart));
        // filter out current day's activity
        if (date('W')===$week) {
            $end=date('Y-m-d');
        }
        $res=DB::table('employees AS e')
            ->leftJoin('activity_log AS al',function($join) use ($start,$end) {
                $join->on('al.employee_fk','e.uid')
                    ->whereBetween('al.time_logged',[$start,$end]);
            })
            ->where('e.active','=',TRUE)
            ->where('e.deleted','=',FALSE)
            ->select('e.uid','e.firstname','e.surname','e.role','al.time_logged',
                'al.activity')
            ->selectRaw('DATE(al.time_logged) AS day')
            ->orderBy('e.surname')
            ->orderBy('e.firstname')
            ->orderBy('al.time_logged')
            ->get()
            ->toArray();
        // create empty day/person structure
        $days=[];
        for ($i=0;$i<7;$i++) {
            $days[$i]=date('D j M',strtotime($start." +$i days"));
        }
        $days=array_unique($days);
        $data=[];
        foreach ($res as $r) {
            $n=$r->firstname.' '.$r->surname;
            $data[$n]=array_combine(array_values($days),array_fill(0,count($days),NULL));
        }
        foreach ($res as $r) {
            if ($r->day) {
                $n=$r->firstname.' '.$r->surname;
                $data[$n][date('D j M',strtotime($r->day))][]=
                    array('time_logged'=>$r->time_logged,'activity'=>$r->activity);
            }
        }
        //pp($data); exit;
        // employees
        $res=DB::table('employees')
            ->where('deleted','=',FALSE)
            ->select('uid')->selectRaw("CONCAT(firstname,' ',surname) AS fullname")
            ->get()->toArray();
        $employees=[];
        foreach ($res as $r) {
            $employees[$r->fullname]=(string)$r->uid;
        }
        return view('global.master',[
                'content'=>'attendance/week',
                'menu'=>'/attendance/week/'.date('o/W'),
                'year'=>$year,
                'week'=>$week,
                'weekstart'=>$weekstart,
                'weekstop'=>strtotime("+7 day",$weekstart),
                'recordset'=>$res,
                'data'=>$data,
                'days'=>$days,
                'employees'=>$employees,
                'barchart_colours'=>config('app.barchart_colours'),
                'funcs'=>new Funcs(),
            ]
        );
    }

    public function getMonthlyAttendance($year,$month) {
        $s=strtotime($year.'-'.$month.'-01');
        $start=date('Y-m-d H:i:s',$s);
        $e=strtotime('+1 month',strtotime($start));
        $end=date('Y-m-d H:i:s',$e-1);
        $res=DB::table('employees AS e')
            ->leftJoin('activity_log AS al',function($join) use ($start,$end) {
                $join->on('al.employee_fk','e.uid')
                    ->whereBetween('al.time_logged',[$start,$end]);
            })
            ->where('e.active','=',TRUE)
            ->where('e.deleted','=',FALSE)
            ->select('e.uid','e.firstname','e.surname','e.role','al.time_logged',
                'al.activity')
            ->selectRaw('DATE(al.time_logged) AS day')
            ->orderBy('e.surname')
            ->orderBy('e.firstname')
            ->orderBy('al.time_logged')
            ->get()
            ->toArray();
        $days=[];
        for ($i=$s;$i<$e;$i+=86400) {
            $days[$i]=date('D j M',$i);
        }
        $days=array_unique($days);
        $data=[];
        foreach ($res as $r) {
            $n=$r->firstname.' '.$r->surname;
            $data[$n]=array_combine(array_values($days),array_fill(0,count($days),NULL));
        }
        foreach ($res as $r) {
            if ($r->day) {
                $n=$r->firstname.' '.$r->surname;
                $data[$n][date('D j M',strtotime($r->day))][]=
                    array('time_logged'=>$r->time_logged,'activity'=>$r->activity);
            }
        }
        $totals=[];
        foreach (array_keys($data) as $k) {
            $totals[$k]['total_present']=NULL;
            $totals[$k]['total_break']=NULL;
        }
        //print_r($days);
        //print_r($totals); exit;
        //print_r($data); exit;
        // absences
        $res=DB::table('absences AS a')
            ->join('employees AS e',function($join) {
                $join->on('e.uid','a.employee_fk')->where('e.active','=',TRUE)
                    ->where('e.deleted','=',FALSE);
            })
            ->where('a.deleted','=',FALSE)
            ->whereBetween('a.absence_date',[$start,$end])
            ->select('e.firstname','e.surname','a.absence_date','a.absence_type',
                'a.duration','a.notes')
            ->orderBy('e.surname')
            ->orderBy('e.firstname')
            ->orderBy('a.absence_date')
            ->get()
            ->toArray();
        $abs=[];
        foreach ($res as $r) {
            $n=$r->firstname.' '.$r->surname;
            $abs[$n][date('D j M',strtotime($r->absence_date))]=
                array('type'=>$r->absence_type,'duration'=>$r->duration,
                    'notes'=>$r->notes);
        }
        $res=DB::table('employees')
            ->where('deleted','=',FALSE)
            ->select('uid')->selectRaw("CONCAT(firstname,' ',surname) AS fullname")
            ->get()->toArray();
        $employees=[];
        foreach ($res as $r) {
            $employees[$r->fullname]=(string)$r->uid;
        }
        return view('global.master',[
                'content'=>'attendance/month',
                'menu'=>'/attendance/month/'.date('Y/m'),
                'months'=>config('app.months'),
                'year'=>$year,
                'month'=>$month,
                'monthstart'=>$s,
                'monthstop'=>$e,
                'recordset'=>$res,
                'data'=>$data,
                'days'=>$days,
                'employees'=>$employees,
                'absences'=>$abs,
                'barchart_colours'=>config('app.barchart_colours'),
                'funcs'=>new Funcs(),
                'utils'=>new Utils(),
                'totals'=>$totals,
            ]
        );
    }


}
