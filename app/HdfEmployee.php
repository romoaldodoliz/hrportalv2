<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HdfEmployee extends Model
{
    protected $connection = "mysql";
    
    protected $fillable = [
        'employee_id',
        'name',
        'dept_bu_position',
        'contact_number',
        'status',
        'remarks',
        'date_time',
        'attachment_file'
    ];

}
