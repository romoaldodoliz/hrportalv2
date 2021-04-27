<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class EmployeeTransferAttachment extends Model implements AuditableContract
{
    protected $connection = "mysql";
    
    use Auditable;
    protected $auditIncluded = [];
    protected $auditTimestamps = true;

    protected $fillable = [
        'employee_transfer_id',
        'employee_id',
        'filename',
        'path',
        'status'
    ];

}
