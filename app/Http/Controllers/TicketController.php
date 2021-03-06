<?php

namespace App\Http\Controllers;

use App\attachmentModel;
use App\departmentModel;
use App\FeedbackModel;
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

    function loadDashboard(Request $repuest)
    {

        if ($repuest) {
            switch ($repuest->dashType) {
                case 'myTickets':
                    return $this->getTicketsFromUser();
                    break;

                case 'myDepartment':
                    return $this->getTicketsFromUserDepartment();
                    break;

                case 'myAssigned':
                    return $this->getAssignedTicketsFromUser();
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

    function loadArchive()
    {
        if (!auth()->user()->can("view archived tickets")) {
            abort(403);
        }
        return view('archive');
    }
    function checkArchive(Request $request)
    {
        $id = $request->input;

        $data = TicketModel::join("person", "person.id", "=", "support_ticket.person_id")
            ->join("department", "department.id", "=", "support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.username as person_name', 'department.name as department_name')
            ->where('support_ticket.id', $id)
            ->get();

        return view('archiveReturn')->with('data', $data);
    }

    function getAllTickets()
    {
        if (!auth()->user()->can("view all tickets")) {
            abort(403);
        }
        $this->getImapMailbox();

        $data = TicketModel::leftJoin("person", "person.id", "=", "support_ticket.person_id")
            ->join("department", "department.id", "=", "support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.username as person_name', 'department.name as department_name')
            ->where('closed_at',  null)
            ->get();

        $allKpi = $this->getUserKPI();
        $allKpiResults = $this->getKpi();

        $status = statusModel::get();
        $types = ticketTypes::get();
        $departments = departmentModel::get();
        return view('dashboard')->with('results', $data)->with('types', $types)->with('statuses', $status)->with('departments', $departments)->with("allKpis", $allKpi)->with("allKpiResults", $allKpiResults);
    }

    private function getImapMailbox()
    {
        try {
           
            $inbox = imap_open('{imap.gmail.com:993/imap/ssl}INBOX', 'bastrucksmail@gmail.com', 'B4STr5cks12#');

            $EmailNumbers = imap_search($inbox, 'UNSEEN');

            if ($EmailNumbers) {
                rsort($EmailNumbers);

                foreach ($EmailNumbers as $email_number) {
                    $overview = imap_fetch_overview($inbox, $email_number, 0)[0];
                    $message = imap_fetchbody($inbox, $email_number, 1.2);

                    $ticket = new TicketModel;
                    $ticket->person_id = 0;
                    $ticket->department_id = 0;
                    $ticket->type = "Jaspersoft";
                    $ticket->subject = $overview->subject;
                    $ticket->message = $message;
                    $ticket->status = "open";
                    $ticket->save();
                }
            }

            imap_close($inbox);

        } catch (\Throwable $th) {

        }
    }

    function getTicketsFromUser()
    {
        if (!auth()->user()->can("view own tickets")) {
            abort(403);
        }
        $data = TicketModel::leftJoin("person", "person.id", "=", "support_ticket.person_id")
            ->join("department", "department.id", "=", "support_ticket.department_id")
            ->leftJoin("ticket_person", "ticket_person.ticket_id", "=", "support_ticket.id")
            ->selectRaw('DISTINCT support_ticket.id, support_ticket.status, subject, type, message, person.username as person_name, department.name as department_name, ticket_person.person_id, (SELECT username FROM person WHERE person.id = ticket_person.person_id) as ticketWorker')
            ->where('support_ticket.person_id', auth()->user()->id)
            ->where('closed_at',  null)
            ->get();
            //            ->unique("support_ticket.id");

        $workerData = TicketModel::join("ticket_person", "ticket_person.ticket_id", "=", "support_ticket.id")
            ->join("person", "person.id", "=", "ticket_person.person_id")
            ->select('username')
            ->get();
        $status = statusModel::get();
        $types = ticketTypes::get();
        $departments = departmentModel::get();

        $allKpi = $this->getUserKPI();
        $allKpiResults = $this->getKpi();

        return view('dashboard')->with('results', $data)->with('types', $types)->with('statuses', $status)->with('departments', $departments)->with("allKpis", $allKpi)->with("allKpiResults", $allKpiResults);
    }

    function getAssignedTicketsFromUser()
    {
        if (!auth()->user()->can("view assigned tickets")) {
            abort(403);
        }

        $Ticket_Persons = TicketPersonModel::select('ticket_person.id', 'ticket_person.status', 'ticket_person.ticket_id')
            ->where('ticket_person.person_id', auth()->user()->id)
            ->get();


        $AssignedTickets = array();
        for ($i = 0; $i < count($Ticket_Persons); $i++) {
            $Ticket_Person = $Ticket_Persons[$i];

            if (strtolower($Ticket_Person->status) != "unassigned") {
                array_push($AssignedTickets, $this->GetSingle($Ticket_Person->ticket_id, true)->ticket);
            }
        }

        $status = statusModel::get();
        $types = ticketTypes::get();
        $departments = departmentModel::get();

        $allKpi = $this->getUserKPI();
        $allKpiResults = $this->getKpi();

        return view('dashboard')->with('results', $AssignedTickets)->with('types', $types)->with('statuses', $status)->with('departments', $departments)->with("allKpis", $allKpi)->with("allKpiResults", $allKpiResults);
    }

    function getTicketsFromUserDepartment()
    {
        if (!auth()->user()->can("view own department tickets")) {
            abort(403);
        }

        $data = TicketModel::join("person", "person.id", "=", "support_ticket.person_id")
            ->join("department", "department.id", "=", "support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.username as person_name', 'email', 'department.name as department_name')
            ->where('department.id', auth()->user()->department_id)
            ->where('closed_at',  null)
            ->get();

        $status = statusModel::get();
        $types = ticketTypes::get();
        $departments = departmentModel::get();

        $allKpi = $this->getUserKPI();
        $allKpiResults = $this->getKpi();


        return view('dashboard')->with('results', $data)->with('types', $types)->with('statuses', $status)->with("allKpis", $allKpi)->with("allKpiResults", $allKpiResults);
    }

    function GetSingle($Ticket_id, $TicketOnly)
    {
        $data = TicketModel::leftJoin("person", "person.id", "=", "support_ticket.person_id")
            ->join("department", "department.id", "=", "support_ticket.department_id")
            ->select('support_ticket.id', 'status', 'subject', 'type', 'message', 'person.username as person_name', 'email', 'department.name as department_name', 'tell')
            ->where('support_ticket.id', $Ticket_id)
            ->get();
        $attachment = null;
        $logs = null;

        if (!$TicketOnly) {
            $attachment = attachmentModel::where('ticket_id', $Ticket_id)
                ->get();

            $logs = TicketLogModel::select("message", "created_at", "created_by")
                ->where('ticket_id', $Ticket_id)
                ->get();
        }

        if (count($data) > 0) {
            return (object)[
                'ticket' => $data[0],
                'attachments' => $attachment,
                'logs' => $logs
            ];
        } else {
            return null;
        }
    }

    function getTicketViewerWithoutRequest($id)
    {
        if (!auth()->user()->can("view ticketviewer")) {
            abort(403);
        }

        $TicketInformation = $this->GetSingle($id, false);

        if ($TicketInformation) {
            $status = statusModel::get();

            $types = ticketTypes::where('name', '!=', $TicketInformation->ticket['type'])->get();
            return view("ticketviewer")->with('result', $TicketInformation->ticket)->with('logs', $TicketInformation->logs)->with('attachment', $TicketInformation->attachments)->with('types', $types)->with('statuses', $status);;
        } else {
            $this->loadDashboard(new Request());
        }
    }

    function getTicketViewerArchive(Request $request)
    {
        return url('/ticketviewer', $request->id);
    }

    function getTicketViewer(Request $request)
    {
        //ToDo: Check if user has permissions to view this ticket
        if (!auth()->user()->can("view ticketviewer")) {
            abort(403);
        }

        $request->except('_token');
        $TicketInformation = $this->GetSingle($request->id, false);
        if ($TicketInformation) {
            $status = statusModel::get();

            $types = ticketTypes::where('name', '!=', $TicketInformation->ticket['type'])->get();


            $ticketPersonController = new TicketPersonController();
            $ticketPersonRequest = new Request();
            $ticketPersonRequest->ticket_id = $request->id;
            $assignedPersons = $ticketPersonController->GetTicketPersonsByTicket($ticketPersonRequest);
            return view("ticketviewer")->with("AssignedPersons", $assignedPersons)->with('result', $TicketInformation->ticket)->with('logs', $TicketInformation->logs)->with('attachment', $TicketInformation->attachments)->with('types', $types)->with('statuses', $status);;
        } else {
            $this->loadDashboard(new Request());
        }
    }

    function addTicket(Request $request)
    {
        if (!auth()->user()->can("ticket input")) {
            abort(403);
        }

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

        if ($files != null) {
            foreach ($files as $file) {
                $attachment = new attachmentModel;
                $attachment->name = $file->getClientOriginalName();
                $attachment->ticket_id = $ticket->id;
                $attachment->save();

                $destinationPath = 'uploaded_files';
                $file->move($destinationPath, $file->getClientOriginalName());
            }
        }

        $ticketlog = new TicketLogModel;
        $ticketlog->ticket_id = $ticket->id;
        $ticketlog->message = "ticket was created by " . auth()->user()->username;
        $ticketlog->created_by = auth()->user()->username;
        $ticketlog->save();

        try {
            $mailcontroller = new MailController();
            $mailcontroller->SendEmail($request->subject, "Dear, " . auth()->user()->username, "Your ticket has been succesfully recieved and we will do our best to complete your ticket as fast as possible",  auth()->user()->email);
        } catch (\Throwable $th) {

        }
        return $this->loadDashboard(new Request());
    }

    function closeTicket($id)
    {
        TicketModel::where('id', $id)
            ->update(['status' => "closed", "closed_at" => Carbon::now()]);

        $ticket = TicketModel::join("person", "person.id", "=", "support_ticket.person_id")->select("username", "support_ticket.id", "person.email")->where('support_ticket.id', $id)->first();
        $ticketlog = new TicketLogModel;
        $ticketlog->ticket_id = $id;
        $ticketlog->message = "ticket was closed by " . auth()->user()->username;
        $ticketlog->created_by = auth()->user()->username;
        $ticketlog->save();

        try {
            $mailcontroller = new MailController();
            $mailcontroller->SendEmail("Regarding ticket " . $ticket->id, "Dear, " . $ticket->name, "Has succesfully been completed and is now set to closed. We would like for you to fill in this short form of how our services where regarding your ticket. http://127.0.0.1:8000/Feedback/" . $ticket->id,  $ticket->email);
        } catch (\Throwable $th) {
            
        }
        return $this->loadDashboard(new Request());
    }

    function openTicket($id)
    {
        TicketModel::where('id', $id)
            ->update(['status' => "open", "updated_at" => Carbon::now(), "closed_at" => null]);

        $ticket = TicketModel::join("person", "person.id", "=", "support_ticket.person_id")->where('support_ticket.id', $id)->first();

        $ticketlog = new TicketLogModel;
        $ticketlog->ticket_id = $id;
        $ticketlog->message = "ticket was reopend by " . auth()->user()->username;
        $ticketlog->created_by = auth()->user()->username;
        $ticketlog->save();

        return $this->loadDashboard(new Request());
    }

    function loadTicketInput()
    {
        if (!auth()->user()->can("ticket input")) {
            abort(403);
        }

        $types = ticketTypes::get();
        $department = departmentModel::get();
        return view("ticketInput")->with('types', $types)->with('departments', $department);
    }

    function updateTicket(Request $request)
    {
        if (!auth()->user()->can("edit ticket")) {
            abort(403);
        }

        $id = $request->id;
        $type = $request->type;

        $ticketlog = new TicketLogModel;
        $ticketlog->ticket_id = $id;
        $ticketlog->message = auth()->user()->username . " has updated the ticket";
        $ticketlog->created_by = auth()->user()->username;
        $ticketlog->save();

        TicketModel::where('id', $id)
            ->update(['type' => $type, "updated_at" => Carbon::now()]);
    }

    function editTicketAttachements(Request $request)
    {
        if (!auth()->user()->can("edit ticket")) {
            abort(403);
        }

        $request->except('_token');
        $files = $request->file("Attachments");
        if ($files != null) {
            foreach ($files as $file) {
                $attachment = new attachmentModel;
                $attachment->name = $file->getClientOriginalName();
                $attachment->ticket_id = $request->id;
                $attachment->save();

                $destinationPath = 'uploaded_files';
                $file->move($destinationPath, $file->getClientOriginalName());
            }
        }
        $TicketInformation = $this->GetSingle($request->id, false);

        if ($TicketInformation) {
            $status = statusModel::get();

            $types = ticketTypes::where('name', '!=', $TicketInformation->ticket['type'])->get();
            return view("ticketviewer")->with('result', $TicketInformation->ticket)->with('logs', $TicketInformation->logs)->with('attachment', $TicketInformation->attachments)->with('types', $types)->with('statuses', $status);;
        }
    }

    function updateTicketMessage(Request $request)
    {
        if (!auth()->user()->can("edit ticket")) {
            abort(403);
        }

        $id = $request->id;
        $message = $request->message;

        $ticketlog = new TicketLogModel;
        $ticketlog->ticket_id = $id;
        $ticketlog->message = auth()->user()->username . " has updated the ticket message";
        $ticketlog->created_by = auth()->user()->username;
        $ticketlog->save();

        TicketModel::where('id', $id)
            ->update(['message' => $message, "updated_at" => Carbon::now()]);
    }

    function getUserKPI()
    {
        $allKpi = [];
        $person_settings = personSettingsModel::where("person_id", auth()->user()->id)
            ->get();
        foreach ($person_settings as $wantedKpi) {
            $kpi = kpiModel::where("id", $wantedKpi["preferd_kpi"])
                ->get();
            array_push($allKpi, $kpi[0]["kpi"]);
            //array_push($allKpi, $kpi["result"]);
        }

        return $allKpi;
    }
    function getKpi()
    {
        $allKpi = $this->getUserKPI();
        $allKpiResults = ["AVR", "AVTR", "TSF", "AUFS", "CS", "SVI"];
        if (auth()->user()->can("view kpi")) {


            foreach ($allKpi as $kpi) {
                switch ($kpi) {
                    case "Average response time":
                        $avr = TicketModel::join("ticket_person", "ticket_person.ticket_id", "=", "support_ticket.id")
                            ->selectRaw("AVG(ROUND(time_to_sec((TIMEDIFF(ticket_person.created_at, support_ticket.created_at))) / 3600)) AS difference")
                            ->get();
                        $allKpiResults["AVR"] =  number_format(round($avr[0]["difference"], 2),2);
                        //dd($allKpiResults["AVR"]);
                        break;
                    case "Time service factor":
                        $tsf = TicketModel::leftJoin("ticket_person", "ticket_person.ticket_id", "=", "support_ticket.id")
                            ->selectRaw("AVG(ROUND(time_to_sec((TIMEDIFF(support_ticket.closed_at, support_ticket.created_at))) / 3600)) + AVG(ROUND(time_to_sec((TIMEDIFF(ticket_person.created_at, support_ticket.created_at))) / 3600)) AS count")
                            ->get();
                        $allKpiResults["TSF"] =  number_format(round($tsf[0]["count"], 2), 2);
                        break;
                    case "Average total resolution time":
                        $avrt = TicketModel::selectRaw("AVG(ROUND(time_to_sec((TIMEDIFF(closed_at, created_at))) / 3600)) AS difference")
                            ->get();

                        $allKpiResults["AVTR"] =  number_format(round($avrt[0]["difference"], 2), 2);
                        break;
                    case "Average user feedbackscore":
                        $avuf = FeedbackModel::selectRaw("avg(score) as avg_score")
                            ->get();
                        $allKpiResults["AUFS"] =  round($avuf[0]['avg_score'], 2);
                        break;
                    case "Customer satisfaction":
                        $cs = FeedbackModel::selectRaw("(SELECT count(*) FROM ticket_feedback where FeedbackBox is null) / count(*) * 100  as percentage")
                            ->get();
                        $allKpiResults["CS"] = round($cs[0]['percentage'], 2);
                        break;
                    case "Opened tickets":
                        $SVI = TicketModel::selectRaw("count(*) as count")
                            ->whereRaw("closed_at is null")
                            ->get();
                        $allKpiResults["SVI"] = $SVI[0]['count'];
                        break;
                }
            }
            return $allKpiResults;
        }
    }
}
