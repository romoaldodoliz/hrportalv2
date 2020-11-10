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
    public function index(Request $request){

        $check = SurveyLearningAndDevelopment::where('user_id',$request->user_id)->first();
        if($check){
            return redirect('http://10.96.4.70/login');
        }else{
            session(['survey_user_id' =>  $request->user_id]);
            return redirect('/survey-verified-user');
        }
        
    }

    public function surveyVerfiedUser(){
        
        if(session('survey_user_id')){
            return view('surveys.survey_1');
        }else{
            return redirect('http://10.96.4.70/login');
        }
    }

    public function getSurvey(){
        return SettingsSurvey::where('status','Active')->first();
    }

    public function getSurveyUser(){
        $employee = Employee::with('companies','departments','locations','user')
                        ->where('user_id',session('survey_user_id'))
                        ->where('status','Active')
                        ->first();

        return $employee_data = [
            'user_id'=>$employee->user_id,
            'name'=>$employee->user->name,
            'company'=>$employee->companies[0]->name,
            'department'=>$employee->departments[0]->name,
        ];
    }

    public function saveUserSurvey(Request $request){
        
        $this->validate($request, [
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
            'core_skills.required' => 'This is field is required.',
            'cc_trainings.required' => 'This is field is required.',
            'cc_communication_skills.required' => 'This is field is required.',
            'cc_customer_service.required' => 'This is field is required.',
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
