<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeNpaRequest extends Model
{
    protected $connection = "mysql";
    
    protected $fillable  = [
    	'employee_id',
        'ctrl_no',
        'date_prepared',
        'subject',
        'employee_name',
        'from_type_of_movement',
        'from_company',
        'from_position_title',
        'from_immediate_manager',
        'from_department',
        'effectivity_date',
        'from_monthly_basic_salary',
        'to_type_of_movement',
        'to_company',
        'to_position_title',
        'to_immediate_manager',
        'to_department',
        'to_monthly_basic_salary',
        'requested_by',
        'prepared_by',
        'prepared_by_status',
        'recommended_by',
        'recommended_by_status',
        'approved_by',
        'approved_by_status',
        'bu_head',
        'bu_head_status',
        'status',
    ];

    public function from_company()
    {
        return $this->hasOne('App\Company','id','from_company')->select('id','name');
    }

    public function from_immediate_manager()
    {
        return $this->hasOne('App\Employee','id','from_immediate_manager')->select('id','first_name','last_name','position');
    }

    public function from_department()
    {
        return $this->hasOne('App\Department','id','from_department')->select('id','name');
    }

    public function to_company()
    {
        return $this->hasOne('App\Company','id','to_company')->select('id','name');
    }

    public function to_immediate_manager()
    {
        return $this->hasOne('App\Employee','id','to_immediate_manager')->select('id','first_name','last_name','position');
    }

    public function to_department()
    {
        return $this->hasOne('App\Department','id','to_department')->select('id','name');
    }

    public function prepared_by()
    {
        return $this->hasOne('App\Employee','id','prepared_by')->select('id','first_name','last_name','position');
    }

    public function recommended_by()
    {
        return $this->hasOne('App\Employee','id','recommended_by')->select('id','first_name','last_name','position');
    }

    public function approved_by()
    {
        return $this->hasOne('App\Employee','id','approved_by')->select('id','first_name','last_name','position');
    }

    public function bu_head()
    {
        return $this->hasOne('App\Employee','id','bu_head')->select('id','first_name','last_name','position');
    }

}
