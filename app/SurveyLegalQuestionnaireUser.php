<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyLegalQuestionnaireUser extends Model
{
    protected $connection = "mysql";

    protected $fillable = [
        'user_id', 'status'
    ];
    
}
