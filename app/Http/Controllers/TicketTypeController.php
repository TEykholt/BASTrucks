<?php

namespace App\Http\Controllers;

use App\TicketPersonModel;
use Illuminate\Http\Request;
use App\ticketTypes;


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
        return view("tickettypes/ticketTypesEdit")->with('types', $types);
    }


    //functions
    function AddType(Request $request)
    {
        $input = $request->all();
        ticketTypes::create($input);
        return $this->GetAllTypes();
    }

    function UpdateType($id, Request $request)
    {
        $type = ticketTypes::findOrFail($id);
        $input = $request->all();
        $type->fill($input)->save();
        return $this->GetAllTypes();
    }

    function DeleteType($id)
    {
        $type = ticketTypes::findOrFail($id);
        $type->delete();
        return $this->GetAllTypes();
    }
}