<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Domain\AppUtils as Utils;
use App\Domain\AppFuncs as Funcs;


class DashboardController extends Controller
{
    // main app dashboard
    public function showDashboard() {
        $start=date('Y-m-d',strtotime('-7 days'));
        $end=date('Y-m-d');
        $recordset=DB::table('employees AS e')
            ->leftJoin('activity_log AS al',function($join) use ($start,$end) {
                $join->on('al.employee_fk','e.uid')
                    ->whereBetween('al.time_logged',[$start,$end]);
            })
            ->where('e.active','=',true)
            ->where('e.deleted','=',false)
            ->select('e.uid','e.firstname','e.surname','e.role','al.time_logged','al.activity')
            ->selectRaw('DATE(al.time_logged) AS day,TIME(al.time_logged) AS time')
            ->orderBy('e.surname')
            ->orderBy('e.firstname')
            ->orderBy('al.time_logged')
            ->get()
            ->toArray();
        //print_r($recordset); exit();
        $days = [];
        for($i = 0; $i<7; $i++) {
            $days[] = date('D j M',strtotime($start. " +$i days"));
        }
        $data = [];
        foreach($recordset as $r) {
            $n = $r->firstname . ' ' . $r->surname;
            $data[$n] = array_combine(array_values($days),array_fill(0,count($days),NULL));
        }
        foreach($recordset as $r) {
            if($r->day) {
                $n = $r->firstname . ' ' . $r->surname;
                $data[$n][date('D j M',strtotime($r->day))][] = ['time_logged'=>$r->time_logged,'time'=>$r->time,'activity'=>$r->activity];
            }
        }
        //print_r($data); exit;
        // employees
        $res=DB::table('employees')
            ->where('active','=',TRUE)
            ->where('deleted','=',FALSE)
            ->selectRaw("uid,CONCAT(firstname,' ',surname) AS fullname")
            ->get()
            ->toArray();
        $employees=[];
        foreach($res as $r) {
            $employees[$r->fullname]=(string)$r->uid;
        }
        // absences
        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime('+7 days'));
        $res =DB::table('absences AS a')
            ->join('employees AS e',function($join) {
                $join->on('e.uid','a.employee_fk')->where('e.active','=',TRUE)
                    ->where('e.deleted','=',FALSE);
            })
            ->where('a.deleted','=',FALSE)
            ->whereBetween('a.absence_date',[$start,$end])
            ->select('a.*','e.initials')
            ->selectRaw("CONCAT(e.firstname,' ',e.surname) AS employee_name")
            ->orderBy('e.surname')
            ->orderBy('e.firstname')
            ->orderBy('a.absence_date')
            ->get()
            ->toArray();
        //print_r($res); exit;
        $events=[];
        foreach ($res as $r) {
            $start=$r->absence_date;
            $end=date('Y-m-d',strtotime($r->absence_date)+86400);
            $events[]=[
                'id'=>$r->uid,
                'title'=>$r->initials.(FALSE!==strpos($r->duration,'HALF_DAY')?
                        html_entity_decode(' (&#189; day)'):''),
                'info'=>"{$r->employee_name}: {$r->absence_type}".
                    ($r->notes?" ({$r->notes})":""),
                'start'=>$start,
                'end'=>$end,
                'allDay'=>TRUE,
                'className'=>array(strtolower($r->duration),
                    'cal-'.strtolower($r->absence_type)),
            ];
        }
        return view('global.master',[
                'content'=>'dashboard/main',
                'recordset'=>$recordset,
                'days'=>$days,
                'data'=>$data,
                'employees'=>$employees,
                'events'=>$events,
                'barchart_colours'=>config('app.barchart_colours'),
                'funcs'=>new Funcs(),
            ]
        );
    }

}
