<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable  = [
    	'company_id',
        'name'
    ];
}
