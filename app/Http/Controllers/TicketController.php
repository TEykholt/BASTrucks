<?php

namespace App\Http\Controllers;

use App\TicketModel;
use App\TicketLogModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function getAllTickets(){
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'department.name as department_name')
            ->get();

        return view('dashboard')->with('results' , $data);
    }

    function getAllTicketsWithStatus($Status){
        if (!$Status) {
            return null;
        }

        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'department.name as department_name')
            ->where('support_ticket.status', $Status)
            ->get();

        return view('dashboard')->with('results' , $data);
    }

    function getAllTicketsFromUser() {
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'department.name as department_name')
            ->where('support_ticket.person_id', auth()->user()->id)
            ->get();

        return view('dashboard')->with('results' , $data);
    }

    function getAllTicketsFromUserWithStatus($Status) {
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'department.name as department_name')
            ->where('support_ticket.person_id', auth()->user()->id)
            ->where('support_ticket.status', $Status)
            ->get();

        return view('dashboard')->with('results' , $data);
    }

    function getAllTicketsFromUserDepartment() {
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'department.name as department_name')
            ->where('department.name', 'ICT')
            ->get();

        return view('dashboard')->with('results' , $data);
    }

    function getAllTicketsFromUserDepartmentWithStatus($Status) {
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'department.name as department_name')
            ->where('department.name', 'ICT')
            ->where('support_ticket.status', $Status)
            ->get();

        return view('dashboard')->with('results' , $data);
    }


    function GetSingle(Request $repuest) {
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'department.name as department_name')
            ->where('support_ticket.id', $repuest->id)
            ->get();

        $logs = TicketLogModel::select("message","date_created","created_by")
            ->where('ticket_id', $repuest->id)
            ->get();

        return view("ticketviewer")->with('results' , $data)->with('logs' , $logs);
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

        $mailcontroller = new MailController();
        //$mailcontroller->SendEmail($request->subject, "About your ticket, ", $request->message, "460123@student.fontys.nl");

        return $this->getAllTicketsFromUser();
    }
}
