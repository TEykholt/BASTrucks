<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailController extends Controller
{
    public function SendEmail($subject, $title, $message, $target){
        $mailinfo = [
            'subject' => $subject,
            'title' => $title,
            'body' => $message
        ];

        \Mail::to($target)->send(new \App\Mail\TicketMailer($mailinfo));
    }
}
