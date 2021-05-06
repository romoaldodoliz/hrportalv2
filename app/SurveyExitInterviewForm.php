<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyExitInterviewForm extends Model
{
    protected $connection = "mysql";

    protected $fillable = [
        'user_id', 
        'name', 
        'position', 
        'department',
        'company',
        'location',
        'no_of_months_worked',
        'age',
        
        'reason_for_leaving',
        'reason_for_leaving_others',
        'reason_is_work',
        'reason_is_work_others',

        'r1_a',
        'r1_a_comments',
        'r1_b',
        'r1_b_comments',
        'r1_c',
        'r1_c_comments',
        'r1_d',
        'r1_d_comments',
        
        'r2_a',
        'r2_a_comments',
        'r2_b',
        'r2_b_comments',
        'r2_c',
        'r2_c_comments',

        'r3_a',
        'r3_a_comments',
        'r3_b',
        'r3_b_comments',
        'r3_c',
        'r3_c_comments',

        'r4_a',
        'r4_a_comments',
        'r4_b',
        'r4_b_comments',
        'r4_c',
        'r4_c_comments',

        'r5_a',
        'r5_a_comments',
        'r5_b',
        'r5_b_comments',

        'r6_a',
        'r6_a_comments',
        'r6_b',
        'r6_b_comments',
        'r6_c',
        'r6_c_comments',
        'r6_d',
        'r6_d_comments',

        'r7_a',
        'r7_a_comments',
        'r7_b',
        'r7_b_comments',
        'r7_c',
        'r7_c_comments',
        'r7_d',
        'r7_d_comments',

        'r8_a',
        'r8_a_comments',
        'r8_b',
        'r8_b_comments',
        'r8_c',
        'r8_c_comments',

        'r9_a',
        'r9_a_comments',
        'r9_b',
        'r9_b_comments',
        'r9_c',
        'r9_c_comments',
        'r9_d',
        'r9_d_comments',

        'r10_a',
        'r10_a_comments',
        'r10_b',
        'r10_b_comments',
        'r10_c',
        'r10_c_comments',

        'r11_a',
        'r11_a_comments',
        'r11_b',
        'r11_b_comments',
        'r11_c',
        'r11_c_comments',
        'other_suggestions_for_improvement',
    ];
}
