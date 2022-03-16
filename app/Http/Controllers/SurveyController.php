<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\User;
use App\SettingsSurvey;
use App\SurveyLearningAndDevelopment;
use App\SurveyCulture;
use App\SurveyNov2021Culture;
use App\SurveyDec2021CqaCulture;
use App\SurveyDogTreat;
use App\SurveyLegalQuestionnaire;
use App\SurveyLegalQuestionnaireUser;
use App\SurveyExitInterviewForm;

use App\PreTestWheatCleaningTemperingAndConditioning;
use App\PreTestWheatCleaningTemperingAndConditioningUser;

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

    //-----------------------------------------------------------------------------
    // November 2021 SURVEY CULTURE
    public function surveyNov2021Culture(Request $request){
        $user_id = $request->user_id;
        session([
            'survey_nov_2021_culture_user_id' => $user_id
        ]);
        $check  = SurveyNov2021Culture::where('user_id',$user_id)->first();
        if($check){
            return redirect('http://10.96.4.70/login');
        }else{
            return view('surveys.survey_nov_2021_culture');
        }
    }

    public function getUserSurveyNov2021Culture(){
        $user_session_id = session('survey_nov_2021_culture_user_id');
        return Employee::with('companies','departments','locations')
                        ->where('user_id',$user_session_id)
                        ->first();
    }

    public function saveSurveyNov2021Culture(Request $request){
        
        $this->validate($request, [
            'q1' => 'required',
            'q2' => 'required',
            'q3' => 'required',
            'q4' => 'required',
            'q5' => 'required',
            'q6' => 'required',
            'q7' => 'required',
            'q8' => 'required',
            'q9' => 'required',
            'q10' => 'required',
            'l1' => 'required',
            'l2' => 'required',
        ],[
            'q1.required' => 'This field is required.',
            'q2.required' => 'This field is required.',
            'q3.required' => 'This field is required.',
            'q4.required' => 'This field is required.',
            'q5.required' => 'This field is required.',
            'q6.required' => 'This field is required.',
            'q7.required' => 'This field is required.',
            'q8.required' => 'This field is required.',
            'q9.required' => 'This field is required.',
            'q10.required' => 'This field is required.',
            'l1.required' => 'This field is required.',
            'l2.required' => 'This field is required.',
        ]);

       DB::beginTransaction();
        try {
            $data = $request->all();
            $data['date_answered'] = date('Y-m-d');
            $data['time_answered'] = date('h:i:s');
            if($survey = SurveyNov2021Culture::create($data)){
                DB::commit();
                return "saved";
            }
        }catch (Exception $e) {
            DB::rollBack();
            return "error";
        }
    }

    public function allSurveyNov2021Culture(){
        return $survey = SurveyNov2021Culture::with('employee')->orderBy('created_at','ASC')->get();
    }
    public function exportSurveyNov2021Culture(){
        return view('surveys.export_survey_nov_2021_culture');
    }

    //-----------------------------------------------------------------------------
    // DECEMBER 2021 SURVEY CQA CULTURE
    public function surveyDec2021CqaCulture(Request $request){
        $user_id = $request->user_id;
        session([
            'survey_dec_2021_cqa_culture_user_id' => $user_id
        ]);
        $check  = SurveyDec2021CqaCulture::where('user_id',$user_id)->first();
        if($check){
            return redirect('http://10.96.4.70/login');
        }else{
            return view('surveys.survey_dec_2021_cqa_culture');
        }
    }

    public function getUserSurveyDec2021CqaCulture(){
        $user_session_id = session('survey_dec_2021_cqa_culture_user_id');
        return Employee::with('companies','departments','locations')
                        ->where('user_id',$user_session_id)
                        ->first();
    }

    public function saveSurveyDec2021CqaCulture(Request $request){
        
        $this->validate($request, [
            'q1' => 'required',
            'q2' => 'required',
            'q3' => 'required',
            'q4' => 'required',
            'q5' => 'required',
            'q6' => 'required',
            'q7' => 'required',
            'q8' => 'required',
            'q9' => 'required',
            'q10' => 'required',
            'q11' => 'required',
            'q12' => 'required',
            'q13' => 'required',
            'q14' => 'required',
            'q15' => 'required',
        ],[
            'q1.required' => 'This field is required.',
            'q2.required' => 'This field is required.',
            'q3.required' => 'This field is required.',
            'q4.required' => 'This field is required.',
            'q5.required' => 'This field is required.',
            'q6.required' => 'This field is required.',
            'q7.required' => 'This field is required.',
            'q8.required' => 'This field is required.',
            'q9.required' => 'This field is required.',
            'q10.required' => 'This field is required.',
            'q11.required' => 'This field is required.',
            'q12.required' => 'This field is required.',
            'q13.required' => 'This field is required.',
            'q14.required' => 'This field is required.',
            'q15.required' => 'This field is required.',
        ]);

       DB::beginTransaction();
        try {
            $data = $request->all();
            $data['date_answered'] = date('Y-m-d');
            $data['time_answered'] = date('h:i:s');
            if($survey = SurveyDec2021CqaCulture::create($data)){
                DB::commit();
                return "saved";
            }
        }catch (Exception $e) {
            DB::rollBack();
            return "error";
        }
    }

    public function allSurveyDec2021CqaCulture(){
        return $survey = SurveyDec2021CqaCulture::with('employee')->orderBy('created_at','ASC')->get();
    }
    public function exportSurveyDec2021CqaCulture(){
        return view('surveys.export_survey_dec_2021_cqa_culture');
    }

    // PRE TEST WHEAT CLEANING TEMPERING AND CONDITIONING
    public function preTestWheatCleaningTemperingAndConditioning(Request $request){
        $user_id = $request->user_id;
        session([
            'pre_test_wheat_cleaning_tempering_and_conditioning_user_id' => $user_id
        ]);

        $check = PreTestWheatCleaningTemperingAndConditioningUser::where('user_id',$request->user_id)->where('status','1')->first();
        if($check){
           $checkSurvey = PreTestWheatCleaningTemperingAndConditioning::where('user_id',$request->user_id)->first();
            if(empty($checkSurvey)){
                return view('surveys.pre_test_wheat_cleaning_tempering_and_conditioning');
            }else{
                return redirect('http://10.96.4.70/login');
            }
        }else{
            return redirect('http://10.96.4.70/login');
        }

        // $check  = PreTestWheatCleaningTemperingAndConditioning::where('user_id',$user_id)->first();
        // if($check){
        //     return redirect('http://10.96.4.70/login');
        // }else{
        //     $check_pretest = PreTestWheatCleaningTemperingAndConditioningUser::where('user_id',$user_id)->where('status','1')->first();
        //     if($check_pretest){
                // return view('surveys.pre_test_wheat_cleaning_tempering_and_conditioning');
        //     }else{
        //         return redirect('http://10.96.4.70/login');
        //     }
        // }
    }

    public function getPreTestWheatCleaningTemperingAndConditioningUsers(){
        return Employee::select('id','user_id','first_name','last_name','position')->with('user','companies','departments','locations','pre_test_wheat_user')
                        ->where('status','active')
                        ->get();
    }

    public function changePreTestWheatCleaningTemperingAndConditioningUserStatus(Request $request){

        $data = $request->all();
        $user_id = $data['user_id'];
        $check = PreTestWheatCleaningTemperingAndConditioningUser::where('user_id',$data['user_id'])->first();

        if($check){
            unset($data['user_id']);
            $check->update($data);
            return Employee::select('id','user_id','first_name','last_name','position')->with('user','companies','departments','locations','pre_test_wheat_user')
                        ->where('user_id',$user_id)
                        ->first();
        }else{
            PreTestWheatCleaningTemperingAndConditioningUser::create($data);
            return Employee::select('id','user_id','first_name','last_name','position')->with('user','companies','departments','locations','pre_test_wheat_user')
            ->where('user_id',$user_id)
            ->first();
        }
    }

    public function getUserPreTestWheatCleaningTemperingAndConditioning(){
        $user_session_id = session('pre_test_wheat_cleaning_tempering_and_conditioning_user_id');
        return Employee::with('companies','departments','locations')
                        ->where('user_id',$user_session_id)
                        ->first();
    }

    public function savePreTestWheatCleaningTemperingAndConditioning(Request $request){
        
        $this->validate($request, [
            'q1' => 'required',
            'q2' => 'required',
            'q3' => 'required',
            'q4' => 'required',
            'q5' => 'required',
            'q6' => 'required',
            'q7' => 'required',
            'q8' => 'required',
            'q9' => 'required',
            'q10' => 'required',
            'q11' => 'required',
            'q12' => 'required',
            'q13' => 'required',
            'q14' => 'required',
            'q15' => 'required',
            'q16' => 'required',
            'q17' => 'required',
            'q18' => 'required',
            'q19' => 'required',
            'q20' => 'required',
            'q21' => 'required',
            'q22' => 'required',
            'q23' => 'required',
            'q24_a' => 'required',
            'q24_b' => 'required',
            'q25' => 'required',
            'q26_a' => 'required',
            'q26_b' => 'required',
            'q26_c' => 'required',
            'q26_d' => 'required',
            'q26_e' => 'required',
            'q26_f' => 'required',
            'q26_g' => 'required',
            'q26_h' => 'required',
            'q26_i' => 'required',
            'q26_j' => 'required',
            'q27' => 'required',
            'q28' => 'required',
            'q29' => 'required',
            'q30' => 'required',
            'q31' => 'required',
        ]
        ,[
            'q1.required' => 'This field is required.',
            'q2.required' => 'This field is required.',
            'q3.required' => 'This field is required.',
            'q4.required' => 'This field is required.',
            'q5.required' => 'This field is required.',
            'q6.required' => 'This field is required.',
            'q7.required' => 'This field is required.',
            'q8.required' => 'This field is required.',
            'q9.required' => 'This field is required.',
            'q10.required' => 'This field is required.',
            'q11.required' => 'This field is required.',
            'q12.required' => 'This field is required.',
            'q13.required' => 'This field is required.',
            'q14.required' => 'This field is required.',
            'q15.required' => 'This field is required.',
            'q16.required' => 'This field is required.',
            'q17.required' => 'This field is required.',
            'q18.required' => 'This field is required.',
            'q19.required' => 'This field is required.',
            'q20.required' => 'This field is required.',
            'q21.required' => 'This field is required.',
            'q22.required' => 'This field is required.',
            'q23.required' => 'This field is required.',
            'q24_a.required' => 'This field is required.',
            'q24_b.required' => 'This field is required.',
            'q25.required' => 'This field is required.',
            'q26_a.required' => 'This field is required.',
            'q26_b.required' => 'This field is required.',
            'q26_c.required' => 'This field is required.',
            'q26_d.required' => 'This field is required.',
            'q26_e.required' => 'This field is required.',
            'q26_f.required' => 'This field is required.',
            'q26_g.required' => 'This field is required.',
            'q26_h.required' => 'This field is required.',
            'q26_i.required' => 'This field is required.',
            'q26_j.required' => 'This field is required.',
            'q27.required' => 'This field is required.',
            'q28.required' => 'This field is required.',
            'q29.required' => 'This field is required.',
            'q30.required' => 'This field is required.',
            'q31.required' => 'This field is required.'
        ]
        );

       DB::beginTransaction();
        try {
            $data = $request->all();

            $q3 = json_decode($data['q3']);
            $data['q3'] = implode(",",$q3);

            $q6 = json_decode($data['q6']);
            $data['q6'] = implode(",",$q6);

            $q7 = json_decode($data['q7']);
            $data['q7'] = implode(",",$q7);

            $q10 = json_decode($data['q10']);
            $data['q10'] = implode(",",$q10);

            $q18 = json_decode($data['q18']);
            $data['q18'] = implode(",",$q18);

            $q20 = json_decode($data['q20']);
            $data['q20'] = implode(",",$q20);

            $q22 = json_decode($data['q22']);
            $data['q22'] = implode(",",$q22);

            $q23 = json_decode($data['q23']);
            $data['q23'] = implode(",",$q23);

            $q25 = json_decode($data['q25']);
            $data['q25'] = implode(",",$q25);

            $data['date_answered'] = date('Y-m-d');
            $data['time_answered'] = date('h:i:s');
            if($survey = PreTestWheatCleaningTemperingAndConditioning::create($data)){
                DB::commit();
                return "saved";
            }
        }catch (Exception $e) {
            DB::rollBack();
            return "error";
        }
    }

    public function allPreTestWheatCleaningTemperingAndConditioning(){
        return $pretest = PreTestWheatCleaningTemperingAndConditioning::with('employee')->orderBy('created_at','ASC')->get();
    }
    public function exportPreTestWheatCleaningTemperingAndConditioning(){
        return view('surveys.export_pre_test_wheat_cleaning_tempering_and_conditioning');
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

    //-----------------------------------------------------------------------------
    // DOG TREATS
    public function surveyDogTreats(Request $request){
        $user_id = $request->user_id;
        session([
            'survey_dog_treats_user_id' => $user_id
        ]);
        $check  = SurveyDogTreat::where('user_id',$user_id)->first();
        if($check){
            return redirect('http://10.96.4.70/login');
        }else{
            return view('surveys.survey_dog_treats');
        }
    }

    public function getUserSurveyDogTreats(){
        $user_session_id = session('survey_dog_treats_user_id');
        return Employee::with('companies','departments','locations')
                        ->where('user_id',$user_session_id)
                        ->first();
    }

    public function saveSurveyDogTreats(Request $request){
        
        $this->validate($request, [
            'q1' => 'required',
            'q2_a' => 'required',
            'q2_b' => 'required',
            'q2_c' => 'required',
            'q2_d' => 'required'
        ],[
            'q1.required' => 'This field is required.',
            'q2_a.required' => 'This field is required.',
            'q2_b.required' => 'This field is required.',
            'q2_c.required' => 'This field is required.',
            'q2_d.required' => 'This field is required.'
        ]);

       DB::beginTransaction();
        try {
            $data = $request->all();
            $data['date_answered'] = date('Y-m-d');
            $data['time_answered'] = date('h:i:s');
            if($survey = SurveyDogTreat::create($data)){
                DB::commit();
                return "saved";
            }
        }catch (Exception $e) {
            DB::rollBack();
            return "error";
        }
    }

    public function allSurveyDogTreats(){
        return $survey = SurveyDogTreat::with('employee')->orderBy('created_at','ASC')->get();
    }
    public function exportSurveyDogTreats(){
        return view('surveys.export_survey_dog_treats');
    }

    //Exit Interview-----------------------------------------------------------------------------------------------------
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
        return SurveyExitInterviewForm::where('created_at','>=',$request->from)->whereDate('created_at','<=',$request->to)->get();
    }
}
