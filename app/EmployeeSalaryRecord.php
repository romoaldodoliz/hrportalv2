<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryRecord extends Model
{
    protected $connection = "mysql";
    
    protected $fillable  = [
    	'employee_id',
    	'old_salary',
    	'new_salary',
    	'reason',
    	'salary_date'
    ];
}
