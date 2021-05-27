<?php

namespace App\Http\Controllers;

use App\attachmentModel;
use App\departmentModel;
use App\kpiModel;
use App\personSettingsModel;
use App\TicketModel;
use App\TicketLogModel;
use App\ticketTypes;
use App\statusModel;
use App\TicketPersonModel;
use App\Http\Controllers\TicketPersonController;

use Carbon\Carbon;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function loadDashboard(Request $repuest) {
        //ToDo: implement permissions

        auth()->user()->assignRole("view own tickets");
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
                case 'archive':
                    return $this->loadArchive();
                    break;

                default:
                    return $this->getTicketsFromUser();
                    break;
            }
        }
    }

    function loadArchive(){
        if (!auth()->user()->can("view archived tickets")) {
            abort(403);
        }
        return view('archive');
    }
    function checkArchive(Request $request){
        $id=$request->input;

        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'department.name as department_name')
            ->where('support_ticket.id', $id)
            ->get();

        return view('archiveReturn')->with('data', $data);
    }

    function getAllTickets(){
        if (auth()->user()->can("view all tickets")) {
            abort(403);
        }

        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'department.name as department_name')
            ->where('closed_at',  null)
            ->get();

        $person_settings = personSettingsModel::where("person_id", auth()->user()->id)
            ->get();

        $allKpi = [];
        foreach($person_settings as $wantedKpi){
            $kpi = kpiModel::where("id",$wantedKpi["preferd_kpi"])
                ->get();
            array_push($allKpi, $kpi[0]["kpi"]);
            //array_push($allKpi, $kpi["result"]);
        }
        
        $status = statusModel::get();
        $types = ticketTypes::get();
        $departments = departmentModel::get();
        return view('dashboard')->with('results' , $data)->with('types', $types)->with('statuses', $status)->with('departments', $departments)->with("allKpis", $allKpi);
    }

    function getTicketsFromUser() {
        if (!auth()->user()->can("view own tickets")) {
            abort(403);
        }
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->leftJoin("ticket_person","ticket_person.ticket_id","=","support_ticket.id")
            ->selectRaw('support_ticket.id, support_ticket.status, subject, type, message, person.name as person_name, department.name as department_name, ticket_person.person_id, (SELECT name FROM person WHERE person.id = ticket_person.person_id) as ticketWorker')
            ->where('support_ticket.person_id', auth()->user()->id)
            ->where('closed_at',  null)
            ->get();

        $workerData = TicketModel::join("ticket_person","ticket_person.ticket_id","=","support_ticket.id")
            ->join("person","person.id","=","ticket_person.person_id")
            ->select('name')
            ->get();
        $status = statusModel::get();
        $types = ticketTypes::get();
        $departments = departmentModel::get();

        return view('dashboard')->with('results' , $data)->with('types', $types)->with('statuses', $status)->with('departments', $departments);
    }

    function getAssignedTicketsFromUser() {
        if (!auth()->user()->can("view assigned tickets")) {
            abort(403);
        }

        $Ticket_Persons = TicketPersonModel::select('ticket_person.id', 'ticket_person.status', 'ticket_person.ticket_id')
            ->where('ticket_person.person_id', auth()->user()->id)
            ->get();

        
        $AssignedTickets = array();
        for ($i=0; $i < count($Ticket_Persons); $i++) {
            $Ticket_Person = $Ticket_Persons[$i];

            if (strtolower($Ticket_Person->status) != "unassigned") {
                array_push($AssignedTickets, $this->GetSingle($Ticket_Person->ticket_id, true)->ticket);
            }

        }

        $status = statusModel::get();
        $types = ticketTypes::get();
        $departments = departmentModel::get();

        return view('dashboard')->with('results' , $AssignedTickets)->with('types', $types)->with('statuses', $status)->with('departments', $departments);
    }

    function getTicketsFromUserDepartment() {
        if (auth()->user()->can("view own department tickets")) {
            abort(403);
        }
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'email', 'department.name as department_name')
            ->where('department.id', auth()->user()->department_id)
            ->where('closed_at',  null)
            ->get();

        $status = statusModel::get();
        $types = ticketTypes::get();
        $departments = departmentModel::get();

        return view('dashboard')->with('results' , $data)->with('types', $types)->with('statuses', $status);
    }

    function GetSingle($Ticket_id, $TicketOnly) {
        $data = TicketModel::join("person","person.id","=","support_ticket.person_id")
            ->join("department","department.id","=","support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.name as person_name', 'email', 'department.name as department_name')
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

   function getTicketViewerWithoutRequest($id){
       $TicketInformation = $this->GetSingle($id, false);

       if ($TicketInformation) {
           $status = statusModel::get();

           $types = ticketTypes::where('name', '!=', $TicketInformation->ticket['type'])->get();
           return view("ticketviewer")->with('result' , $TicketInformation->ticket)->with('logs' , $TicketInformation->logs)->with('attachment', $TicketInformation->attachments)->with('types', $types)->with('statuses', $status);;
       }
       else {
           $this->loadDashboard(new Request());
       }
   }

    function getTicketViewerArchive(Request $request) {
        return url('/ticketviewer', $request->id);
    }

   function getTicketViewer(Request $request) {
        //ToDo: Check if user has permissions to view this ticket
       $request->except('_token');
        $TicketInformation = $this->GetSingle($request->id, false);
        if ($TicketInformation) {
            $status = statusModel::get();

            $types = ticketTypes::where('name', '!=', $TicketInformation->ticket['type'])->get();

            $ticketPersonController = new TicketPersonController();
            $ticketPersonRequest = new Request();
            $ticketPersonRequest->ticket_id=$request->id;
            $assignedPersons=$ticketPersonController->GetTicketPersonsByTicket($ticketPersonRequest);
            return view("ticketviewer")->with("AssignedPersons", $assignedPersons)->with('result' , $TicketInformation->ticket)->with('logs' , $TicketInformation->logs)->with('attachment', $TicketInformation->attachments)->with('types', $types)->with('statuses', $status);;
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
        $mailcontroller->SendEmail("Regarding ticket ".$ticket->id, "Dear, ". $ticket->name, "Has succesfully been completed and is now set to closed. We would like for you to fill in this short form of how our services where regarding your ticket. http://127.0.0.1:8000/Feedback/id=".$ticket->id,  $ticket->email);

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

    function updateTicket(Request $request){
        $id=$request->id;
        $type=$request->type;

        TicketModel::where('id', $id)
            ->update(['type' => $type, "updated_at" => Carbon::now()]);
    }

    function editTicketAttachements(Request $request){
        $request->except('_token');
        $files = $request->file("Attachments");
        if($files != null){
            foreach($files as $file){
                $attachment = new attachmentModel;
                $attachment->name = $file->getClientOriginalName();
                $attachment->ticket_id = $request->id;
                $attachment->save();

                $destinationPath = 'uploaded_files';
                $file->move($destinationPath,$file->getClientOriginalName());
            }
        }
        $TicketInformation = $this->GetSingle($request->id, false);

        if ($TicketInformation) {
            $status = statusModel::get();

            $types = ticketTypes::where('name', '!=', $TicketInformation->ticket['type'])->get();
            return view("ticketviewer")->with('result' , $TicketInformation->ticket)->with('logs' , $TicketInformation->logs)->with('attachment', $TicketInformation->attachments)->with('types', $types)->with('statuses', $status);;
        }
    }

    function updateTicketMessage(Request $request){
        $id=$request->id;
        $message=$request->message;

        TicketModel::where('id', $id)
            ->update(['message' => $message, "updated_at" => Carbon::now()]);
    }
}
