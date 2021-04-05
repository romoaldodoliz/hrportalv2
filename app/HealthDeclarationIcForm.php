<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HealthDeclarationIcForm extends Model
{
    protected $connection = "mysql";
    
    protected $fillable = [
        'employee_id',
        'name',
        'dept_bu_position',
        'contact_number',
        'temperature',
        'one_question',
        'two_question',
        'three_question',
        'four_question',
        'five_question',
        'six_question',
        'six_yes_desc',
        'seven_question',
        'seven_yes_desc',
        'eight_question',
        'status',
        'date_time',
        'ip_address'
    ];
}
