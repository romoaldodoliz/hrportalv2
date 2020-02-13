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
    
}
