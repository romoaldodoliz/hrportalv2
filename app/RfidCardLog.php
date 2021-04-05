<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RfidCardLog extends Model
{
    protected $connection = "mysql";
    
    protected $auditIncluded = [];
    protected $auditTimestamps = true;

    protected $fillable = [
    	'employee_id',
    	'biometric_user_id',
    	'card_id',
    	'card_id_number',
    ];
}
