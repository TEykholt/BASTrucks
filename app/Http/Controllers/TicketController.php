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
        $ticket->department_id = $request->department_id;
        $ticket->date_created = date("Y-m-d-h"); //Date time

        $ticket->subject = $request->subject;
        $ticket->message = $request->message;
        $ticket->status = "open";
        $ticket->save();

        return view("ticketInvoer");
    }
}
