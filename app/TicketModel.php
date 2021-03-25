<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketModel extends Model
{
    protected $table = 'support_ticket';
    protected $primaryKey = 'id';
}
