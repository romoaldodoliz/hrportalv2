<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyLegalQuestionnaire extends Model
{
    protected $connection = "mysql";

    protected $fillable = [
        'user_id', 'name', 'position', 'department','company','location','q1','q2','q3'
    ];

    public function employee()
    {
        return $this->hasOne('App\Employee','user_id','user_id')->select('id','user_id','id_number','cluster')->where('status','Active');
    }
}
