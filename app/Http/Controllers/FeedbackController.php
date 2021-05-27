<?php

namespace App\Http\Controllers;

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
        return redirect('http://127.0.0.1:8000/');
    }
    public function load_ticket_feedback($id)
    {
        return view("Feedback")->with("ticket_id",$id);
    }
}
