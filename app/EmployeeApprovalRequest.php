<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class EmployeeApprovalRequest extends Model implements AuditableContract
{
    protected $connection = "mysql";
    
    use Auditable;
    protected $auditIncluded = [];
    protected $auditTimestamps = true;

    protected $fillable = [
        'employee_id',
        'employee_data',
        'original_employee_data',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo('App\Employee')->with('companies','departments','locations');
    }

    public function employee_data_request(){
       
        if($this->attributes){
            $employee_data = json_decode($this->attributes['employee_data']);
            return $employee_data;
        }
        
    }
    
}
