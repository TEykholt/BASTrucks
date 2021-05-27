<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ticketTypes extends Model
{
    protected $table = 'ticket_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name'
    ];
}
