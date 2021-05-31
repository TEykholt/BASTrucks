<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class departmentModel extends Model
{
    protected $table = 'department';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'description'
    ];
}
