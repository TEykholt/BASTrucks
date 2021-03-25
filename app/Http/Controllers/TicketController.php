<?php

namespace App\Http\Controllers;

use App\TicketModel;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    function getAllTickets(){
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")->get();

        return view('dashboard')->with('results' , $data);
    }
}
