<?php

namespace App\Http\Controllers;

use App\TicketPersonModel;
use Illuminate\Http\Request;

class TicketPersonController extends Controller
{
    function TicketPersonAdd()
    {
        $data=TicketPersonModel::select('id', 'ticket_id', 'person_id', 'status', 'created_at', 'updated_at');
    }

    function TicketPersonRemove()
    {
        //a
    }
}
