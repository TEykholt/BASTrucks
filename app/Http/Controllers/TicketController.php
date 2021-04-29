<?php

namespace App\Http\Controllers;

use App\attachmentModel;
use App\TicketModel;
use App\TicketLogModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
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

    function getAllTicketsFromUser() {
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'department.name as department_name')
            ->where('support_ticket.person_id', auth()->user()->id)
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

    function GetSingle(Request $repuest) {
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'department.name as department_name')
            ->where('support_ticket.id', $repuest->id)
            ->get();

        $attachment = attachmentModel::where('ticket_id', $repuest->id)
            ->get();

        $logs = TicketLogModel::select("message","created_at","created_by")
            ->where('ticket_id', $repuest->id)
            ->get();

        return view("ticketviewer")->with('results' , $data)->with('logs' , $logs)->with('attachment', $attachment);
    }

    function addTicket(Request $request){
        $request->except('_token');
        $files = $request->file("Attachments");

        $ticket = new TicketModel;
        $ticket->person_id = auth()->user()->id;
        $ticket->department_id = $request->department_id;
        $ticket->type = $request->ticket_type;
        $ticket->subject = $request->subject;
        $ticket->message = $request->message;
        $ticket->status = "open";
        $ticket->save();

        if($files != null){
            foreach($files as $file){
                $attachment = new attachmentModel;
                $attachment->name = $file->getClientOriginalName();
                $attachment->ticket_id = $ticket->id;
                $attachment->save();

                $destinationPath = 'uploaded_files';
                $file->move($destinationPath,$file->getClientOriginalName());
            }
        }

        $ticketlog = new TicketLogModel;
        $ticketlog->ticket_id = $ticket->id;
        $ticketlog->message = "ticket was created by " . auth()->user()->name;
        $ticketlog->created_by = auth()->user()->name;
        $ticketlog->save();

        $mailcontroller = new MailController();
        $mailcontroller->SendEmail($request->subject, "Dear, ". auth()->user()->name, "Your ticket has been succesfully recieved and we will do our best to complete your ticket as fast as possible",  auth()->user()->email);

        return $this->getAllTicketsFromUser();
    }

    function closeTicket($id){
        TicketModel::where('id', $id)
            ->update(['status' => "closed", "closed_at" => Carbon::now()]);

        $ticket = TicketModel::join("person","person.id","=","support_ticket.person_id")->where('support_ticket.id', $id)->first();

        $mailcontroller = new MailController();
        $mailcontroller->SendEmail("Regarding ticket ".$ticket->id, "Dear, ". $ticket->name, "Has succesfully been completed and is now set to closed. We would like for you to fill in this short form of how our services where regarding your ticket.",  $ticket->email);

        return $this->getAllTicketsFromUser();
    }

    function loadTicketInput(){
        return view("ticketInput");
    }
}
