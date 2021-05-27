<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class statusModel extends Model
{

    protected $table = 'status';
    protected $primaryKey = 'id';

    protected $fillable = [
        'status'
    ];
}
