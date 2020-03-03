<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeDetailVerification extends Model
{
    protected $auditTimestamps = true;

    protected $fillable = [
        'employee_id',
        'verification'
    ];

    public function employee()
    {
    	return $this->belongsTo('App\Employee')->select('id','user_id','first_name','middle_name','last_name')->with('companies','departments','locations');
    }
    
}
