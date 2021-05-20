<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedbackModel extends Model
{
    protected $table = 'ticket_feedback';
    protected $primaryKey = 'id';
}
