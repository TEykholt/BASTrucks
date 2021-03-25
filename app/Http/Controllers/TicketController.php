<?php

namespace App\Http\Controllers;

use App\TicketModel;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    function getAllTickets(){
        $data = TicketModel::all();

        return view('dashboard')->with('results' , $data);
    }
}
