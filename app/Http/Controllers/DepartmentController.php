<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\departmentModel;
use Illuminate\Support\Facades\Redirect;

class DepartmentController extends Controller
{
    //views
    function index()
    {
        $departments = departmentModel::all();
        return view("department/department")->with('departments', $departments);
    }

    function create(){
        return view("department/departmentCreate");
    }

    function edit($id){
        $departments = departmentModel::findOrFail($id);
        return view("department/departmentEdit")->with('departments', $departments)->with('id', $id);
    }


    //functions
    function Add(Request $request)
    {
        departmentModel::create($request->all());
        return Redirect::to('admin/department');
    }

    function Update($id, Request $request)
    {
        $type = departmentModel::findOrFail($id);
        $input = $request->all();
        $type->fill($input)->save();
        return Redirect::to('admin/department');
    }

    function Delete($id)
    {
        $type = departmentModel::findOrFail($id);
        $type->delete();
        return Redirect::to('admin/department');
    }
}
