<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class attachmentModel extends Model
{
    protected $table = 'ticket_attachment';
    protected $primaryKey = 'id';
}
