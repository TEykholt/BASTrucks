<?php

namespace App\Http\Controllers;

use App\TicketModel;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    function getAllTickets(){
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")->join("department","department.id","=","support_ticket.department_id")->get();

        return view('dashboard')->with('results' , $data);
    }

    function addTicket(Request $request){
        $ticket = new TicketModel;
        $ticket->person_id = $request->person_id;
        $ticket->subject = $request->subject;
    }
}
