<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendEmailEmployeeRegularNotification extends Model
{
    protected $connection = "mysql";
    
    protected $fillable = [
        'employee_id',
        'receiver',
        'email_desc',
        'month_status'
    ];

}
