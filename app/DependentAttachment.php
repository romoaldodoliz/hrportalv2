<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DependentAttachment extends Model
{
    //
    protected $auditTimestamps = true;

    protected $fillable = [
        'employee_id',
        'file',
    ];

}
