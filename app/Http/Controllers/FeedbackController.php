<?php

namespace App\Http\Controllers;

use App\TicketModel;
use Illuminate\Http\Request;
use App\FeedbackModel;

class FeedbackController extends Controller
{
    public function addFeedback(Request $request)
    {
        if (!auth()->user()->can("feedback input")) {
            abort(403);
        }
        $Feedback = new FeedbackModel;
        $Feedback->ticket_id = $request->ticket_id;
        $Feedback->FeedbackBox = $request->FeedbackBox;
        $Feedback->score = $request->Score;
        $Feedback->save();
        return redirect('https://i460123.hera.fhict.nl/');
    }
    public function load_ticket_feedback($id)
    {
        $FeedbackCheck = FeedbackModel::where("ticket_id", "=", $id)
        ->get();
        if(count($FeedbackCheck) > 0){
            abort(403);
        }

        $UserCheck = TicketModel::where("id", "=", $id)
            ->where("person_id", "=", auth()->user()->id)
            ->get();
        if(count($UserCheck) == 0){
            abort(403);
        }

        return view("Feedback")->with("ticket_id",$id);
    }
}
