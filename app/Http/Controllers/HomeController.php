<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Employee;
use App\EmployeeApprovalRequest;
use App\EmployeeDetailVerification;
use App\DependentAttachment;
use App\User;
use App\RfidNumber;
use App\Cluster;
use App\AssignHead;

use DB;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       
        $employee = Employee::where('user_id', auth()->user()->id)->first();

        if($employee){
            session(['employee_id' =>  $employee->id]);
        }
        
      $user = User::with('roles')->where('id', auth()->user()->id)->first();
       
        if(isset($user->roles)){
            if(isset(auth()->user()->roles[0])){
                if(auth()->user()->roles[0]->name == "User"){
                    return redirect('/user_profile');
                }
                elseif(auth()->user()->roles[0]->name == "Administrator Printer"){
                    return redirect('/employee_ids');
                }
                elseif(auth()->user()->roles[0]->name == "Administrator" || auth()->user()->roles[0]->name == "Admin Broadcast" || auth()->user()->roles[0]->name == "IT Broadcast" || auth()->user()->roles[0]->name == "HR Broadcast" || auth()->user()->roles[0]->name == "HR Staff"){
                    session(['header_text' => 'Dashboard']);
                    return view('home');
                }
                elseif(auth()->user()->roles[0]->name == "Cluster Head" || auth()->user()->roles[0]->name == "BU Head" || auth()->user()->roles[0]->name == "Immediate Superior"){
                    return view('employees.index');
                }
                else{
                    $user->attachRole(2);
                    return redirect('/home');
                }
            }else{
                $user->attachRole(2);
                return redirect('/home');
            }

        }else{
            $user->attachRole(2);
            return redirect('/home');
        }
        
        
        
    }

    public function user_profile(){
        $user = User::with('roles')->where('id', auth()->user()->id)->first();
       
        if(isset($user->roles)){
            if(isset(auth()->user()->roles[0])){
                return view('user_profile')->with('employee');
            }else{
                return redirect('/home');
            }
        }else{
            return redirect('/home');
        }
        
        
        
        
    }

    public function userProfile(){
        return Employee::with('companies','departments','locations','verification')->where('user_id', auth()->user()->id)->first();
    }

    
    public function userProfileRequestPendingData(){
        return EmployeeApprovalRequest::with('employee')->where('status','Pending')->get();
    }

    public function employeeApprovalRequestData(){
        return EmployeeApprovalRequest::with('employee')->orderBy('created_at','DESC')->get();
    }

    public function employeeApprovalRequests(){
        return view('employee_approval_requests.index');
    }


    public function downloadDataRequestVerification(){
        $all_employee = Employee::select('id','id_number','first_name','last_name')
                                    ->where('status','Active')
                                    ->with('companies','departments','locations','employee_approval_requests','verification')
                                    ->orderBy('series_number','ASC')
                                    ->get();

        $filtered_data = [];

        foreach( $all_employee as $key => $employee ){
            $filtered_data[$key]['id_number'] = $employee['id_number'];
            $filtered_data[$key]['name'] = strtoupper($employee['first_name']) . ' ' . strtoupper($employee['last_name']);
            $filtered_data[$key]['company'] = $employee['companies'][0]['name'];
            $filtered_data[$key]['department'] = $employee['departments'][0]['name'];
            $filtered_data[$key]['location'] = $employee['locations'][0]['name'];
            if($employee['employee_approval_requests']){
                $filtered_data[$key]['employee_approval_requests'] = $employee['employee_approval_requests']['status'];
            }else{
                $filtered_data[$key]['employee_approval_requests'] = "";
            }
           

            if($employee['employee_approval_requests']){
                if($employee['employee_approval_requests']['status'] == 'Approved'){
                    $filtered_data[$key]['accepted_by_hr'] = "Accepted";
                }else{
                    $filtered_data[$key]['accepted_by_hr'] = "";
                }
            }else{
                $filtered_data[$key]['accepted_by_hr'] = "";
            }

            if($employee['verification']){
                if($employee['verification']['verification'] == '1'){
                    $filtered_data[$key]['verified_by_employee'] = "Yes";
                }else{
                    $filtered_data[$key]['verified_by_employee'] = "No";
                }
            }else{
                $filtered_data[$key]['verified_by_employee'] = "No";
            }

            if($employee['verification']){
                if($employee['verification']['verification'] == '1'){
                    $filtered_data[$key]['verified_by_employee'] = "Yes";
                }else{
                    $filtered_data[$key]['verified_by_employee'] = "";
                }
            }else{
                $filtered_data[$key]['verified_by_employee'] = "";
            }
            

            if(!$employee['employee_approval_requests'] && !$employee['verification']){
                $filtered_data[$key]['unverified'] = "Yes";
            }else{
                $filtered_data[$key]['unverified'] = "";
            }
        }

        return $filtered_data;
    }

    public function verifiedEmployees(){
        return EmployeeDetailVerification::with('employee')->orderBy('created_at','DESC')->get();
    }

    public function verifiedEmployeeList(){
        return view('verified_employees');
    }

    public function verifyEmployee(Request $request){
        $data = $request->all();

        EmployeeDetailVerification::create($data);

        return Employee::with('companies','departments','locations','verification')->where('id',$data['employee_id'])->first();
    }

    public function employeeApproval(Request $request,EmployeeApprovalRequest $employee_request){
        
        DB::beginTransaction();
        try {

            $approved_data = array();
            $employee = Employee::with('dependents')->where('id',$employee_request->employee_id)->first();
            $employee_request_original_data = json_decode($employee_request->original_employee_data, true);
            $employee_request_approval_data = json_decode($employee_request->employee_data, true);
            $employee_request_approval_dependents_data = $employee_request_approval_data['dependents'] ? json_decode($employee_request_approval_data['dependents'], true) : '';
            $employee_request_approval_deleted_dependents_data = $employee_request_approval_data['deleted_dependents'] ? json_decode($employee_request_approval_data['deleted_dependents'], true) : '';
            
            $employee_request_approval_deleted_dependents_attachment = $employee_request_approval_data['deleted_dependent_attachments'] ? json_decode($employee_request_approval_data['deleted_dependent_attachments'], true) : '';

            $employee_request_approval_dependents_attachment = $employee_request_approval_data['dependent_attachments'] ? $employee_request_approval_data['dependent_attachments'] : [];

            if($request->status == "Approved"){
                //Update Approved Employee Request
                if($employee_request_original_data['nick_name'] != $employee_request_approval_data['nick_name']){
                    $approved_data['nick_name'] = $employee_request_approval_data['nick_name'];
                }
                if($employee_request_original_data['last_name'] != $employee_request_approval_data['last_name']){
                    $approved_data['last_name'] = $employee_request_approval_data['last_name'];
                }
                if($employee_request_original_data['middle_name'] != $employee_request_approval_data['middle_name']){
                    $approved_data['middle_name'] = $employee_request_approval_data['middle_name'];
                }
                if($employee_request_original_data['middle_initial'] != $employee_request_approval_data['middle_initial']){
                    $approved_data['middle_initial'] = $employee_request_approval_data['middle_initial'];
                }
                if($employee_request_original_data['marital_status'] != $employee_request_approval_data['marital_status']){
                    $approved_data['marital_status'] = $employee_request_approval_data['marital_status'];
                }

                if($employee_request_original_data['gender'] != $employee_request_approval_data['gender']){
                    $approved_data['gender'] = $employee_request_approval_data['gender'];
                }
                if(isset($employee_request_approval_data['marital_status_attachment'])){
                
                    $file = Storage::disk('public')->get("/marital_attachments/temps/" . $employee_request_approval_data['marital_status_attachment']);
                    if($file){
                        Storage::disk('public')->put('marital_attachments/' . $employee_request_approval_data['marital_status_attachment'] , $file); 
                    }

                    $approved_data['marital_status_attachment'] =  $employee_request_approval_data['marital_status_attachment'];
                }
                if($employee_request_original_data['mobile_number'] != $employee_request_approval_data['mobile_number']){
                    $approved_data['mobile_number'] = $employee_request_approval_data['mobile_number'];
                }
                if($employee_request_original_data['phone_number'] != $employee_request_approval_data['phone_number']){
                    $approved_data['phone_number'] = $employee_request_approval_data['phone_number'];
                }
                if($employee_request_original_data['permanent_address'] != $employee_request_approval_data['permanent_address']){
                    $approved_data['permanent_address'] = $employee_request_approval_data['permanent_address'];
                }
                if($employee_request_original_data['current_address'] != $employee_request_approval_data['current_address']){
                    $approved_data['current_address'] = $employee_request_approval_data['current_address'];
                }
                if($employee_request_original_data['contact_number'] != $employee_request_approval_data['contact_number']){
                    $approved_data['contact_number'] = $employee_request_approval_data['contact_number'];
                }
                if($employee_request_original_data['contact_person'] != $employee_request_approval_data['contact_person']){
                    $approved_data['contact_person'] = $employee_request_approval_data['contact_person'];
                }
                if($employee_request_original_data['contact_relation'] != $employee_request_approval_data['contact_relation']){
                    $approved_data['contact_relation'] = $employee_request_approval_data['contact_relation'];
                }
                if($employee_request_original_data['sss_number'] != $employee_request_approval_data['sss_number']){
                    $approved_data['sss_number'] = $employee_request_approval_data['sss_number'];
                }
                if($employee_request_original_data['phil_number'] != $employee_request_approval_data['phil_number']){
                    $approved_data['phil_number'] = $employee_request_approval_data['phil_number'];
                }
                if($employee_request_original_data['hdmf'] != $employee_request_approval_data['hdmf']){
                    $approved_data['hdmf'] = $employee_request_approval_data['hdmf'];
                }
                if($employee_request_original_data['tax_number'] != $employee_request_approval_data['tax_number']){
                    $approved_data['tax_number'] = $employee_request_approval_data['tax_number'];
                }

                if($employee_request_approval_dependents_data){
                    foreach($employee_request_approval_dependents_data as $dependent){
                        $data_dependent = [
                            'employee_id'=>$employee->id,
                            'dependent_name'=>$dependent['dependent_name'],
                            'dependent_gender'=>$dependent['dependent_gender'],
                            'bdate'=>$dependent['bdate'],
                            'relation'=>$dependent['relation'],
                        ];
                        if(!empty($dependent['id'])){
                            $employee->dependents()->where('id',$dependent['id'])->update($data_dependent);
                        }else{
                            $employee->dependents()->create($data_dependent);
                        }
                   }
                }
                
                if($employee_request_approval_deleted_dependents_data){
                    foreach($employee_request_approval_deleted_dependents_data as $deleted_dependent){
                        if(isset($deleted_dependent['id'])){
                            $employee->dependents()->where('id',$deleted_dependent['id'])->delete();
                        }
                   }
                }


                

                //Dependents Attachment
                if($employee_request_approval_dependents_attachment){
                    foreach($employee_request_approval_dependents_attachment as $attachment){
                        //Move file from temp to folder
                        $file = Storage::disk('public')->get('dependents_attachments/temps/' . $attachment);
                        if($file){
                            Storage::disk('public')->put('dependents_attachments/' . $attachment , $file); 
                        }

                        $dependent_attachment_data =  [];
                        $dependent_attachment_data['employee_id'] = $employee_request->employee_id;
                        $dependent_attachment_data['file'] = $attachment;

                        DependentAttachment::create($dependent_attachment_data);
                    }
                }

                //Delete Dependents Attachment
                if($employee_request_approval_deleted_dependents_attachment){
                    foreach($employee_request_approval_deleted_dependents_attachment as $deleted_dependent_attachment){
                        if(isset($deleted_dependent_attachment['id'])){
                            $employee->dependents_attachments()->where('id',$deleted_dependent_attachment['id'])->delete();
                        }
                   }
                }
                
                if($employee->update($approved_data)){
                    if($employee_request->update(['status'=>$request->get('status')])){

                        //Verification
                
                        $verification  = EmployeeDetailVerification::where('employee_id',$employee->id)->first();
                        $data = [];
                        $data['employee_id'] = $employee->id;
                        $data['verification'] = '1';
                        if(!empty($verification)){
                            EmployeeDetailVerification::where('id', $verification->id)->update($data);
                        }else{
                            EmployeeDetailVerification::create($data);
                        }
                        
                        DB::commit();
                        return EmployeeApprovalRequest::with('employee')->where('id',$employee_request->id)->first();
                    }
                }else{
                    return EmployeeApprovalRequest::with('employee')->where('id',$employee_request->id)->first();
                }
            }else{
                //Disapproved
                if($employee_request->update(['status'=>$request->get('status')])){
                    DB::commit();
                    return EmployeeApprovalRequest::with('employee')->where('id',$employee_request->id)->first();
                }
            }    
            

        }catch (Exception $e) {
            DB::rollBack();
            return $employee_request;
        }
    }

    public function viewUserProfile(Employee $employee){
        return view('view_user_profile')->with('employee_id',$employee->id);
    }

    public function viewUserProfileData(Employee $employee){
        return Employee::with('companies','departments','locations','verification')->where('id', $employee->id)->first();
    }

    public function getRfidNumber(){

        $client = new Client();

        $response = $client->request('GET', 'http://10.96.4.132/vdi_report_monitoring_portal/public/api/rfid_number');

        $response = json_decode($response->getBody(), true);

        return $response;

    }

    public function saveRFID(Request $request){

        $request->validate([
            'rfid_26' => 'required|unique:employees,rfid_26,' . $request->id,
            'rfid_64' => 'required|unique:employees,rfid_64,' . $request->id
        ]);


        $employee = Employee::select('id','id_number','series_number','first_name','last_name','rfid_26','rfid_64','door_id_number')->where('id',$request->id)->first();
        if($employee){
            $data = [];
            $data['rfid_26'] = $request->rfid_26;
            $data['rfid_64'] = $request->rfid_64;
            $data['door_id_number'] = $request->door_id_number;
            if($employee->update($data)){
                return  Employee::select('id','id_number','series_number','first_name','last_name','rfid_26','rfid_64','door_id_number')->where('id',$request->id)->first();
            }   
        }else{
            return $employee;
        }
    }

    public function scansRFID(){
        $client = new Client();

        $response = $client->request('GET', 'http://10.96.4.132/vdi_report_monitoring_portal/public/api/scan-rfids');

        $response = json_decode($response->getBody(), true);

        return $response;
    }

    public function getYearToDateHeadcount(){

        $year = date('Y');

        $all_employees = Employee::select('id','date_hired')->whereYear('date_hired',$year)->count();
        $jan = Employee::select('id','date_hired')->whereMonth('date_hired','01')->whereYear('date_hired',$year)->where('status','Active')->count();
        $feb = Employee::select('id','date_hired')->whereMonth('date_hired','02')->whereYear('date_hired',$year)->where('status','Active')->count();
        $mar = Employee::select('id','date_hired')->whereMonth('date_hired','03')->whereYear('date_hired',$year)->where('status','Active')->count();
        $apr = Employee::select('id','date_hired')->whereMonth('date_hired','04')->whereYear('date_hired',$year)->where('status','Active')->count();
        $may = Employee::select('id','date_hired')->whereMonth('date_hired','05')->whereYear('date_hired',$year)->where('status','Active')->count();
        $jun = Employee::select('id','date_hired')->whereMonth('date_hired','06')->whereYear('date_hired',$year)->where('status','Active')->count();
        $jul = Employee::select('id','date_hired')->whereMonth('date_hired','07')->whereYear('date_hired',$year)->where('status','Active')->count();
        $aug = Employee::select('id','date_hired')->whereMonth('date_hired','08')->whereYear('date_hired',$year)->where('status','Active')->count();
        $sep = Employee::select('id','date_hired')->whereMonth('date_hired','09')->whereYear('date_hired',$year)->where('status','Active')->count();
        $oct = Employee::select('id','date_hired')->whereMonth('date_hired','10')->whereYear('date_hired',$year)->where('status','Active')->count();
        $nov = Employee::select('id','date_hired')->whereMonth('date_hired','11')->whereYear('date_hired',$year)->where('status','Active')->count();
        $dec = Employee::select('id','date_hired')->whereMonth('date_hired','12')->whereYear('date_hired',$year)->where('status','Active')->count();

        $datas = [$jan,$feb,$mar,$apr,$may,$jun,$jul,$aug,$sep,$oct,$nov,$dec];

        return $datas;
    }

    public function getEmployeeAgeCount(){
       
        $all_employees = Employee::select('id','birthdate','status')->where('status','Active')->get();

        $get_age = [
            '21_below' => 0,
            '21_30' => 0,
            '31_40' => 0,
            '41_50' => 0,
            '51_60' => 0,
            'none' => 0,
        ];

        $today = date("Y-m-d");

        foreach($all_employees as $employee){
            $birthdate =  $employee['birthdate'];

            $data = [];
            $data['employee_id'] =  $employee['id'];
            if($birthdate != '0000-00-00'){
                $diff = date_diff(date_create($birthdate), date_create($today));
                $age = $diff->format('%y');
                $data['age'] =  $age;
            } else{
                $data['age'] =  "";
            }  

            if($data['age']){   
                if($data['age'] < 21){
                    $get_age['21_below'] += 1;
                }else if($data['age'] >= 21 && $data['age'] <= 30){
                    $get_age['21_30'] += 1;
                }else if($data['age'] >= 31 && $data['age'] <= 40){
                    $get_age['31_40'] += 1;
                }else if($data['age'] >= 41 && $data['age'] <= 50){
                    $get_age['41_50'] += 1;
                }else if($data['age'] >= 51 && $data['age'] <= 60){
                    $get_age['51_60'] += 1;
                }
            }else{
                $get_age['none'] += 1;
            }
        }

        $datas = [$get_age['21_below'],$get_age['21_30'],$get_age['31_40'],$get_age['41_50'],$get_age['51_60'],$get_age['none']];

        return $datas;
    }

    public function getEmployeeRegionCount(){
        
        $all_employees = Employee::select('id','area','status')->where('status','Active')->get();

        $get_region = [
            'luzon' => 0,
            'visayas' => 0,
            'mindanao' => 0,
            'none' => 0,
        ];

        foreach($all_employees as $employee){
            if($employee['area'] == 'LUZON' || $employee['area'] == 'luzon'){
                $get_region['luzon'] += 1;
            }else if($employee['area'] == 'VISAYAS' || $employee['area'] == 'visayas'){
                $get_region['visayas'] += 1;
            }else if($employee['area'] == 'MINDANAO' || $employee['area'] == 'mindanao'){
                $get_region['mindanao'] += 1;
            }else{
                $get_region['none'] += 1;
            }
        }

        $datas = [$get_region['luzon'],$get_region['visayas'],$get_region['mindanao'],$get_region['none']];

        return $datas;
    }

    public function getEmployeeGenderCount(){
        $all_employees = Employee::select('id','gender','status')->where('status','Active')->get();

        $get_gender = [
            'male' => 0,
            'female' => 0,
            'none' => 0,
        ];

        foreach($all_employees as $employee){
            if($employee['gender'] == 'MALE'){
                $get_gender['male'] += 1;
            }else if($employee['gender'] == 'FEMALE'){
                $get_gender['female'] += 1;
            }else{
                $get_gender['none'] += 1;
            }
        }

        $datas = [$get_gender['male'],$get_gender['female'],$get_gender['none']];

        return $datas;
    }

    public function getEmployeeMaritaStatusCount(){

        $all_employees = Employee::select('id','marital_status','status')->where('status','Active')->get();

        $get_marital_status = [
            'single' => 0,
            'married' => 0,
            'widow' => 0,
            'none' => 0
        ];

        foreach($all_employees as $employee){
            if($employee['marital_status'] == 'SINGLE'){
                $get_marital_status['single'] += 1;
            }else if($employee['marital_status'] == 'MARRIED'){
                $get_marital_status['married'] += 1;
            }else if($employee['marital_status'] == 'WIDOW'){
                $get_marital_status['widow'] += 1;
            }else{
                $get_marital_status['none'] += 1;
            }
        }

        $datas = [$get_marital_status['single'],$get_marital_status['married'],$get_marital_status['widow'],$get_marital_status['none']];

        return $datas;
    }
    

    public function getEmployeeClusterCount(){

        
        $clusters = Cluster::all();
        
        $datas = [];
        $x = 0;
        foreach($clusters as $key => $cluster){
            $cluster_count = Employee::select('id','cluster','status')->where('cluster',$cluster['name'])->where('status','Active')->count();
            
            $datas[$key]['name'] = mb_strimwidth($cluster['name'], 0, 10, "...");
            $datas[$key]['count'] = $cluster_count;

            $x++;
        }
        
        $cluster_non_count = Employee::select('id','cluster','status')->whereNull('cluster')->where('status','Active')->count();

        $datas[$x]['name'] = 'None';
        $datas[$x]['count'] = $cluster_non_count;

        return $datas;

    }

    public function getForRegularNotification(){

        $all_employees = Employee::select('id','first_name','last_name','date_hired','position','classification','status')->with('companies')->where('classification','Probationary')->where('status','Active')->orderBy('date_hired','DESC')->get();
        
        if($all_employees){
            $data = [];
            $today = date('Y-m-d');
            foreach($all_employees as $k => $employee){
                $date_hired = $employee['date_hired'];

                $months = "";
                if($date_hired != '0000-00-00' && $date_hired){
                    $diff = date_diff(date_create($date_hired), date_create($today));
                    $months = $diff->format('%m');
                }

                $immediate_superior = AssignHead::where('employee_id' , $employee['id'])->where('head_id','3')->first();
                $immediate_superior_details = Employee::select('id','first_name', 'last_name','user_id')->where('id',$immediate_superior['employee_head_id'])->where('status','Active')->first();
                $email = User::where('id',$immediate_superior_details['user_id'])->first();
        
                if($months){
                    if($months >= 3 && $months <= 5){
                        //Validate if with 3 - 5 months 
                        $data[$k]['id'] =  $employee['id'];
                        $data[$k]['name'] =  $employee['first_name'] . ' ' . $employee['last_name'];
                        $data[$k]['company'] =  $employee['companies'] ? $employee['companies'][0]['name'] : "";
                        $data[$k]['position'] =  $employee['position'];
                        $data[$k]['date_hired'] =  $employee['date_hired'];
                        $data[$k]['classification'] =  $employee['classification'];
                        $data[$k]['status'] =  $employee['status'];
                        $data[$k]['months'] =  $months;
                        $data[$k]['email_reciever'] =  $email ? $email['email'] : "";
                        $data[$k]['reciever_name'] =  $immediate_superior_details ? $immediate_superior_details['first_name'] . ' ' . $immediate_superior_details['last_name']  : "";

                        $date_of_regularization = date('Y-m-d', strtotime("+6 months", strtotime($employee['date_hired'])));
                        $data[$k]['date_of_regularization'] =  $date_of_regularization ? $date_of_regularization : "";
                    }
                }   
            }
        }

        return $data;
        
    }
}
