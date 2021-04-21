<?php

namespace App\Http\Controllers;

use App\TicketModel;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function getAllTickets(){
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'username', 'department.name')
            ->where('department.name', 'ICT')
            ->get();
            
        return view('dashboard')->with('results' , $data);
    }

    function getAllTicketsFromUser() {
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
        ->join("department","department.id","=","support_ticket.department_id")
        ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'username', 'department.name')
        ->where('username', auth()->user()->username)
        ->where('department.name', 'ICT')
        ->get();
        
    return view('dashboard')->with('results' , $data);
    }


    function GetSingle(Request $repuest) {
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'username', 'department.name')
            ->where('support_ticket.id', $repuest->id)
            ->get();

        return view("ticketviewer")->with('results' , $data);
    }

    function addTicket(Request $request){
        $request->except('_token');

        $ticket = new TicketModel;
        $ticket->person_id = auth()->user()->id;
        $ticket->department_id = $request->department_id;
        $ticket->type = $request->ticket_type;
        $ticket->subject = $request->subject;
        $ticket->message = $request->message;
        $ticket->status = "open";
        $ticket->save();

        return view("ticketInput");
    }
}
