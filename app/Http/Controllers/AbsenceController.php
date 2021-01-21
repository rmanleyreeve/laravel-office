<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Employee;
use App\Providers\AppFuncsProvider as Funcs;
use App\Providers\AppUtilsProvider as Utils;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class AbsenceController extends Controller
{
    public function getCalendar(){
        $recordset = Absence::where('deleted', '=',false)
            ->with('employee_name')
            ->orderBy('absence_date')
            ->get();
        //var_dump($recordset); exit();
        $events = [];
        foreach($recordset as $r) {
            $start = $r->absence_date;
            $end = date('Y-m-d',strtotime($r->absence_date)+86400);
            $events[] = [
                'id'=>$r->uid,
                'info'=>"{$r->employee_name['name']}: {$r->absence_type}" . ($r->notes ? " ({$r->notes})":""),
                'title'=>$r->employee_name['initials']. (false!==strpos($r->duration,'HALF_DAY')?html_entity_decode(' (&#189; day)'):''),
                'start'=>$start,
                'end'=>$end,
                'allDay'=>true,
                'className'=>[strtolower($r->duration),'cal-'.strtolower($r->absence_type)],
            ];
        }
        return view('global.master',[
                'content'=>'absences/month',
                'events' => $events,
                'utils'=> new Utils(),
            ]
        );
    }

    public function listAbsences(){
        $start_date = date('Y-m-d',strtotime('-1 month'));
        $res = DB::table('employees AS e')
            ->select('e.uid','e.firstname','e.surname','e.role','a.uid AS absence_id','a.absence_date','a.absence_type','a.duration','a.notes')
            ->leftJoin('absences AS a', function($join) use($start_date) {
                $join->on('a.employee_fk','=','e.uid');
                $join->where('a.deleted','=',false);
                $join->where('a.absence_date','>', $start_date);
            })
            ->where('e.active','=',true)->where('e.deleted','=',false)
            ->orderBy('e.surname')->orderBy('e.firstname')->orderBy('a.absence_date')
            ->get()
            ->toArray();
        //print_r($res); exit();
        $data = [];
        foreach($res as $r) {
            $n = $r->firstname . ' ' . $r->surname;
            $data[$n] = [];
        }
        foreach($res as $r) {
            $n = $r->firstname . ' ' . $r->surname;
            if($r->absence_date) {
                $data[$n][] = [
                    'absence_id'=>$r->absence_id,
                    'date'=>$r->absence_date,
                    'type'=>$r->absence_type,
                    'duration'=>$r->duration,
                    'notes'=>$r->notes
                ];
            }
        }
        return view('global.master',[
                'content'=>'absences/list',
                'recordset' => $data,
                'utils'=> new Utils(),
                'start_date' => $start_date,
            ]
        );
    }

    public function getAddAbsence(){
        $sql = "SELECT uid,surname,firstname,role FROM employees WHERE active=TRUE AND deleted=FALSE ORDER BY surname,firstname;";
        $employees = Employee::where('active','=',true)->where('deleted','=',false)
            ->orderBy('surname')->orderBy('firstname')
            ->select('uid','surname','firstname','role')
            ->get();
        $durations = Utils::enumSelect('absences','duration');
        return view('global.master',[
                'content'=>'absences/select',
                'employees' => $employees,
                'utils'=> new Utils(),
                'durations' => $durations,
            ]
        );

    }
    public function postAddAbsence(Request $request){
        //print_r($request->all()); exit();
        if($request->absence_date) {
            Absence::create($request->except(['start_date','end_date','_token']));
        } else if($request->start_date) {
            $s = strtotime($request->start_date);
            $e = strtotime($request->end_date);
            $efk = intval($request->employee_fk);
            DB::beginTransaction();
            for($i=$s; $i<$e; $i+=86400) {
                if(!in_array(date('w',$i),array(6,0))) { // not sat or sun
                    DB::table('absences')
                        ->insert([
                            'employee_fk' => $efk,
                            'absence_type' => $request->absence_type,
                            'absence_date' => date('Y-m-d',$i),
                            'notes' => $request->notes
                        ]);
                }
            }
            DB::commit();
        }
        Utils::chlog("Added employee absence",$request->except('_token'));
        $request->session()->put('alert', ['type'=>'success','msg'=>'The employee absence has been added to the system.']);
        return redirect()->to('/absences');
    }

    public function getEditAbsence($id){
        $res = Absence::find($id);
        if(!$res) {
            echo '<h1 class="error">No matching record!</h1>';
        } else {
            return view('global.modal',[
                    'content'=>'absences/edit',
                    'selected' => $res,
                    'durations' => Utils::enumSelect('absences','duration'),
                    'utils'=> new Utils(),
                ]
            );
        }

    }
    public function postEditAbsence($id,Request $request){
        //print_r($request->all()); exit();
        $dataObj = Absence::find($id);
        if(!$dataObj) {
            $request->session()->put('alert', ['type'=>'success','msg'=>'No matching record!']);
        } else {
            $prev = $dataObj->toArray();
            $dataObj->update($request->except('_token'));
            $dataObj->save();
            Utils::chlog("Edited employee absence {$id}",['BEFORE'=>$prev,'AFTER'=>$dataObj->toArray(),'DIFF'=>array_diff_assoc($dataObj->toArray(),$prev)]);
            $request->session()->put('alert', ['type'=>'success','msg'=>'The employee absence has been updated in the system.']);
        }
        return redirect()->to('/absences');
    }

    public function deleteAbsence($id, Request $request){
        $dataObj = Absence::find($id);
        if(!$dataObj) {
            $request->session()->put('alert', ['type'=>'success','msg'=>'No matching record!']);
        } else {
            $dataObj->deleted = true;
            $dataObj->save();
            Utils::chlog("Deleted absence {$id}",$dataObj->toArray());
            $request->session()->put('alert', ['type'=>'success','msg'=>'The selected absence record has been deleted from the system.']);
            return redirect()->to('/absences');
        }
    }

    public function exportAbsences(){
        $recordset = Absence::select('absences.*')
            ->selectRaw("CONCAT(e.firstname,' ',e.surname) AS employee_fk")
            ->join('employees AS e','e.uid','=','absences.employee_fk')
            ->where('absences.deleted','=',false)->where('e.deleted','=',false)
            ->orderBy('absences.absence_date')
            ->get();
        $fields = Schema::getColumnListing('absences');
        return response()
            ->view('global.export',[
                'fields' => $fields,
                'recordset'=>$recordset,
                'utils' => new Utils(),
            ], 200)
            ->header('Content-Type', 'text/csv');
    }

}
