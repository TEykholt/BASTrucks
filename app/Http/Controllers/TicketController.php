<?php

namespace App\Http\Controllers;

use App\attachmentModel;
use App\departmentModel;
use App\TicketModel;
use App\TicketLogModel;
use App\ticketTypes;
use App\statusModel;
use App\TicketPersonModel;

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

    function loadDashboard(Request $repuest) {
        //ToDo: implement permissions
        if ( $repuest) {
            switch ($repuest->dashType) {
                case 'myTickets':
                    return $this->getTicketsFromUser();
                    break;

                case 'myAssigned':
                    return $this->getAssignedTicketsFromUser();
                    break;

                case 'myDepartment':
                    return $this->getTicketsFromUserDepartment();
                    break;

                case 'allTickets':
                    return $this->getAllTickets();
                    break;

                default:
                    return $this->getTicketsFromUser();
                    break;
            }
        }
        return $this->getTicketsFromUser();
    }

    function getAllTickets(){
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'department.name as department_name')
            ->get();

        $status = statusModel::get();
        $types = ticketTypes::get();
        $departments = departmentModel::get();

        return view('dashboard')->with('results' , $data)->with('types', $types)->with('statuses', $status)->with('departments', $departments);
    }

    function getTicketsFromUser() {
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'department.name as department_name')
            ->where('support_ticket.person_id', auth()->user()->id)
            ->get();

        $status = statusModel::get();
        $types = ticketTypes::get();
        $departments = departmentModel::get();

        return view('dashboard')->with('results' , $data)->with('types', $types)->with('statuses', $status)->with('departments', $departments);
    }

    function getAssignedTicketsFromUser() {

        $Ticket_Persons = TicketPersonModel::select('ticket_person.id', 'ticket_person.status', 'ticket_person.ticket_id')
            ->where('ticket_person.person_id', auth()->user()->id)
            ->get();

        $AssignedTickets = array();
        for ($i=0; $i < count($Ticket_Persons); $i++) {
            $Ticket_Person = $Ticket_Persons[$i];

            if (strtolower($Ticket_Person->status) == "assigned") {
                array_push($AssignedTickets, $this->GetSingle($Ticket_Person->ticket_id, true)->ticket);
            }

        }

        $status = statusModel::get();
        $types = ticketTypes::get();
        $departments = departmentModel::get();

        return view('dashboard')->with('results' , $AssignedTickets)->with('types', $types)->with('statuses', $status)->with('departments', $departments);
    }

    function getTicketsFromUserDepartment() {
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'department.name as department_name')
            ->where('department.id', auth()->user()->department_id)
            ->get();

        $status = statusModel::get();
        $types = ticketTypes::get();
        $departments = departmentModel::get();

        return view('dashboard')->with('results' , $data)->with('types', $types)->with('statuses', $status);
    }

    function GetSingle($Ticket_id, $TicketOnly) {
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'department.name as department_name')
            ->where('support_ticket.id', $Ticket_id)
            ->get();

        $attachment = null; $logs = null;

        if (!$TicketOnly) {
            $attachment = attachmentModel::where('ticket_id', $Ticket_id)
                ->get();

            $logs = TicketLogModel::select("message","created_at","created_by")
                ->where('ticket_id', $Ticket_id)
                ->get();
        }

        if (count($data) > 0) {
            return (object)[
                'ticket' => $data[0],
                'attachments' => $attachment,
                'logs' => $logs
            ];
        }
        else {
            return null;
        }
   }

   function getTicketViewer(Request $request) {
        //ToDo: Check if user has permissions to view this ticket

        $TicketInformation = $this->GetSingle($request->id, false);

        if ($TicketInformation) {
            $status = statusModel::get();

            $types = ticketTypes::where('name', '!=', $TicketInformation->ticket['type'])->get();

            return view("ticketviewer")->with('result' , $TicketInformation->ticket)->with('logs' , $TicketInformation->logs)->with('attachment', $TicketInformation->logs)->with('types', $types)->with('statuses', $status);;
        }
        else {
            $this->loadDashboard(new Request());
        }

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

        return $this->loadDashboard(new Request());
    }

    function closeTicket($id){
        TicketModel::where('id', $id)
            ->update(['status' => "closed", "closed_at" => Carbon::now()]);

        $ticket = TicketModel::join("person","person.id","=","support_ticket.person_id")->where('support_ticket.id', $id)->first();

        $ticketlog = new TicketLogModel;
        $ticketlog->ticket_id = $id;
        $ticketlog->message = "ticket was closed by " . auth()->user()->name;
        $ticketlog->created_by = auth()->user()->name;
        $ticketlog->save();

        $mailcontroller = new MailController();
        $mailcontroller->SendEmail("Regarding ticket ".$ticket->id, "Dear, ". $ticket->name, "Has succesfully been completed and is now set to closed. We would like for you to fill in this short form of how our services where regarding your ticket.",  $ticket->email);

        return $this->loadDashboard(new Request());
    }

    function openTicket($id){
        TicketModel::where('id', $id)
            ->update(['status' => "open", "updated_at" => Carbon::now()]);

        $ticket = TicketModel::join("person","person.id","=","support_ticket.person_id")->where('support_ticket.id', $id)->first();

        $ticketlog = new TicketLogModel;
        $ticketlog->ticket_id = $id;
        $ticketlog->message = "ticket was reopend by " . auth()->user()->name;
        $ticketlog->created_by = auth()->user()->name;
        $ticketlog->save();

        return $this->loadDashboard(new Request());
    }

    function loadTicketInput(){
        $types = ticketTypes::get();
        $department = departmentModel::get();
        return view("ticketInput")->with('types', $types)->with('departments', $department);
    }
}
