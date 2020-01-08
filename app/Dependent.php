<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Dependent extends Model
{
    protected $fillable  = [
    	'dependent_name',
        'dependent_gender',
    	'bdate',
        'relation'
    ];
}
