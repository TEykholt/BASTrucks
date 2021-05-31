<?php

namespace App\Http\Controllers;

use App\TicketPersonModel;
use App\TicketLogModel;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

use Carbon\Carbon;

class TicketPersonController extends Controller
{
    function GetAssignedTicketPersons($ticketid)
    {
        $data=TicketPersonModel::select('id', 'ticket_id', 'person_id', 'status', 'created_at', 'updated_at')->where("ticket_id", $ticketid)->where('status', 'assigned');
    }

    function TicketPersonCreate(Request $request)
    {
        $request->except('_token');
        //to-do prevent unauthorized users from assigning people
        $ticketperson = new TicketPersonModel;
        $ticketperson->person_id = $request->person_id;
        $ticketperson->ticket_id = $request->ticket_id;
        $ticketperson->status = "assigned";
        $ticketperson->save();
    }

    function TicketPersonAssign(Request $request)
    {
        if (!auth()->user()->can("assign employee")) {
            abort(403);
        }

        $ticket_person = TicketPersonModel::select('status')->where('person_id', $request->person_id)->where('ticket_id', $request->ticket_id)->get();
        
        if(count($ticket_person)<=0)
        {
            return $this->TicketPersonAdd($request);
        }
        else if($ticket_person[0]->status=="unassigned")
        {
            return $this->TicketPersonUpdate($request);
        }

    }

    function TicketPersonAssignByUsername(Request $request)
    {
        if (!auth()->user()->can("assign employee")) {
            abort(403);
        }
        if ($request->username == null || $request->username == "")
        {
            abort(403);
        }

        $userController = new UserController();
        $User = $userController->getUserByUserName($request);
        
        if ($User) {
            $ticket_person = TicketPersonModel::select('status')->where('person_id', $User->id)->where('ticket_id', $request->ticket_id)->get();
        
            $request->person_id = $User->id;
            $request->username = $User->username;
            $request->ticket_id = $User->ticket_id;

            if(count($ticket_person)<= 0)
            {
                //abort(404);
                return $this->TicketPersonAdd($request);
            }
            else if($ticket_person[0]->status =="unassigned")
            {
                //abort(405);
                return $this->TicketPersonUpdate($request);
            }
        }
        else
        {
            abort(403);
        }
    }

    function TicketPersonAdd(Request $request)
    {
        //$ticketlog = new TicketLogModel;
        //$ticketlog->ticket_id = $request->ticket_id;
        //$ticketlog->message = "assigned ".$request->username." to ticket";
        //$ticketlog->created_by = auth()->user()->username;
        //$ticketlog->save();
        
        TicketPersonModel::where('person_id', $request->person_id)->where('ticket_id', $request->ticket_id)
        ->insert(['status' => "assigned", 'person_id' => $request->person_id, 'ticket_id' => $request->ticket_id, 'created_at']);
    }

    function TicketPersonUpdate(Request $request)
    {
        //$ticketlog = new TicketLogModel;
        //$ticketlog->ticket_id = $request->ticket_id;
        //$ticketlog->message = "reassigned ".$request->username." to ticket";
        //$ticketlog->created_by = auth()->user()->username;
        //$ticketlog->save();

        TicketPersonModel::where('person_id', $request->person_id)->where('ticket_id', $request->ticket_id)
        ->update(['status' => "reassigned"]);
    }

    function TicketPersonRemove(Request $request)
    {
        if (!auth()->user()->can("unassign employee")) {
            abort(403);
        }

        $userController = new UserController();
        $userRequest = new Request();
        $userRequest->id = $request->person_id;
        $User = $userController->getUser($userRequest);

        if ($User == null) {
            abort(403);
        }
        //$ticketlog = new TicketLogModel;
        //$ticketlog->ticket_id = $request->ticket_id;
        //$ticketlog->message = "removed ".$User->username." from ticket";
        //$ticketlog->created_by = auth()->user()->username;
        //$ticketlog->save();

        TicketPersonModel::where('person_id', $request->person_id)->where('ticket_id', $request->ticket_id)
        ->update(['status' => "unassigned", 'updated_at' => Carbon::now()]);
    }

    function GetTickets(Request $request)
    {
        TicketPersonModel::where('ticket_id', $request->ticket_id)->get();
    }

    function GetTicketPersonsByTicket(Request $request)
    {
        $ticket_persons=TicketPersonModel::where('ticket_id', $request->ticket_id)->where('status', "assigned")->orwhere('status', "reassigned")->get();
        $userController = new UserController();
        $userRequest = new Request();
        $returnPersons=array();

        for ($i=0; $i < Count($ticket_persons); $i++) 
        {
            $userRequest->id=$ticket_persons[$i]->person_id;
            $user = $userController->getUser($userRequest);

            if ($user) {
                array_push($returnPersons, $user);
            }
        }
        return $returnPersons;
    }
}
