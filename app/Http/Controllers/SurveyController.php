<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\User;
use App\SettingsSurvey;
use App\SurveyLearningAndDevelopment;

use DB;
class SurveyController extends Controller
{

    public function index(){ 
        return view('surveys.survey_1');
    }

    public function getSurvey(){
        $survey = SettingsSurvey::where('status','Active')->first();
        session(['survey_description'=>$survey['survey_description']]);
        return $survey;
    }

    public function saveUserSurvey(Request $request){
        
        $this->validate($request, [
            'user_name' => 'required|unique:survey_learning_and_developments',
            'user_company' => 'required',
            'user_department' => 'required',
            'core_skills' => 'required',
            'cc_trainings' => 'required',
            'cc_communication_skills' => 'required',
            'cc_customer_service' => 'required',
            'cc_it_software_skills' => 'required',
            'leadership_trainings' => 'required',
            'fc_answer_1' => 'required',
            'fc_answer_2' => 'required',
            'fc_answer_3' => 'required',
            'fc_answer_4' => 'required',
            'fc_answer_5' => 'required',
        ],[
            'core_skills.required' => 'This field is required.',
            'cc_trainings.required' => 'This field is required.',
            'cc_communication_skills.required' => 'This field is required.',
            'cc_customer_service.required' => 'This field is required.',
            'cc_it_software_skills.required' => 'This is field is required.',
            'leadership_trainings.required' => 'This is field is required.',
            'fc_answer_1.required' => 'This is field is required.',
            'fc_answer_2.required' => 'This is field is required.',
            'fc_answer_3.required' => 'This is field is required.',
            'fc_answer_4.required' => 'This is field is required.',
            'fc_answer_5.required' => 'This is field is required.',
        ]);
        
        DB::beginTransaction();
        try {
            $data = $request->all();
            if($survey = SurveyLearningAndDevelopment::create($data)){
                DB::commit();
                return "saved";
            }
        }catch (Exception $e) {
            DB::rollBack();
            return "error";
        }

    }

}
