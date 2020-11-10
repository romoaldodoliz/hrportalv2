<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyLearningAndDevelopment extends Model
{
    protected $connection = "mysql";

    protected $fillable = [
        'user_id',
        'survey_id',
        'user_name',
        'core_skills',
        'cc_trainings',
        'cc_communication_skills',
        'cc_communication_skills_other',
        'cc_customer_service',
        'cc_customer_service_other',
        'cc_it_software_skills',
        'cc_it_software_skills_other',
        'leadership_trainings',
        'fc_answer_1',
        'fc_answer_2',
        'fc_answer_3',
        'fc_answer_4',
        'fc_answer_5',
    ];
}
