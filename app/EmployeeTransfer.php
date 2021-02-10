<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeTransfer extends Model
{
    protected $connection = "mysql";
    
    protected $fillable  = [
    	'employee_id',
        'previous_id_number',
        'previous_position',
        'previous_date_hired',
        'previous_division',
        'previous_cluster',
        'previous_department',
        'previous_company',
        'previous_location',
        'previous_system_approvers',
        'new_id_number',
        'new_position',
        'new_date_hired',
        'new_division',
        'new_cluster',
        'new_department',
        'new_company',
        'new_location',
        'new_system_approvers',
        'transferred_by',
    ];

    public function employee()
    {
        return $this->belongsTo('App\Employee')->select('id','first_name','last_name','middle_name');
    }

    public function new_company()
    {
        return $this->hasOne('App\Company','id','new_company');
    }

    public function new_department()
    {
        return $this->hasOne('App\Department','id','new_department');
    }

    public function new_location()
    {
        return $this->hasOne('App\Location','id','new_location');
    }

    public function previous_company()
    {
        return $this->hasOne('App\Company','id','previous_company');
    }

    public function previous_department()
    {
        return $this->hasOne('App\Department','id','previous_department');
    }

    public function previous_location()
    {
        return $this->hasOne('App\Location','id','previous_location');
    }


    public function employee_transfer_attachments()
    {
        return $this->hasMany('App\EmployeeTransferAttachment');
    }

}
