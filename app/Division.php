<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $connection = "mysql";
    
    protected $fillable  = [
    	'company_id',
        'name'
    ];
}
