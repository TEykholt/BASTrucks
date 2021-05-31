<?php

namespace App\Http\Controllers;

use App\TicketPersonModel;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

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
        $ticketperson->status = "open";
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
            if(count($ticket_person)<= 0)
            {
                return $this->TicketPersonAdd($request);
            }
            else if($ticket_person[0]->status =="unassigned")
            {
                return $this->TicketPersonUpdate($request);
            }
        }
        else{
            abort(403);
        }
    }

    function TicketPersonAdd(Request $request)
    {
        TicketPersonModel::where('person_id', $request->person_id)->where('ticket_id', $request->ticket_id)
        ->update(['status' => "assigned"]);
    }

    function TicketPersonUpdate(Request $request)
    {
        TicketPersonModel::where('person_id', $request->person_id)->where('ticket_id', $request->ticket_id)
        ->update(['status' => "reassigned"]);
    }

    function TicketPersonRemove(Request $request)
    {
        if (!auth()->user()->can("unassign employee")) {
            abort(403);
        }

        TicketPersonModel::where('person_id', $request->person_id)->where('ticket_id', $request->ticket_id)
        ->update(['status' => "unassigned"]);
    }

    function GetTickets(Request $request)
    {
        TicketPersonModel::where('ticket_id', $request->ticket_id)->get();
    }

    function GetTicketPersonsByTicket(Request $request)
    {
        $ticket_persons=TicketPersonModel::where('ticket_id', $request->ticket_id)->where('status', "assigned")->get();
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
