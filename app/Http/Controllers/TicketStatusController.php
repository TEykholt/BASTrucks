<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\statusModel;
use Illuminate\Support\Facades\Redirect;

class TicketStatusController extends Controller
{
    //views
    function index()
    {
        if (!auth()->user()->can("admin panel")){
            abort(403);
        }
        $status = statusModel::all();
        return view("ticketstatus/ticketStatus")->with('status', $status);
    }

    function create(){
        if (!auth()->user()->can("admin panel")){
            abort(403);
        }
        return view("ticketstatus/ticketStatusCreate");
    }

    function edit($id){
        if (!auth()->user()->can("admin panel")){
            abort(403);
        }
        $status = statusModel::findOrFail($id);
        return view("ticketstatus/ticketStatusEdit")->with('status', $status)->with('id', $id);
    }


    //functions
    function Add(Request $request)
    {
        if (!auth()->user()->can("admin panel")){
            abort(403);
        }
        statusModel::create($request->all());
        return Redirect::to('admin/ticketStatus');
    }

    function Update($id, Request $request)
    {
        if (!auth()->user()->can("admin panel")){
            abort(403);
        }
        $status = statusModel::findOrFail($id);
        $input = $request->all();
        $status->fill($input)->save();
        return Redirect::to('admin/ticketStatus');
    }

    function Delete($id)
    {
        if (!auth()->user()->can("admin panel")){
            abort(403);
        }
        $status = statusModel::findOrFail($id);
        $status->delete();
        return Redirect::to('admin/ticketStatus');
    }
}
