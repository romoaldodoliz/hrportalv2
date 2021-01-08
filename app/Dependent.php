<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Dependent extends Model
{
    protected $connection = "mysql";
    
    protected $fillable  = [
    	'dependent_name',
        'dependent_gender',
    	'bdate',
        'relation',
        'dependent_status'
    ];

    public function employee()
    {
    	return $this->belongsToOne('App\Employee');
    }
}
