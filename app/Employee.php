<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

use Carbon\Carbon;

class Employee extends Model implements AuditableContract
{
    use Auditable;
    protected $auditIncluded = [];
    protected $auditTimestamps = true;
    
    protected $fillable = [
    	'series_number',
    	'id_number',
    	'employee_number',
    	'company', 
        'department',
        'division',
    	'level',
    	'location',
    	'area',
    	'last_name',
    	'first_name',
    	'middle_name',
    	'middle_initial',
    	'nick_name',
        'job_remarks', // job information remarks
        'id_remarks', // identification information remarks
        'gender',
        'marital_status',
        'marital_status_attachment',
        'current_address',
    	'position',
    	'classification',
    	'status',
    	'permanent_address',
    	'phone_number',
    	'mobile_number',
    	'birthdate',
    	'birthplace',
    	'bank_name',
    	'bank_account_number',
    	'sss_number',
        'phil_number',
        'hdmf',
    	'tax_number',
    	'tax_status',
    	'contact_person',
        'contact_number',
    	'contact_relation',
        'name_suffix',
        'date_hired',
        'date_regularized',
        'date_resigned',
        'ess_ee_number',
        'avatar', // employee avatar
        'update_remark', // seperate added table
        'school_graduated',
        'school_course',
        'school_year',
        'vocational_graduated',
        'vocational_course',
        'vocational_year',
        'confidential',
    ];
    

    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name) .' '. ucfirst($this->last_name);
    }

    public function companies()
    {
        return $this->belongsToMany('App\Company')->withTimestamps();
    }

    public function departments()
    {
        return $this->belongsToMany('App\Department')->withTimestamps();
    }

    public function locations()
    {
        return $this->belongsToMany('App\Location')->withTimestamps();
    }

    public function getCompanyListAttribute()
    {
        return $this->companies()->pluck('id')->all();
    }

    public function assign_heads()
    {
        return $this->hasMany('App\AssignHead')->orderBy('id','ASC');
    }

    public function dependents()
    {
        return $this->hasMany('App\Dependent');
    }

    public function print_id_logs()
    {
        return $this->hasMany('App\PrintIdLog');
    }

    public function verification()
    {
        return $this->hasOne('App\EmployeeDetailVerification');
    }

}
