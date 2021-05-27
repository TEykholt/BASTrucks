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
        $types = ticketTypes::all();
        return view("tickettypes/ticketTypes")->with('types', $types);
    }

    function create(){
        return view("tickettypes/ticketTypesCreate");
    }

    function edit($id){
        $types = ticketTypes::findOrFail($id);
        return view("tickettypes/ticketTypesEdit")->with('types', $types)->with('id', $id);
    }


    //functions
    function Add(Request $request)
    {
        ticketTypes::create($request->all());
        return Redirect::to('/ticketType');
    }

    function Update($id, Request $request)
    {
        $type = ticketTypes::findOrFail($id);
        $input = $request->all();
        $type->fill($input)->save();
        return Redirect::to('/ticketType');
    }

    function Delete($id)
    {
        $type = ticketTypes::findOrFail($id);
        $type->delete();
        return Redirect::to('/ticketType');
    }
}