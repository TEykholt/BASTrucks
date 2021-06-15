<?php

namespace App\Http\Controllers;

use App\TicketPersonModel;
use Illuminate\Http\Request;
use App\ticketTypes;
use Illuminate\Support\Facades\Redirect;

class TicketTypeController extends Controller
{
    

    //views
    function index()
    {
        if (!auth()->user()->can("admin panel")){
            abort(403);
        }
        $types = ticketTypes::all();
        return view("tickettypes/ticketTypes")->with('types', $types);
    }

    function create(){
        if (!auth()->user()->can("admin panel")){
            abort(403);
        }
        return view("tickettypes/ticketTypesCreate");
    }

    function edit($id){
        if (!auth()->user()->can("admin panel")){
            abort(403);
        }
        $types = ticketTypes::findOrFail($id);
        return view("tickettypes/ticketTypesEdit")->with('types', $types)->with('id', $id);
    }


    //functions
    function Add(Request $request)
    {
        if (!auth()->user()->can("admin panel")){
            abort(403);
        }
        ticketTypes::create($request->all());
        return Redirect::to('admin/ticketType');
    }

    function Update($id, Request $request)
    {
        if (!auth()->user()->can("admin panel")){
            abort(403);
        }
        $type = ticketTypes::findOrFail($id);
        $input = $request->all();
        $type->fill($input)->save();
        return Redirect::to('admin/ticketType');
    }

    function Delete($id)
    {
        if (!auth()->user()->can("admin panel")){
            abort(403);
        }
        $type = ticketTypes::findOrFail($id);
        $type->delete();
        return Redirect::to('admin/ticketType');
    }
}