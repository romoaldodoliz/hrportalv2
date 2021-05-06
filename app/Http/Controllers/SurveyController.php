<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\User;
use App\SettingsSurvey;
use App\SurveyLearningAndDevelopment;
use App\SurveyCulture;
use App\SurveyLegalQuestionnaire;
use App\SurveyLegalQuestionnaireUser;
use App\SurveyExitInterviewForm;

use DB;
class SurveyController extends Controller
{

    //---------------------------------------------------------------------------
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

    public function userSurvey(){
        return view('surveys.survey_1_user');
    }

    public function allUserSurvey(Request $request){
        $all_user = SurveyLearningAndDevelopment::where('survey_id',$request->survey_id)->get();

        $extracted_data = [];
        if($all_user){
            foreach($all_user as $k => $item){
                $extracted_data[$k] = $item;
                $extracted_data[$k]['core_skills'] = json_decode($item['core_skills']);
                $extracted_data[$k]['cc_trainings'] = json_decode($item['cc_trainings']);
                $extracted_data[$k]['cc_communication_skills'] = json_decode($item['cc_communication_skills']);
                $extracted_data[$k]['cc_customer_service'] = json_decode($item['cc_customer_service']);
                $extracted_data[$k]['cc_it_software_skills'] = json_decode($item['cc_it_software_skills']);
                $extracted_data[$k]['leadership_trainings'] = json_decode($item['leadership_trainings']);
            }
        }

        return $extracted_data;
    }


    //----------------------------------------------------------------------------
    //DECEMBER 2020 VALUES QUESTIONNAIRE

    public function surveyCulture(Request $request){
        $user_id = $request->user_id;
        session([
            'survey_culture_user_id' => $user_id
        ]);
        $check  = SurveyCulture::where('user_id',$user_id)->first();
        if($check){
            return redirect('http://10.96.4.70/login');
        }else{
            return view('surveys.survey_culture');
        }
       
    }

    public function getUserSurveyCulture(){
        $user_session_id = session('survey_culture_user_id');
        return Employee::with('companies','departments','locations')
                        ->where('user_id',$user_session_id)
                        ->first();
    }
    
    public function saveSurveyCulture(Request $request){

        $this->validate($request, [
            'q1' => 'required',
            'q2' => 'required',
            'q3' => 'required',
            'q4' => 'required',
            'q5' => 'required',
            'q6' => 'required'
        ],[
            'q1.required' => 'This field is required.',
            'q2.required' => 'This field is required.',
            'q3.required' => 'This field is required.',
            'q4.required' => 'This field is required.',
            'q5.required' => 'This field is required.',
            'q6.required' => 'This field is required.'
        ]);

       DB::beginTransaction();
        try {
            $data = $request->all();
            $q1 = json_decode($data['q1']);
            $data['q1'] = implode(",",$q1);
            $q6 = json_decode($data['q6']);
            $data['q6'] = implode(",",$q6);
            if($survey = SurveyCulture::create($data)){
                DB::commit();
                return "saved";
            }
        }catch (Exception $e) {
            DB::rollBack();
            return "error";
        }
    }

    public function allSurveyCulture(){
        $survey = SurveyCulture::with('employee')->orderBy('department','ASC')->get();

        $itemArr = [];
        foreach($survey as $k => $item){
            $itemArr[$k] = $item;
            //Sort
            $q1 = explode(',',$item['q1']);
            sort($q1);
            $itemArr[$k]['q1'] = implode(',',$q1);

            $q6 = explode(',',$item['q6']);
            sort($q6);
            $itemArr[$k]['q6'] = implode(',',$q6);
        }
        return $itemArr;
    }

    public function exportSurveyCulture(){
        
        return view('surveys.export_survey_culture');
    }

    //Survey Legal

    public function surveyLegalQuestionnaire(Request $request){
        $validate_user = SurveyLegalQuestionnaire::where('user_id',$request->user_id)->where('status','Active')->first();
        if(empty($validate_user)){
            return view('surveys.survey_legal_questionnaire');
        }else{
            return redirect('http://10.96.4.70/login');
        }
       
    }

    public function getUserSurvey(Request $request){
        $user_session_id = $request->user_id;
        return Employee::with('companies','departments','locations')
                        ->where('user_id',$user_session_id)
                        ->first();
    }

    public function saveSurveyLegalQuestionnaire(Request $request){
        $this->validate($request, [
            'q1' => 'required',
            'q2' => 'required',
            'q3' => 'required',
        ],[
            'q1.required' => 'This field is required.',
            'q2.required' => 'This field is required.',
            'q3.required' => 'This field is required.',
        ]);
        
        DB::beginTransaction();
        try {
            $data = $request->all();
            if($survey = SurveyLegalQuestionnaire::create($data)){
                DB::commit();
                return "saved";
            }
        }catch (Exception $e) {
            DB::rollBack();
            return "error";
        }
    }

    public function surveyLegalQuestionnaireUser(){
        return view('surveys.survey_legal_questionnaire_users');
    }
    
    public function getsurveyLegalQuestionnaire(){
        return SurveyLegalQuestionnaire::all();
    }

    public function getSurveyLegalQuestionnaireUser(){
        return Employee::select('id','user_id','first_name','last_name','position')->with('user','companies','departments','locations','survey_legal_user')
                        ->where('status','active')
                        ->get();
    }

    public function changeSurveyLegalQuestionnaireUser(Request $request){
        $data = $request->all();
        $user_id = $data['user_id'];
        $check = SurveyLegalQuestionnaireUser::where('user_id',$data['user_id'])->first();

        if($check){
            unset($data['user_id']);
            $check->update($data);
            return Employee::select('id','user_id','first_name','last_name','position')->with('user','companies','departments','locations','survey_legal_user')
                        ->where('user_id',$user_id)
                        ->first();
        }else{
            SurveyLegalQuestionnaireUser::create($data);
            return Employee::select('id','user_id','first_name','last_name','position')->with('user','companies','departments','locations','survey_legal_user')
            ->where('user_id',$user_id)
            ->first();
        }
    }

    public function surveyExitInterviewForm(){
        return view('surveys.survey_exit_interview_form');
    }

    public function saveSurveyExitInterviewForm(Request $request){
        
        $check = SurveyExitInterviewForm::where('user_id',$request->user_id)->first();

        DB::beginTransaction();
        try {
            $data = $request->all();

            if($check){
                if($check->update($data)){
                    DB::commit();
                    return "saved";
                }else{
                    DB::rollBack();
                    return "error";
                }
            }else{
                if($survey = SurveyExitInterviewForm::create($data)){
                    DB::commit();
                    return "saved";
                }else{
                    DB::rollBack();
                    return "error";
                }
            }
        }catch (Exception $e) {
            DB::rollBack();
            return "error";
        }
    }

    public function getUserSurveyExitInterviewForm(Request $request){
        return SurveyExitInterviewForm::where('user_id',$request->user_id)->first();
    }

    public function surveyExitInterviewReports(){
        return view('surveys.survey_exit_interview_form_reports');
    }

    public function allSurveyExitInterview(Request $request){
        
        return SurveyExitInterviewForm::where('updated_at','>=',$request->from)->whereDate('updated_at','<=',$request->to)->get();
    }

}
