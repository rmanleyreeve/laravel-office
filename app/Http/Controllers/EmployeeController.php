<?php

namespace App\Http\Controllers;

use App\Domain\AppFuncs as Funcs;
use App\Domain\AppUtils as Utils;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Intervention\Image\ImageManagerStatic as Image;


class EmployeeController extends Controller
{
    public function getEmployees()
    {
        $employees = Employee::notDeleted()->bySurname()->get();
        return view('global.master', [
                'content' => 'employees/list',
                'recordset' => $employees,
            ]
        );
    }

    public function getAddEmployee()
    {
        return view('global.master', [
                'content' => 'employees/add-edit',
                'action' => 'Add New',
                'selected' => new Employee(),
                'utils' => new Utils(),
            ]
        );
    }

    public function postAddEmployee(Request $request)
    {
        //print_r($request->all()); exit;
        $dataObj = new Employee();
        $this->updateEmployeeModel($dataObj, $request);
        $request->session()->flash('alert', ['type' => 'success', 'msg' => 'The new employee was added successfully.']);
        return redirect()->to('/employees');
    }

    public function viewEmployee($id)
    {
        $employee = Employee::notDeleted()->find($id);
        if (!$employee) {
            return response('<h1 class="error">No matching employee!</h1>');
        } else {
            return view('global.modal', [
                    'content' => 'employees/view',
                    'selected' => $employee,
                    'utils' => new Utils(),
                ]
            );
        }
    }

    public function getEditEmployee(Request $request, $id)
    {
        $employee = Employee::notDeleted()->find($id);
        if (!$employee) {
            $request->session()->flash('alert', ['type' => 'error', 'msg' => 'No matching employee!']);
            return redirect()->to('/employees');
        } else {
            return view('global.master', [
                    'content' => 'employees/add-edit',
                    'action' => 'Edit',
                    'selected' => $employee,
                    'utils' => new Utils(),
                ]
            );
        }
    }

    public function postEditEmployee(Request $request, $id)
    {
        //print_r($request->all()); exit;
        $dataObj = Employee::notDeleted()->find($id);
        if (!$dataObj) {
            $request->session()->flash('alert', ['type' => 'error', 'msg' => 'No matching employee!']);
            return redirect()->to('/employees');
        } else {
            $prev = $dataObj->toArray();
            unset($prev['image']);
            $this->updateEmployeeModel($dataObj, $request);
            $after = $dataObj->toArray();
            unset($after['image']);
            Utils::chlog("Edited employee {$id}", array('BEFORE' => $prev, 'AFTER' => $after, 'DIFF' => array_diff_assoc($after, $prev)));
            $request->session()->flash('alert', ['type' => 'success', 'msg' => 'The selected employee has been updated.']);
            return redirect()->to('/employees');
        }
    }

    public function deleteEmployee(Request $request, $id)
    {
        $dataObj = Employee::notDeleted()->find($id);
        if (!$dataObj) {
            $request->session()->flash('alert', ['type' => 'error', 'msg' => 'No matching employee!']);
        } else {
            $dataObj->deleted = true;
            $dataObj->save();
            $request->session()->flash('alert', ['type' => 'success', 'msg' => 'The selected employee has been deleted from the system.']);
        }
        return redirect()->to('/employees');
    }

    public function exportEmployees()
    {
        $recordset = Employee::notDeleted()->bySurname()->get();
        $fields = Schema::getColumnListing('employees');
        unset($fields[array_search('image', $fields, true)]);
        return response()
            ->view('global.export', [
                'fields' => $fields,
                'recordset' => $recordset,
                'utils' => new Utils(),
            ], 200)
            ->header('Content-Type', 'text/csv');
    }

    public function getEmployeeImage($id)
    {
        $auth = (Auth::check() && Funcs::_up('EMPLOYEE'));
        $dataObj = Employee::notDeleted()->find($id);
        if (!$auth || !$dataObj || !$dataObj->image) {
            $img = Image::make(public_path('/assets/images/silhouette.jpg'));
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            return $img->response();
        } else {
            header("Content-type: image/jpeg");
            echo $dataObj->image;
        }
    }

    protected function updateEmployeeModel(Employee $dataObj, Request $request): void
    {
        $vars = Arr::except($request->all(), ['_token']);
        $dataObj->update($vars);
        if ($request->hasfile('image')) {
            $request->validate([
                'image.*' => 'mimes:png,gif,jpg,jpeg'
            ]);
            $file = $request->file('image');
            //var_dump($file); exit;
            $dataObj->image = file_get_contents($file->getPathname());
        }
        $dataObj->save();
    }

}

