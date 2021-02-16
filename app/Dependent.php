<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Dependent extends Model
{
    protected $connection = "mysql";
    
    protected $fillable  = [
    	'first_name',
    	'last_name',
    	'middle_name',
    	'dependent_name',
        'dependent_gender',
    	'bdate',
        'relation',
        'dependent_status',
        'hmo_enrollment',
        'civil_status',
    ];

    public function employee()
    {
    	return $this->belongsToOne('App\Employee');
    }
}
