<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignHead extends Model
{
    protected $fillable = [
    	'employee_id',
    	'employee_head_id',
    	'head_id'
    ];
}
