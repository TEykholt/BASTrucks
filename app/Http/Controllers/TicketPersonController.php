<?php

namespace App\Http\Controllers;

use App\TicketPersonModel;
use Illuminate\Http\Request;

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
        $ticket_person=TicketPersonModel::select('status')->where('person_id', $request->person_id)->where('ticket_id', $request->ticket_id)->get();
        
        if(count($ticket_person)<=0)
        {
            return $this->TicketPersonAdd($request);
        }
        else if($ticket_person[0]->status=="unassigned")
        {
            return $this->TicketPersonUpdate($request);
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
        TicketPersonModel::where('person_id', $request->person_id)->where('ticket_id', $request->ticket_id)
        ->update(['status' => "unassigned"]);
    }

    function GetTickets(Request $request)
    {
        TicketPersonModel::where('ticket_id', $request->ticket_id)->get();
    }
}
