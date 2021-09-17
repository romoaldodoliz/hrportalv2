<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Employee;
use App\AssignHead;
use App\Dependent;
use App\Department;
use App\Company;
use App\Location;
use App\Address;
use App\User;
use App\PrintIdLog;
use App\EmployeeApprovalRequest;
use App\EmployeeDetailVerification;
use App\EmployeeTransfer;
use App\DependentAttachment;
use App\Api;
use App\EmployeeNpaRequest;
use App\EmployeeSalaryRecord;

use App\Employee201File;

use App\EmployeeTransferAttachment;

use App\QrCodeLog;

use App\RfidUser;

use App\HrRecieverHmoDependent;
use App\Mail\EmployeeHMODependentUpdate;
use App\Mail\EmployeeNpaNotification;

use Carbon\Carbon;
use Fpdf;
use File;
use Image;

use Auth;
use Hash;
use DB;

use QRCode;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;

class EmployeeController extends Controller
{
    public function index()
    {
        session(['header_text' => 'Employees']);
       
        return view('employees.index');
    }

    public function indexData()
    {
        $check_user = User::with('roles')->where('id',Auth::user()->id)->first();
       
        if($check_user['roles'][0]['name'] == 'Cluster Head' || $check_user['roles'][0]['name'] == 'BU Head' || $check_user['roles'][0]['name'] == 'Immediate Superior' ){
            $employee_head = Employee::select('id')->where('user_id',$check_user['id'])->first();
            $assign_heads = AssignHead::select('employee_id')->where('employee_head_id',$employee_head['id'])->get();

            $employee_ids = [];
            if($assign_heads){
                foreach($assign_heads as $head){
                    array_push($employee_ids , $head['employee_id']);
                }
            }
            return Employee::with('companies','departments','locations','immediate_superior','bu_head')
                        ->when($check_user['view_confidential'] != "YES" , function($q) {
                            $q->where('confidential','NO');
                        })
                        ->whereIn('id',$employee_ids)
                        ->where('status','Active')
                        ->orderBy('series_number','DESC')
                        ->get();
        }else{
            return Employee::with('companies','departments','locations','immediate_superior','bu_head')
                        ->when($check_user['view_confidential'] != "YES" , function($q) {
                            $q->where('confidential','NO');
                        })
                        ->where('status','Active')
                        ->orderBy('series_number','DESC')
                        ->get();
        }
       
        
    }

    public function getEmployee(Request $request){
        return Employee::with('companies','departments','locations','immediate_superior','bu_head')
                        ->where('id',$request->employee_id)
                        ->orderBy('series_number','DESC')
                        ->first();
    }

    public function employeeindexCount()
    {
        return Employee::where('status','Active')->count();
    }
    public function employeeInactiveCount()
    {
        return Employee::where('status','Inactive')->count();
    }
    public function employeeNewCount()
    {
        return Employee::with('companies','departments')->whereMonth('date_hired', Carbon::now()->month)->whereYear('date_hired', Carbon::now()->year)->get();
    }
    public function employeeUpdateCount()
    {
        //return Employee::whereMonth('updated_at', Carbon::now()->month)->count();
        return EmployeeDetailVerification::count();
    }

    public function employeeApprovers(Employee $employee){
        return AssignHead::where('employee_id',$employee->id)->orderBy('created_at', 'ASC')->get();
    }

    public function employeeDependents(Employee $employee){
        return Dependent::where('employee_id',$employee->id)->orderBy('created_at', 'ASC')->get();
    }

    public function employeeDependentsAttachments(Employee $employee){
        return DependentAttachment::where('employee_id',$employee->id)->orderBy('created_at', 'ASC')->get();
    }

    public function employee201FileAttachments(Employee $employee){
        return Employee201File::where('employee_id',$employee->id)->orderBy('created_at', 'ASC')->get();
    }

    public function employeeHeadApprovers(){
        return Employee::where('level','!=','RANK&FILE')->whereNotNull('level')->where('status','Active')->orderBy('last_name','ASC')->get();
    }

    public function employeeApprovalRequest(Employee $employee){
        return EmployeeApprovalRequest::where('employee_id',$employee->id)->orderBy('created_at','DESC')->get();
    }

    public function userProfileRequestPending(Employee $employee){
        return EmployeeApprovalRequest::where('employee_id',$employee->id)->where('status','Pending')->count();
    }

    public function add(){
        return view('employees.add');
    }

    public function store(Request $request){
        
        $data = $request->all();

        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'marital_status' => 'required',
            'gender' => 'required',
            'birthdate' => 'required',
            'company_list' => 'required',
            'department_list' => 'required',
            'location_list' => 'required',
            'sss_number' => 'required',
            'hdmf' => 'required',
            'phil_number' => 'required',
            'tax_number' => 'required',
            'tax_status' => 'required',
        ],[
            'company_list.required' => 'This field is required',
            'department_list.required' => 'This field is required',
            'location_list.required' => 'This field is required',
            'tax_status.required' => 'This field is required',
            'sss_number.required' => 'This field is required',
            'hdmf.required' => 'This field is required',
            'phil_number.required' => 'This field is required',
            'tax_number.required' => 'This field is required',
        ]);

        

        if(empty($request->division) && $request->division == 'null'){
            $data['division'] = null;
        }

        if(empty($request->date_hired)){
            $data['date_hired'] = null;
        }
        
        if(empty($request->date_regularized)){
            $data['date_regularized'] = null;
        }
        if(empty($request->date_resigned)){
            $data['date_resigned'] = null;
        }

        DB::beginTransaction();
        try {
            $employee_last = Employee::orderBy('created_at','DESC')->pluck('employee_number')->first();

            $user = new User;
            $user->password = Hash::make(strtolower($request->input('first_name')).".".strtolower($request->input('last_name')));
            $user->name =  $request->input('first_name') . " " . $request->input('last_name');

            //Get Company
            $get_company_details = Company::where('id',$data['company_list'])->first();
            $first_name = str_replace(' ', '', $request->input('first_name'));
            $last_name = str_replace(' ', '', $request->input('last_name'));
            if($get_company_details){
                if($get_company_details['domain']){
                    $email = strtolower($first_name) .".". strtolower($last_name) . "@" . $get_company_details['domain'];
                }else{
                    $email = strtolower($first_name) .".". strtolower($first_name) . "@lafilgroup.com";
                }
            }else{
                $email = strtolower($first_name) .".". strtolower($first_name) . "@lafilgroup.com";
            }
            
            
            $user->email = str_replace(' ', '', $email);
            $user->save();
            $user->attachRole('2');

            $employee = $user->employees()->create($request->all());
            $employee->employee_number = $employee_last + 1;

            if(isset($request->marital_status_attachment)){
                if($request->file('marital_status_attachment')){
                    $attachment = $request->file('marital_status_attachment');   
                    $filename = $employee->id . '_' . $attachment->getClientOriginalName();
                    $path = Storage::disk('public')->putFileAs('marital_attachments', $attachment , $filename);
                    $employee->marital_status_attachment =  $filename;
                }
            }

            if($employee->save()){

                if(isset($request->employee_image)){
                    if($request->file('employee_image')){
                        $attachment = $request->file('employee_image');   
                        $filename = $employee->id . '.png';
                        $path = Storage::disk('public')->putFileAs('id_image/employee_image', $attachment , $filename);
                    }
                }
        
                if(isset($request->employee_signature)){
                    if($request->file('employee_signature')){
                        $attachment = $request->file('employee_signature');   
                        $filename = $employee->id . '.png';
                        $path = Storage::disk('public')->putFileAs('id_image/employee_signature', $attachment , $filename);
                    }
                }
        
                $employee->companies()->sync( (array) $data['company_list']);
                $employee->departments()->sync( (array) $data['department_list']);
                $employee->locations()->sync( (array) $data['location_list']);

                 //Add Approvers
                if($data['head_approvers']){
                    $approvers = json_decode($data['head_approvers']);
                   foreach($approvers as $head_approver){
                        $data_head_approver = [
                            'employee_id'=>$employee->id,
                            'employee_head_id'=>$head_approver->employee_head_id,
                            'head_id'=>$head_approver->head_id
                        ];
                        $employee->assign_heads()->create($data_head_approver);
                   }
                }

                //Add Dependents
                if($data['dependents']){
                    $dependents = json_decode($data['dependents']);
                    foreach($dependents as $dependent){

                        $dependent_name = $dependent->dependent_name ? $dependent->dependent_name : null;
                        $dependent_gender = $dependent->dependent_gender ? $dependent->dependent_gender : null;
                        $bdate = $dependent->bdate ? $dependent->bdate : null;
                        $relation = $dependent->relation ? $dependent->relation : null;

                        $data_dependent = [
                            'employee_id'=>$employee->id,
                            'dependent_name'=>$dependent_name,
                            'dependent_gender'=>$dependent_gender,
                            'bdate'=>$bdate,
                            'relation'=>$relation,
                        ];
                        if($employee->id){
                            $employee->dependents()->create($data_dependent);       
                        }

                       
                   }
                }

                DB::commit();

                return Employee::with('companies','departments','locations')->where('id',$employee->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
            return $employee;
        }

    }

    public function update(Request $request, Employee $employee)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'marital_status' => 'required',
            'gender' => 'required',
            'birthdate' => 'required',
            'company_list' => 'required',
            'department_list' => 'required',
            'location_list' => 'required',
            // 'tax_status' => 'required',
        ],[
            'company_list.required' => 'This field is required',
            'department_list.required' => 'This field is required',
            'location_list.required' => 'This field is required',
            'tax_status.required' => 'This field is required',
        ]);

        $data = $request->all();
        $date_time = Carbon::now()->format('M_d_Y_h_m_s');

        if($request->date_hired == 'NaN-NaN-NaN'){
            $data['date_hired'] = null;
        }
        
        if(empty($request->date_regularized) || $request->date_regularized == 'NaN-NaN-NaN'){
            $data['date_regularized'] = null;
        }
        if(empty($request->date_resigned) || $request->date_resigned == 'NaN-NaN-NaN'){
            $data['date_resigned'] = null;
        }
  
        if(isset($request->employee_image)){
            $delete = Storage::disk('public')->delete('id_image/employee_image/'.$employee->id . '.png');
            if($request->file('employee_image')){
                $attachment = $request->file('employee_image');   
                $filename = $employee->id . '.png';
                $path = Storage::disk('public')->putFileAs('id_image/employee_image', $attachment , $filename);
            }
        }

        if(isset($request->employee_signature)){
            $delete = Storage::disk('public')->delete('id_image/employee_signature/'.$employee->id . '.png');
            if($request->file('employee_signature')){
                $attachment = $request->file('employee_signature');   
                $filename = $employee->id . '.png';
                $path = Storage::disk('public')->putFileAs('id_image/employee_signature', $attachment , $filename);
            }
        }

        if(isset($request->marital_status_attachment)){
            if($request->file('marital_status_attachment')){
                $attachment = $request->file('marital_status_attachment');   
                $filename = $employee->id . '_' . $attachment->getClientOriginalName();
                $path = Storage::disk('public')->putFileAs('marital_attachments', $attachment , $filename);
                $data['marital_status_attachment'] =  $filename;
            }
        }

        if(isset($request->monthly_basic_salary)){
            $data['monthly_basic_salary'] = Crypt::encryptString($request->monthly_basic_salary);
        }
        

        if($data['generate_id_number'] == 'YES'){
            //Generate New Employee Number
            $new_id_number = '';
            $new_series_number = '';
            $find_last = Employee::select('series_number','date_hired')->whereNotNull('id_number')->orderBy('series_number','DESC')->first();             
            if($find_last){
                $series_number = $find_last['series_number'];
                $new_company = Company::where('id',$data['company_list'])->first();
                $get_year = $data['date_hired'] ? date('y',strtotime($data['date_hired'])) . '00' : 'XXXX';
                $year_number = str_pad($get_year,2,'0',STR_PAD_LEFT);
                $new_series_number = $series_number + 1;
                $new_id_number = $new_company['company_code'] . '-' . str_pad($new_series_number, 5, '0', STR_PAD_LEFT)  . '-' . $year_number;
            }
            $data['id_number'] = $new_id_number;
            $data['series_number'] = str_pad($new_series_number, 5, '0', STR_PAD_LEFT);
        }
        
        DB::beginTransaction();
        try {

            if($employee->update($data)){
                $employee->companies()->sync( (array) $data['company_list']);
                $employee->departments()->sync( (array) $data['department_list']);
                $employee->locations()->sync( (array) $data['location_list']);

                //Add Approvers
                if($data['head_approvers']){
                    $approvers = json_decode($data['head_approvers']);
                   foreach($approvers as $head_approver){
                        $data_head_approver = [
                            'employee_id'=>$employee->id,
                            'employee_head_id'=>$head_approver->employee_head_id,
                            'head_id'=>$head_approver->head_id
                        ];
                        if(!empty($head_approver->id)){
                            $employee->assign_heads()->where('id',$head_approver->id)->update($data_head_approver);
                        }else{
                            $employee->assign_heads()->create($data_head_approver);
                        }
                   }
                }
                
                //Delete Approvers
                if(!empty($data['deleted_approvers'])){
                   $deleted_approvers = json_decode($data['deleted_approvers']);
                   foreach($deleted_approvers as $deleted_approver){
                        if(isset($deleted_approver->id)){
                            $employee->assign_heads()->where('id',$deleted_approver->id)->delete();
                        }
                   }
                }

                //Add Dependents
                if($data['dependents']){
                    $dependents = json_decode($data['dependents']);
                   foreach($dependents as $dependent){

                        $dependent_name = $dependent->dependent_name ? $dependent->dependent_name : null;
                        $first_name = $dependent->first_name ? $dependent->first_name : null;
                        $last_name = $dependent->last_name ? $dependent->last_name : null;
                        $middle_name = $dependent->middle_name ? $dependent->middle_name : null;
                        $dependent_gender = $dependent->dependent_gender ? $dependent->dependent_gender : null;
                        $bdate = $dependent->bdate ? $dependent->bdate : null;
                        $relation = $dependent->relation ? $dependent->relation : null;
                        $dependent_status = $dependent->dependent_status ? $dependent->dependent_status : null;
                        $civil_status = $dependent->civil_status ? $dependent->civil_status : null;
                        $hmo_enrollment = $dependent->hmo_enrollment ? $dependent->hmo_enrollment : null;

                        $data_dependent = [
                            'employee_id'=>$employee->id,
                            'dependent_name'=>$dependent_name,
                            'first_name'=>$first_name,
                            'last_name'=>$last_name,
                            'middle_name'=>$middle_name,
                            'dependent_gender'=>$dependent_gender,
                            'bdate'=>$bdate,
                            'relation'=>$relation,
                            'dependent_status'=>$dependent_status,
                            'civil_status'=>$civil_status,
                            'hmo_enrollment'=>$hmo_enrollment,
                        ];

                        if(!empty($dependent->id)){
                            $employee->dependents()->where('id',$dependent->id)->update($data_dependent);
                        }else{
                            $employee->dependents()->create($data_dependent);
                        }
                   }
                }
                
                //Delete Dependents
                if(!empty($data['deleted_dependents'])){
                   $deleted_dependents = json_decode($data['deleted_dependents']);
                   foreach($deleted_dependents as $deleted_dependent){
                        if(isset($deleted_dependent->id)){
                            $employee->dependents()->where('id',$deleted_dependent->id)->delete();
                        }
                   }
                }

                //dependent_attachments
                $attachments = $request->file('dependent_attachments');   
                $new_dependent_attachments = [];

                if(!empty($attachments)){
                    foreach($attachments as $attachment){
                        $filename = $employee->id . '_' . $date_time . '_' . $attachment->getClientOriginalName();
                        $path = Storage::disk('public')->putFileAs('dependents_attachments', $attachment,$filename);
                        $new_dependent_attachments[] = $filename; 
                    }    
                }
                
                //Delete Dependents Attachment
                if($data['deleted_dependent_attachments']){
                    $deleted_dependent_attachments = json_decode($data['deleted_dependent_attachments']);
                    foreach($deleted_dependent_attachments as $deleted_dependent_attachment){
                        if(isset($deleted_dependent_attachment->id)){
                            $employee->dependents_attachments()->where('id',$deleted_dependent_attachment->id)->delete();
                        }
                   }
                }

                 //Dependents Attachment
                if($new_dependent_attachments){
                    foreach($new_dependent_attachments as $attachment){
                        $dependent_attachment_data =  [];
                        $dependent_attachment_data['employee_id'] = $employee->id;
                        $dependent_attachment_data['file'] = $attachment;
                        $employee->dependents_attachments()->create($dependent_attachment_data);
                    }
                }

                //Employee 201 Files : Save to Folder
                $employee_201_attachments = $request->file('documents_201_files_attachments');   
                $new_employee_201_attachments = [];

                if(!empty($employee_201_attachments)){
                    foreach($employee_201_attachments as $attachment){
                        $filename = $attachment->getClientOriginalName();
                        $path = Storage::disk('public')->putFileAs('employee_201_files/'.$employee->id, $attachment,$filename);
                        $new_employee_201_attachments[] = $filename; 
                    }    
                }
                
                //Delete 201 File Attachment
                if($data['deleted_documents_201_files_attachments']){
                    $deleted_employee_201_attachments = json_decode($data['deleted_documents_201_files_attachments']);
                    foreach($deleted_employee_201_attachments as $deleted_attachment){
                        if(isset($deleted_attachment->id)){
                            $document_201_file = $employee->employee_201_files()->where('id',$deleted_attachment->id)->delete();
                        }
                   }
                }

                 //Save 201 File Attachment
                if($new_employee_201_attachments){
                    foreach($new_employee_201_attachments as $attachment){
                        $employee_201_attachment_data =  [];
                        $employee_201_attachment_data['employee_id'] = $employee->id;
                        $employee_201_attachment_data['file'] = $attachment;
                        $employee->employee_201_files()->create($employee_201_attachment_data);
                    }
                }

                //Change to First Level Approver
                if($data['new_approver_under']){
                    //Find Current approver 
                    $current_approver = AssignHead::with('employee_info')
                                                            ->where('employee_head_id',$employee->id)
                                                            ->where('head_id','3')
                                                            ->whereHas(
                                                                'employee_info',function($q) {
                                                                    $q->where('status','Active');
                                                                }
                                                            )
                                                            ->update(['employee_head_id'=>$data['new_approver_under']]);
                    
                }

                DB::commit();
                return Employee::with('companies','departments','locations')->where('id',$employee->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
            return $employee;
        }
    }


    public function updateEmployeeUserProfileVerification(Request $request, Employee $employee){
       
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'nick_name' => 'required',
            'marital_status' => 'required',
            'gender' => 'required',
            'birthdate' => 'required',
        ]);

        $data = $request->all();

        if(isset($request->marital_status_attachment)){
            if($request->file('marital_status_attachment')){
                $attachment = $request->file('marital_status_attachment');   
                $filename = $employee->id . '_' . $attachment->getClientOriginalName();
                $path = Storage::disk('public')->putFileAs('marital_attachments', $attachment , $filename);
                $data['marital_status_attachment'] =  $filename;
            }
        }

        DB::beginTransaction();
        try {

            if($employee->update($data)){

                //Add Dependents
                if($data['dependents']){
                    $dependents = json_decode($data['dependents']);
                   foreach($dependents as $dependent){

                        $dependent_name = $dependent->dependent_name ? $dependent->dependent_name : null;
                        $dependent_gender = $dependent->dependent_gender ? $dependent->dependent_gender : null;
                        $bdate = $dependent->bdate ? $dependent->bdate : null;
                        $relation = $dependent->relation ? $dependent->relation : null;

                        $data_dependent = [
                            'employee_id'=>$employee->id,
                            'dependent_name'=>$dependent_name,
                            'dependent_gender'=>$dependent_gender,
                            'bdate'=>$bdate,
                            'relation'=>$relation,
                        ];

                        if(!empty($dependent->id)){
                            $employee->dependents()->where('id',$dependent->id)->update($data_dependent);
                        }else{
                            $employee->dependents()->create($data_dependent);
                        }
                   }
                }
                
                //Delete Dependents
                if(!empty($data['deleted_dependents'])){
                   $deleted_dependents = json_decode($data['deleted_dependents']);
                   foreach($deleted_dependents as $deleted_dependent){
                        if(isset($deleted_dependent->id)){
                            $employee->dependents()->where('id',$deleted_dependent->id)->delete();
                        }
                   }
                }

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
                return Employee::with('companies','departments','locations','verification')->where('id',$employee->id)->first();
            }else{
                return Employee::with('companies','departments','locations','verification')->where('id',$employee->id)->first(); 
            }
        }catch (Exception $e) {
            DB::rollBack();
            return $employee;
        }

    }

    public function storeEmployeeUserProfile(Request $request, Employee $employee){
        
        $data = $request->all();

        $date_time = Carbon::now()->format('M_d_Y_h_m_s');

        $this->validate($request, [
            'last_name' => 'required',
            'marital_status' => 'required',
            'gender' => 'required',
        ]);

        $original_employee_dependents = Dependent::where('employee_id',$employee->id)->orderBy('created_at', 'ASC')->get();

        if(empty($request->date_hired)){
            $data['date_hired'] = null;
        }
        
        if(empty($request->date_regularized)){
            $data['date_regularized'] = null;
        }
        if(empty($request->date_resigned)){
            $data['date_resigned'] = null;
        }

        
        if(isset($request->marital_status_attachment)){
            if($request->file('marital_status_attachment')){
                $attachment = $request->file('marital_status_attachment');   
                $filename = $employee->id . '_' . $date_time . '_' . $attachment->getClientOriginalName();
                $path = Storage::disk('public')->putFileAs('marital_attachments/temps/', $attachment , $filename);
                $employee_data['marital_status_attachment'] =  $filename;
            }
        }

        //dependent_attachments
        $attachments = $request->file('dependent_attachments');   
        $new_dependent_attachments = [];

        if(!empty($attachments)){
            foreach($attachments as $attachment){
                $filename = $employee->id . '_' . $date_time . '_' . $attachment->getClientOriginalName();
                $path = Storage::disk('public')->putFileAs('dependents_attachments/temps', $attachment,$filename);
                $new_dependent_attachments[] = $filename; 
            }    
        }
    
        //For approval data 
        $employee_data['nick_name'] = $request->nick_name ? $request->nick_name : $employee->nick_name;
        $employee_data['middle_name'] = $request->middle_name ? $request->middle_name : $employee->middle_name;
        $employee_data['middle_initial'] = $request->middle_initial ? $request->middle_initial : $employee->middle_initial;
        $employee_data['last_name'] = $request->last_name ? $request->last_name : $employee->last_name;
        $employee_data['marital_status'] = $request->marital_status;
        $employee_data['current_address'] = $request->current_address;
        $employee_data['permanent_address'] = $request->permanent_address;
        $employee_data['phone_number'] = $request->phone_number;
        $employee_data['mobile_number'] = $request->mobile_number;
        $employee_data['contact_person'] = $request->contact_person;
        $employee_data['contact_relation'] = $request->contact_relation;
        $employee_data['contact_number'] = $request->contact_number;
        $employee_data['dependents'] = $request->dependents;
        $employee_data['deleted_dependents'] = $request->deleted_dependents;

        $employee_data['dependent_attachments'] = $new_dependent_attachments;
        $employee_data['deleted_dependent_attachments'] = $request->deleted_dependent_attachments;

        //Send Email to HR of Dependents Update -----------------------------------------------------------------------------------
        $send_email_dependent_update = 0;
        if($request->dependents){
            $dependents_requests = json_decode($request->dependents);
           
            foreach($dependents_requests as $item){
                $dependent_exist = Dependent::where('employee_id',$employee->id)
                                                ->where('dependent_name',$item->dependent_name)
                                                ->where('relation',$item->relation)
                                                ->where('bdate',$item->bdate)
                                                ->where('dependent_gender',$item->dependent_gender)
                                                ->where('dependent_status',$item->dependent_status)
                                                ->first();
                if(empty($dependent_exist)){
                    $send_email_dependent_update = 1;
                }
            }

            if($send_email_dependent_update == 1){
                $email_reciever = HrRecieverHmoDependent::select('email')->where('id','0')->first();
                $email_reciever_cc = HrRecieverHmoDependent::select('email')->where('id','!=','0')->get();
                $data = [
                    'employee_name'=>$employee->first_name . ' ' . $employee->last_name,
                    'position'=>$employee->position,
                    'dependents'=>$dependents_requests,
                    'deleted_dependents'=> $request->deleted_dependents ? json_decode($request->deleted_dependents) : ""
                ];
                $send_update = Mail::to($email_reciever)->cc($email_reciever_cc)->send(new EmployeeHMODependentUpdate($data));
            }
        }
        if($request->deleted_dependents){
            if($send_email_dependent_update == 0){
                $email_reciever = HrRecieverHmoDependent::select('email')->where('id','0')->first();
                $email_reciever_cc = HrRecieverHmoDependent::select('email')->where('id','!=','0')->get();
                $data = [
                    'employee_name'=>$employee->first_name . ' ' . $employee->last_name,
                    'position'=>$employee->position,
                    'dependents'=>$dependents_requests,
                    'deleted_dependents'=> $request->deleted_dependents ? json_decode($request->deleted_dependents) : ""
                ];
                $send_update = Mail::to($email_reciever)->cc($email_reciever_cc)->send(new EmployeeHMODependentUpdate($data));
            }   
        }
        //---------------------------------------------------------------------------------------------------------------------------

        $employee_data['gender'] = $request->gender;
        $employee_data['sss_number'] = $request->sss_number;
        $employee_data['phil_number'] = $request->phil_number;
        $employee_data['tax_number'] = $request->tax_number;
        $employee_data['hdmf'] = $request->hdmf;

        $employee_data['remarks'] = $request->remarks;

        //Original Employee
        $original_employee_data['nick_name'] = $employee->nick_name ? $employee->nick_name : $employee->nick_name;
        $original_employee_data['middle_name'] = $employee->middle_name ? $employee->middle_name : $employee->middle_name;
        $original_employee_data['middle_initial'] = $employee->middle_initial ? $employee->middle_initial : $employee->middle_initial;
        $original_employee_data['last_name'] = $employee->last_name ? $employee->last_name : $employee->last_name;
        $original_employee_data['marital_status'] = $employee->marital_status;
        $original_employee_data['current_address'] = $employee->current_address;
        $original_employee_data['permanent_address'] = $employee->permanent_address;
        $original_employee_data['phone_number'] = $employee->phone_number;
        $original_employee_data['mobile_number'] = $employee->mobile_number;
        $original_employee_data['contact_person'] = $employee->contact_person;
        $original_employee_data['contact_person'] = $employee->contact_person;
        $original_employee_data['contact_relation'] = $employee->contact_relation;
        $original_employee_data['contact_number'] = $employee->contact_number;
        $original_employee_data['dependents'] = $original_employee_dependents;

        $original_employee_data['gender'] = $employee->gender;
        $original_employee_data['sss_number'] = $employee->sss_number;
        $original_employee_data['phil_number'] = $employee->phil_number;
        $original_employee_data['tax_number'] = $employee->tax_number;
        $original_employee_data['hdmf'] = $employee->hdmf;
        
        // Employee Approval Data
        $employee_approval_data['employee_id'] = $employee->id;
        $employee_approval_data['employee_data'] = json_encode($employee_data);
        $employee_approval_data['original_employee_data'] = json_encode($original_employee_data);

        $check_request = EmployeeApprovalRequest::where('employee_id', '=', $employee->id)->where('status' , '=', 'Pending')->first();
 
        if($check_request){
            $check_request->update($employee_approval_data);
        }else{
            EmployeeApprovalRequest::create($employee_approval_data);
        }

        return Employee::with('companies','departments','locations','verification')->where('id',$employee->id)->first();

    }

    public function destroyEmployeeUserProfile(EmployeeApprovalRequest $employee){
        EmployeeApprovalRequest::where('id',$employee->id)->delete();
        return EmployeeApprovalRequest::where('employee_id',$employee->employee_id)->orderBy('created_at','DESC')->get();
    }

    public function employee_id_index(){
       return view('employee_ids.index');
    }

    public function employeeFilter(Request $request){

        // $request->all();
        $company = $request->company;
        $department = $request->department;
        $location = $request->location;
        $employee_status = $request->employee_status;
        
        $check_user = User::where('id',Auth::user()->id)->first();

        $employee = Employee::with('companies','departments','locations','print_id_logs','verification','immediate_superior','bu_head')
                    ->when(!empty($request->company), function($q) use($company) {
                        $q->whereHas('companies', function ($w) use($company)  {
                            $w->where('id', '=', $company);
                        });
                    })
                    ->when(!empty($request->department), function($q) use($department) {
                        $q->whereHas('departments', function ($w) use($department)  {
                            $w->where('id', '=', $department);
                        });
                    })
                    ->when(!empty($request->location), function($q) use($location) {
                        $q->whereHas('locations', function ($w) use($location)  {
                            $w->where('id', '=', $location);
                        });
                    })
                    ->when(!empty($request->employee_status), function($q) use($employee_status) {
                       $q->where('status', '=', $employee_status);
                    })
                    ->when(empty($request->employee_status), function($q){
                       $q->where('status', '=', 'Active');
                    })
                    ->when($check_user['view_confidential'] != "YES" , function($q) {
                        $q->where('confidential','NO');
                    })
                    ->orderBy('series_number','DESC')
                    ->get();
        return $employee;
    }

    public function getHREmployees(){
        
        $hr_employee = Employee::with('departments')
                    ->whereHas('departments', function ($w) {
                            $w->where('id', '=', '20');
                    })
                    ->where('status','Active')
                    ->orderBy('last_name' , 'ASC')
                    ->get();

        return $hr_employee;
    }

    public function getBUHead(){
        
        $bu_heads = AssignHead::select('employee_head_id')->distinct()->where('head_id','4')->get();

        $bu_head_ids = [];
        
        foreach($bu_heads as $bu_head){
            array_push($bu_head_ids, $bu_head['employee_head_id']);
        }
        
        $bu_heads_employees = Employee::whereIn('id',$bu_head_ids)
                    ->where('status','Active')
                    ->orderBy('last_name' , 'ASC')
                    ->get();

        return $bu_heads_employees;
    }

    public function employeeIdIndex(){
        session(['header_text' => 'Employees']);

        $check_user = User::where('id',Auth::user()->id)->first();

        $employee = Employee::select('id','id_number','series_number','first_name','last_name','rfid_26','rfid_64','door_id_number')->with('companies','departments','locations','print_id_logs','verification')
                            ->when($check_user['view_confidential'] != "YES" , function($q) {
                                $q->where('confidential','NO');
                            })
                            ->where('status','Active')
                            ->orderBy('series_number','DESC')
                            ->get();
        return $employee;
    }

    public function employeeFilterID(Request $request){

        // $request->all();
        $company = $request->company;
        $department = $request->department;
        $location = $request->location;
        $employee_status = $request->employee_status;
        
        $check_user = User::where('id',Auth::user()->id)->first();

        $employee = Employee::select('id','id_number','series_number','first_name','last_name','rfid_26','rfid_64','door_id_number')->with('companies','departments','locations','print_id_logs','verification')
                    ->when(!empty($request->company), function($q) use($company) {
                        $q->whereHas('companies', function ($w) use($company)  {
                            $w->where('id', '=', $company);
                        });
                    })
                    ->when(!empty($request->department), function($q) use($department) {
                        $q->whereHas('departments', function ($w) use($department)  {
                            $w->where('id', '=', $department);
                        });
                    })
                    ->when(!empty($request->location), function($q) use($location) {
                        $q->whereHas('locations', function ($w) use($location)  {
                            $w->where('id', '=', $location);
                        });
                    })
                    ->when(!empty($request->employee_status), function($q) use($employee_status) {
                       $q->where('status', '=', $employee_status);
                    })
                    ->when($check_user['view_confidential'] != "YES" , function($q) {
                        $q->where('confidential','NO');
                    })
                    ->orderBy('series_number','DESC')
                    ->get();
        return $employee;
    }

    public function print_id(Employee $employee){
        
        $employee =  Employee::select('id','id_number','middle_initial','name_suffix','last_name','first_name','nick_name','classification')->with('departments','locations')->where('id',$employee->id)->first();

        if($employee['nick_name'] == '' || $employee['nick_name'] == '-'){
            $nick_name = strtolower($employee['first_name']);
        }else{
            $nick_name = strtolower($employee['nick_name']);
        }
        
        $first_name = utf8_decode(strtolower($employee['first_name']));
        $convert_last_name = mb_strtolower($employee['last_name'],'UTF-8');
        $last_name_front = utf8_decode($convert_last_name);
        $last_name_back = utf8_decode($employee['last_name']);

        if($employee['middle_initial'] != '-' && !empty($employee['middle_initial'])){
            $last_middle_initial_back = $employee['middle_initial'] . '.';
        }else{
            $last_middle_initial_back = "";
        }

        if($employee['name_suffix'] != '-' && !empty($employee['name_suffix'])){
            $last_name_suffix_back = $employee['name_suffix'] . '.';
        }else{
            $last_name_suffix_back = "";
        }



        $department = $employee->departments ? strtolower($employee->departments[0]['name']) : "";
        
        $address = "";            
        if($employee->locations->first()->id){
            $location = Location::with('addresses')->where('id',$employee->locations->first()->id);
            $get_address = Address::where('id',$employee->locations->first()->address_id)->first();
            // $address = isset($location->addresses->first()->name) ? $location->addresses->first()->name : '';
            $address = $get_address ? $get_address['name'] : '';
        }

        Fpdf::AddPage("P", [53.98,85.60]);

        Fpdf::AddFont('Avenir-Bold','','AvenirNextCondensed-Bold.php');
        Fpdf::AddFont('Avenir-Regular','','AvenirNextCondensed-Regular.php');

        Fpdf::SetMargins(0,0,0,0);
        Fpdf::SetAutoPageBreak(false);
        
    
        //Front BG
        if (file_exists( 'storage/id_image/new_id_image/front.png')){
            Fpdf::Image(url("storage/id_image/new_id_image/front.png"), 0, 0, 53.98, 85.60,'PNG');
        }

        //Employee Image
        if (file_exists('storage/id_image/employee_image/' . $employee->id . '.png')){
        
            $image ='storage/id_image/employee_image/' . $employee->id . '.png';
           
            $destinationPath = storage_path('app/public/id_image/temp_employee_image');

            if(!File::isDirectory($destinationPath)){
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            
            
            $img = Image::make( ($image));
    
            $img->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'. $employee->id . '.png');
    
            if (file_exists('storage/id_image/temp_employee_image/' . $employee->id . '.png')){
                Fpdf::Image(url("storage/id_image/temp_employee_image/") .'/'. $employee->id.'.png', 9, 15, 36, 36,'PNG');
            }
        }

        $fullname_font = 15;
       
        $full_name = ucfirst($nick_name) . ' ' . ucfirst($last_name_front);

        Fpdf::SetFont('Avenir-Bold','',$fullname_font);
        Fpdf::SetXY(1.5,53);
        Fpdf::SetTextColor(3,119, 57);
        Fpdf::MultiCell(51,5, ucwords($full_name) ,0,'C');

        $current_y = Fpdf::gety();
        Fpdf::SetXY(0,$current_y);
        Fpdf::SetFont('Arial', 'B', 7);
        Fpdf::MultiCell(54,4, $employee->id_number ,0,'C');

        Fpdf::SetTextColor(0,0,0);
        Fpdf::SetXY(0,67);
        Fpdf::SetFont('Arial', 'B', 7);
        Fpdf::MultiCell(54,2.5, ucwords($department) ,0,'C');

        if($employee['classification'] == "Project"){
            Fpdf::SetTextColor(255,255,255);
            Fpdf::SetFillColor(0,130,55);
            Fpdf::SetXY(0,78);
            Fpdf::SetFont('Arial', 'B', 10);
            Fpdf::MultiCell(54,8, "PROJECT EMPLOYEE" ,0,'C',TRUE);
        }
        Fpdf::SetTextColor(0,0,0);
        //Back
        Fpdf::AddPage("P", [85.60, 53.98]);
        Fpdf::SetMargins(0,0,0,0);
        Fpdf::SetAutoPageBreak(false);

        //Back BG
        if (file_exists('storage/id_image/new_id_image/back.png')){
            Fpdf::Image(url("storage/id_image/new_id_image/back.png"), 0, 0, 53.98, 85.60,'PNG');
        }
      
        //Company Logo
        if (file_exists('storage/id_image/company/' . $employee['companies'][0]->id.'.png')){

            $signature = 'storage/id_image/company/' . $employee['companies'][0]->id . '.png';
            $destinationPath = storage_path('app/public/id_image/temp_company/');
    
            if(!File::isDirectory($destinationPath)){
                File::makeDirectory($destinationPath, 0777, true, true);
            }
    
            $img = Image::make($signature);
    
            $img->resize(500, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'. $employee['companies'][0]->id. '.png');
            
            if (file_exists('storage/id_image/temp_company/' . $employee['companies'][0]->id .'.png')){
                Fpdf::Image(url("storage/id_image/temp_company/") .'/'. $employee['companies'][0]->id.'.png', 16, 13, 23, 23,'PNG');
            }
            
        }

        //QR Code
        $id_number = $employee['id_number'];
        $qr_text = "https://myvisitors.lafilgroup.com:8671/calling_card/" . $id_number;
        if(file_exists(base_path().'/public/qr_employees/'.$employee['id'].'.png')){
            Fpdf::Image(base_path().'/public/qr_employees/'.$employee['id'].'.png', 2, 2, 15, 15,'PNG');
        }else{
            QRCode::text($qr_text)->setSize(40)->setMargin(2)->setOutfile(base_path().'/public/qr_employees/'.$employee['id'].'.png')->png();
            Fpdf::Image(base_path().'/public/qr_employees/'.$employee['id'].'.png', 2, 2, 15, 15,'PNG');
        }

        $full_name_back = strtoupper($first_name) . ' ' . $last_middle_initial_back . ' ' . strtoupper($last_name_back) . ' ' . $last_name_suffix_back;
        Fpdf::SetFont('Arial','B', 8);
        Fpdf::SetXY(0,37);
        Fpdf::MultiCell(54,3, strtoupper($full_name_back) ,0,'C');

        Fpdf::SetFont('Avenir-Regular','',8);
        Fpdf::SetXY(2.5,68);
        Fpdf::MultiCell(51,3, $address ,0,'C');


        Fpdf::Output(utf8_decode($employee->last_name) .'_' . utf8_decode($employee->first_name) . '_' . $employee->employee_number  . ".pdf", 'I');
        exit();

    }

    public function print_id_logs(Request $request){
        
        

        if($request->employee_id){
            $print_id_logs_data = [];
            $print_id_logs_data['employee_id'] = $request->employee_id;
            $print_id_logs_data['user_id'] = Auth::user()->id;
            $print_id_logs_data['remarks'] = 'Print on ' . date('Y-m-d') . ' at ' . date('h:m:s');

            PrintIdLog::create($print_id_logs_data);
        }

        $employee = Employee::select('id','id_number','series_number','first_name','last_name')->with('companies','departments','locations','print_id_logs','verification')->where('id',$request->employee_id)->first();
        return $employee;
    }

    public function transferEmployee(Request $request, Employee $employee){

        $employee_data = Employee::with('companies','departments','locations','assign_heads')->where('id',$employee->id)->first();

        $data = $request->all();
  
        $this->validate($request, [
            'position' => 'required',
            'date_hired' => 'required',
            'company_list' => 'required',
            'department_list' => 'required',
            'location_list' => 'required',
        ]);

        DB::beginTransaction();
        try {
            //Generate New Employee Number
            $new_id_number = '';
            $new_series_number = '';
            $find_last = Employee::select('series_number','date_hired')
                                    ->whereNotNull('id_number')
                                    ->orderBy('series_number','DESC')
                                    ->first();             
            if($find_last){
                $series_number = $find_last['series_number'];

                $new_company = Company::where('id',$data['company_list'])->first();

                $check_transfer_employee = EmployeeTransfer::where('employee_id',$employee_data['id'])->get();

                if(count($check_transfer_employee) > 0){
                    $first_year = $check_transfer_employee[0]['previous_date_hired'] ? date('y',strtotime($check_transfer_employee[0]['previous_date_hired'])) : 'XX';
                    $second_year = $data['date_hired'] ? date('y',strtotime($data['date_hired'])) : 'XX';
                    $year_number = str_pad($first_year,2,'0',STR_PAD_LEFT) . str_pad($second_year,2,'0',STR_PAD_LEFT);
                }else{
                    $first_year = $employee_data['date_hired'] ? date('y',strtotime($employee_data['date_hired'])) : 'XX';
                    $second_year = $data['date_hired'] ? date('y',strtotime($data['date_hired'])) : 'XX';
                    $year_number = str_pad($first_year,2,'0',STR_PAD_LEFT) . str_pad($second_year,2,'0',STR_PAD_LEFT);
                }
                $new_series_number = $series_number + 1;
                $new_id_number = $new_company['company_code'] . '-' . str_pad($new_series_number, 5, '0', STR_PAD_LEFT)  . '-' . $year_number;
                
            }
            
            $data['id_number'] = $new_id_number;
            $data['series_number'] = str_pad($new_series_number, 5, '0', STR_PAD_LEFT);


            if($employee->update($data)){
                $employee->companies()->sync( (array) $data['company_list']);
                $employee->departments()->sync( (array) $data['department_list']);
                $employee->locations()->sync( (array) $data['location_list']);

                //Delete Approvers
                if($employee_data['assign_heads']){
                    foreach($employee_data['assign_heads'] as $assign_head){
                        $employee->assign_heads()->where('id',$assign_head->id)->delete();
                    }
                }

                //Add Approvers
                if($data['head_approvers']){
                    $approvers = json_decode($data['head_approvers']);
                   foreach($approvers as $head_approver){
                        $data_head_approver = [
                            'employee_id'=>$employee_data['id'],
                            'employee_head_id'=>$head_approver->employee_head_id,
                            'head_id'=>$head_approver->head_id
                        ];
                        if(!empty($head_approver->id)){
                            $employee->assign_heads()->where('id',$head_approver->id)->update($data_head_approver);
                        }else{
                            $employee->assign_heads()->create($data_head_approver);
                        }
                   }
                }

                //Save Transfer Logs
                $transfer_logs_data = [];
                $transfer_logs_data['employee_id'] = $employee_data['id'];

                $assign_heads = AssignHead::where('employee_id',$employee->id)->get();
                //OLD
                $transfer_logs_data['previous_id_number'] = $employee_data['id_number'];
                $transfer_logs_data['previous_position'] = $employee_data['position'];
                $transfer_logs_data['previous_date_hired'] = $employee_data['date_hired'];
                $transfer_logs_data['previous_cluster'] = $employee_data['cluster'];
                $transfer_logs_data['previous_department'] = $employee_data['departments'] ? $employee_data['departments'][0]['id'] : "";
                $transfer_logs_data['previous_company'] = $employee_data['companies'] ? $employee_data['companies'][0]['id'] : "";
                $transfer_logs_data['previous_location'] = $employee_data['locations'] ? $employee_data['locations'][0]['id'] : "";
                $transfer_logs_data['previous_system_approvers'] = $employee_data['assign_heads'] ? json_encode($assign_heads) : "";

                //New
                $transfer_logs_data['new_id_number'] = $new_id_number;
                $transfer_logs_data['new_position'] = $data['position'];
                $transfer_logs_data['new_date_hired'] = $data['date_hired'];
                $transfer_logs_data['new_cluster'] = $data['cluster'];
                $transfer_logs_data['new_department'] =  $data['department_list'] ? $data['department_list'] : "";
                $transfer_logs_data['new_company'] = $data['company_list'] ? $data['company_list'] : "";
                $transfer_logs_data['new_location'] = $data['location_list'] ? $data['location_list'] : "";
                $transfer_logs_data['new_system_approvers'] = $data['head_approvers'] ? json_encode($data['head_approvers']) : "";

                $transfer_logs_data['transferred_by'] =  Auth::user()->id;

                if($employee_transfer = EmployeeTransfer::create($transfer_logs_data)){

                    //Supporting Documents
                    $transfer_supporting_documents = $request->file('transfer_supporting_documents');
                    if(!empty($transfer_supporting_documents)){
                        foreach($transfer_supporting_documents as $attachment){
                            $filename = $attachment->getClientOriginalName();
                            $path = Storage::disk('public')->putFileAs('employee_transfer_attachments/'.$employee->id, $attachment,$filename);

                            $supporting_document_data =  [];
                            $supporting_document_data['employee_transfer_id'] = $employee_transfer->id;
                            $supporting_document_data['employee_id'] = $employee->id;
                            $supporting_document_data['filename'] = $filename;
                            $supporting_document_data['path'] = $path;

                            EmployeeTransferAttachment::create($supporting_document_data);
                        }    
                    }
                }

                DB::commit();
                
                return Employee::with('companies','departments','locations')->where('id',$employee->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
            return $employee;
        }

        return Employee::with('companies','departments','locations')->where('id',$employee->id)->first();

    }

    public function transferEmployeeLogs(Employee $employee){
        return $transfer_employee_logs = EmployeeTransfer::with('new_company','new_department','new_location','previous_company','previous_department','previous_location','employee_transfer_attachments','employee')->where('employee_id',$employee->id)->orderBy('new_date_hired','ASC')->get();
    }

    public function pdfTransferEmployeeLogs(Employee $employee){
        
        $transfer_employee_logs = EmployeeTransfer::with('new_company','new_department','new_location','previous_company','previous_department','previous_location','employee_transfer_attachments','employee')->where('employee_id',$employee->id)->orderBy('new_date_hired','ASC')->get();
        if($transfer_employee_logs){
            foreach($transfer_employee_logs as $key => $transfer_employee_log){
                $transfer_employee_logs[$key] = $transfer_employee_log;
                $transfer_employee_logs[$key]['new_company'] = $transfer_employee_log['new_company'];
                $transfer_employee_logs[$key]['previous_system_approvers'] = json_decode($transfer_employee_log['previous_system_approvers']);
                $transfer_employee_logs[$key]['new_system_approvers'] = json_decode($transfer_employee_log['new_system_approvers']);
            }
        }

        Fpdf::AddPage("L", 'A4');
        Fpdf::SetMargins(0,0,0,0);
        Fpdf::SetAutoPageBreak(false);

        Fpdf::SetXY(10,10);
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::MultiCell(100,5, "EMPLOYEE NAME: " . $employee->last_name . ', ' . $employee->first_name,0,'L');

        Fpdf::SetXY(10,17);
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::MultiCell(20,5, "#" ,1,'C');

        Fpdf::SetXY(30,17);
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::MultiCell(100,5, "FROM" ,1,'C');

        Fpdf::SetXY(130,17);
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::MultiCell(100,5, "TO" ,1,'C');

        Fpdf::SetXY(230,17);
        Fpdf::SetFont('Arial', '', 10);
        Fpdf::MultiCell(55,5, "SUPPORTING DOCUMENTS" ,1,'C');

        $item_no = 1;
        $y=22;
        foreach($transfer_employee_logs as $item){
            $item_data = json_encode($item,true);
            $new_item_data = json_decode($item_data,true);
            Fpdf::SetXY(10,$y);
            Fpdf::SetFont('Arial', '', 8);
            Fpdf::MultiCell(20,30, $item_no ,1,'C');

            Fpdf::SetXY(30,$y);
            Fpdf::SetFont('Arial', '', 8);
            Fpdf::MultiCell(100,30, "" ,1,'L');

                //Company
                Fpdf::SetXY(30,$y);
                Fpdf::SetFont('Arial', '', 8);
                Fpdf::MultiCell(100,5, $new_item_data['previous_company']['name'],1,'L');

                //ID Number
                Fpdf::SetXY(30,$y+5);
                Fpdf::SetFont('Arial', '', 8);
                Fpdf::MultiCell(100,5, $new_item_data['previous_id_number'],1,'L');

                //Date Hired
                Fpdf::SetXY(30,$y+10);
                Fpdf::SetFont('Arial', '', 8);
                Fpdf::MultiCell(100,5, $new_item_data['previous_date_hired'],1,'L');

                //Position
                Fpdf::SetXY(30,$y+15);
                Fpdf::SetFont('Arial', '', 8);
                Fpdf::MultiCell(100,5, $new_item_data['previous_position'],1,'L');

                //Department
                Fpdf::SetXY(30,$y+20);
                Fpdf::SetFont('Arial', '', 8);
                Fpdf::MultiCell(100,5, $new_item_data['previous_department']['name'],1,'L');

                //Location
                Fpdf::SetXY(30,$y+25);
                Fpdf::SetFont('Arial', '', 8);
                Fpdf::MultiCell(100,5,$new_item_data['previous_location']['name'],1,'L');

            Fpdf::SetXY(130,$y);
            Fpdf::SetFont('Arial', '', 10);
            Fpdf::MultiCell(100,30, "" ,1,'L');

                //Company
                Fpdf::SetXY(130,$y);
                Fpdf::SetFont('Arial', '', 8);
                Fpdf::MultiCell(100,5, $new_item_data['new_company']['name'] ,1,'L');

                //ID Number
                Fpdf::SetXY(130,$y+5);
                Fpdf::SetFont('Arial', '', 8);
                Fpdf::MultiCell(100,5, $new_item_data['new_id_number'],1,'L');

                //Date Hired
                Fpdf::SetXY(130,$y+10);
                Fpdf::SetFont('Arial', '', 8);
                Fpdf::MultiCell(100,5, $new_item_data['new_date_hired'] ,1,'L');

                //Position
                Fpdf::SetXY(130,$y+15);
                Fpdf::SetFont('Arial', '', 8);
                Fpdf::MultiCell(100,5, $new_item_data['new_position'],1,'L');

                //Department
                Fpdf::SetXY(130,$y+20);
                Fpdf::SetFont('Arial', '', 8);
                Fpdf::MultiCell(100,5, $new_item_data['previous_department']['name'],1,'L');

                //Location
                Fpdf::SetXY(130,$y+25);
                Fpdf::SetFont('Arial', '', 8);
                Fpdf::MultiCell(100,5, $new_item_data['previous_location']['name'],1,'L');

            Fpdf::SetXY(230,$y);
            Fpdf::SetFont('Arial', '', 10);
            Fpdf::MultiCell(55,30, count($item->employee_transfer_attachments)  . ' Attachments',1,'C');

            $y+=30;
            $item_no++;

            if($item_no == 6 || $item_no == 12 || $item_no == 18){
                Fpdf::AddPage("L", 'A4');
                Fpdf::SetMargins(0,0,0,0);
                Fpdf::SetAutoPageBreak(false);
                $y=22;
            }
        }
        

        Fpdf::Output($employee->id . ".pdf", 'I');
        exit();
    }

    public function orgChartUnderEmployee(Employee $employee){
        
        $to_bottom = AssignHead::with('employee_info','unders.unders')
                                ->whereHas('employee_info',function($q){
                                    $q->where('status','Active');
                                })
                                ->where('employee_head_id',$employee->id)
                                ->whereIn('head_id',['3'])
                                ->get();

        $datas = [];
        //Unders
        if($to_bottom){
            foreach($to_bottom as $under)
            {
                if($under->employee_info->status == "Active"){
                    array_push($datas, (object)[
                        "id" => $under->employee_id,
                        "head_id" => $under->head_id,
                        'pid' => $under->employee_head_id,
                        'name' => $under->employee_info->first_name." ".$under->employee_info->last_name,
                        'position' => $under->employee_info->position,
                        'img' => "http://10.96.4.126:8668/storage/id_image/employee_image/".$under->employee_info->id.".png",
                    ]);  
                }
                    

                if($under->unders != null)
                {
                    foreach($under->unders as $under)
                    {
                        if($under->employee_info->status == "Active"){
                            array_push($datas, (object)[
                                "id" => $under->employee_id,
                                'pid' => $under->employee_head_id,
                                "head_id" => $under->head_id,
                                'name' => $under->employee_info->first_name." ".$under->employee_info->last_name,
                                'position' => $under->employee_info->position,
                                'img' => "http://10.96.4.126:8668/storage/id_image/employee_image/".$under->employee_info->id.".png",
                            ]); 
                        }

                        if($under->unders != null)
                        {
                            foreach($under->unders as $under)
                            {
                                if($under->employee_info->status == "Active"){
                                    array_push($datas, (object)[
                                        "id" => $under->employee_id,
                                        'pid' => $under->employee_head_id,
                                        "head_id" => $under->head_id,
                                        'name' => $under->employee_info->first_name." ".$under->employee_info->last_name,
                                        'position' => $under->employee_info->position,
                                        'img' => "http://10.96.4.126:8668/storage/id_image/employee_image/".$under->employee_info->id.".png",
                                    ]); 
                                }
                                if($under->unders != null)
                                {
                                    foreach($under->unders as $under)
                                    {
                                        if($under->employee_info->status == "Active"){
                                            array_push($datas, (object)[
                                                "id" => $under->employee_id,
                                                'pid' => $under->employee_head_id,
                                                "head_id" => $under->head_id,
                                                'name' => $under->employee_info->first_name." ".$under->employee_info->last_name,
                                                'position' => $under->employee_info->position,
                                                'img' => "http://10.96.4.126:8668/storage/id_image/employee_image/".$under->employee_info->id.".png",
                                            ]); 
                                        }
                                        
                                    }
                                }
                            }
                        }
                            
                    }
                }
            }
        }

        return $datas;

    }

    public function orgChart(Employee $employee){

        // $api = Api::first();
        $user = $employee;
        // $datas = '';
        // if($employee->id){
        //     $rUrl =  $api->api_link.$employee->id;
        //     $datas = file_get_contents($rUrl);
        // }
      
        $self = Employee::findOrfail($employee->id);
        $to_top = AssignHead::with('employee_info','approver_info','approvers.approvers.approvers.approvers.approvers')->where('employee_id',$employee->id)->whereIn('head_id',['3'])->first();
        $to_bottom = AssignHead::with('employee_info','unders.unders')
                                ->whereHas('employee_info',function($q){
                                    $q->where('status','Active');
                                })
                                ->where('employee_head_id',$employee->id)
                                ->whereIn('head_id',['3'])
                                ->get();
        $datas = [];
        //Unders
        if($to_bottom){
            foreach($to_bottom as $under)
            {
                if($under->employee_info->status == "Active"){
                    array_push($datas, (object)[
                        "id" => $under->employee_id,
                        "head_id" => $under->head_id,
                        'pid' => $under->employee_head_id,
                        'name' => $under->employee_info->first_name." ".$under->employee_info->last_name,
                        'position' => $under->employee_info->position,
                        'img' => "http://10.96.4.126:8668/storage/id_image/employee_image/".$under->employee_info->id.".png",
                    ]);  
                }
                  

                if($under->unders != null)
                {
                    foreach($under->unders as $under)
                    {
                        if($under->employee_info->status == "Active"){
                            array_push($datas, (object)[
                                "id" => $under->employee_id,
                                'pid' => $under->employee_head_id,
                                "head_id" => $under->head_id,
                                'name' => $under->employee_info->first_name." ".$under->employee_info->last_name,
                                'position' => $under->employee_info->position,
                                'img' => "http://10.96.4.126:8668/storage/id_image/employee_image/".$under->employee_info->id.".png",
                            ]); 
                        }

                        if($under->unders != null)
                        {
                            foreach($under->unders as $under)
                            {
                                if($under->employee_info->status == "Active"){
                                    array_push($datas, (object)[
                                        "id" => $under->employee_id,
                                        'pid' => $under->employee_head_id,
                                        "head_id" => $under->head_id,
                                        'name' => $under->employee_info->first_name." ".$under->employee_info->last_name,
                                        'position' => $under->employee_info->position,
                                        'img' => "http://10.96.4.126:8668/storage/id_image/employee_image/".$under->employee_info->id.".png",
                                    ]); 
                                }
                                if($under->unders != null)
                                {
                                    foreach($under->unders as $under)
                                    {
                                        if($under->employee_info->status == "Active"){
                                            array_push($datas, (object)[
                                                "id" => $under->employee_id,
                                                'pid' => $under->employee_head_id,
                                                "head_id" => $under->head_id,
                                                'name' => $under->employee_info->first_name." ".$under->employee_info->last_name,
                                                'position' => $under->employee_info->position,
                                                'img' => "http://10.96.4.126:8668/storage/id_image/employee_image/".$under->employee_info->id.".png",
                                            ]); 
                                        }
                                        
                                    }
                                }
                            }
                        }
                           
                    }
                }
            }
        }

        //Approvers
        if($to_top){
            if($to_top->employee_info->status == "Active"){
                array_push($datas, (object)[
                    'id' => $to_top->employee_id,
                    'pid' => $to_top->employee_head_id,
                    "head_id" => $to_top->head_id,
                    'name' => $to_top->employee_info->first_name.' '.$to_top->employee_info->last_name,
                    'position' => $to_top->employee_info->position,
                    'img' => "http://10.96.4.126:8668/storage/id_image/employee_image/".$to_top->employee_info->id.".png",
                ]);
            }

            //First Level
            if($to_top->approvers)
            {
                if($to_top->approvers->employee_info->status == "Active"){
                    array_push($datas, (object)[
                        'id' => $to_top->approvers->employee_id,
                        'pid' => $to_top->approvers->employee_head_id,
                        "head_id" => $to_top->approvers->head_id,
                        'name' => $to_top->approvers->employee_info->first_name." ".$to_top->approvers->employee_info->last_name,
                        'position' => $to_top->approvers->employee_info->position,
                        'img' => "http://10.96.4.126:8668/storage/id_image/employee_image/".$to_top->approvers->employee_info->id.".png",
                    ]);
                }
                //Second Level
                if($to_top->approvers->approvers){

                    if($to_top->approvers->approvers->employee_info->status == "Active"){
                        array_push($datas, (object)[
                            'id' => $to_top->approvers->approvers->employee_id,
                            'pid' => $to_top->approvers->approvers->employee_head_id,
                            'name' => $to_top->approvers->approvers->employee_info->first_name." ".$to_top->approvers->approvers->employee_info->last_name,
                            'position' => $to_top->approvers->approvers->employee_info->position,
                            'img' => "http://10.96.4.126:8668/storage/id_image/employee_image/".$to_top->approvers->approvers->employee_info->id.".png",
                        ]);
                    }
                    
                    //Third Level
                    if($to_top->approvers->approvers->approvers){

                        if($to_top->approvers->approvers->approvers->employee_info->status == "Active"){
                            array_push($datas, (object)[
                                'id' => $to_top->approvers->approvers->approvers->employee_id,
                                'pid' => $to_top->approvers->approvers->approvers->employee_head_id,
                                'name' => $to_top->approvers->approvers->approvers->employee_info->first_name." ".$to_top->approvers->approvers->approvers->employee_info->last_name,
                                'position' => $to_top->approvers->approvers->approvers->employee_info->position,
                                'img' => "http://10.96.4.126:8668/storage/id_image/employee_image/".$to_top->approvers->approvers->approvers->employee_info->id.".png",
                            ]);
                        }
                        
                        //Fourth Level
                        if($to_top->approvers->approvers->approvers->approvers)
                        {
                            if($to_top->approvers->approvers->approvers->approvers->employee_info->status == "Active"){
                                array_push($datas, (object)[
                                    'id' => $to_top->approvers->approvers->approvers->approvers->employee_id,
                                    'pid' => $to_top->approvers->approvers->approvers->approvers->employee_head_id,
                                    'name' => $to_top->approvers->approvers->approvers->approvers->employee_info->first_name." ".$to_top->approvers->approvers->approvers->approvers->employee_info->last_name,
                                    'position' => $to_top->approvers->approvers->approvers->approvers->employee_info->position,
                                    'img' => "http://10.96.4.126:8668/hrportal/public/id_image/employee_image/".$to_top->approvers->approvers->approvers->approvers->employee_info->id.".png",
                                ]); 
                          
                                array_push($datas, (object)[
                                    'id' => $to_top->approvers->approvers->approvers->approvers->employee_head_id,
                                    'pid' => null,
                                    'name' => $to_top->approvers->approvers->approvers->approvers->approver_info->first_name." ".$to_top->approvers->approvers->approvers->approvers->approver_info->last_name,
                                    'position' => $to_top->approvers->approvers->approvers->approvers->approver_info->position,
                                    'img' => "http://10.96.4.126:8668/hrportal/public/id_image/employee_image/".$to_top->approvers->approvers->approvers->approvers->approver_info->id.".png",
                                ]); 
                            }

                        }else{

                            if($to_top->approvers->approvers->approvers->approver_info->status == "Active"){
                                array_push($datas, (object)[
                                    'id' => $to_top->approvers->approvers->approvers->employee_head_id,
                                    'pid' => null,
                                    'name' => $to_top->approvers->approvers->approvers->approver_info->first_name." ".$to_top->approvers->approvers->approvers->approver_info->last_name,
                                    'position' => $to_top->approvers->approvers->approvers->approver_info->position,
                                    'img' => "http://10.96.4.126:8668/storage/id_image/employee_image/".$to_top->approvers->approvers->approvers->approver_info->id.".png",
                                ]); 
                            }
                        }
                    }else{
                        if($to_top->approvers->approvers->approver_info->status == "Active"){
                            array_push($datas, (object)[
                                'id' => $to_top->approvers->approvers->employee_head_id,
                                'pid' => null,
                                'name' => $to_top->approvers->approvers->approver_info->first_name." ".$to_top->approvers->approvers->approver_info->last_name,
                                'position' => $to_top->approvers->approvers->approver_info->position,
                                'img' => "http://10.96.4.126:8668/storage/id_image/employee_image/".$to_top->approvers->approvers->approver_info->id.".png",
                            ]);
                        }
                    }

                }else {
                    if($to_top->approvers->approver_info->status == "Active"){
                        array_push($datas, (object)[
                            'id' => $to_top->approvers->employee_head_id,
                            'pid' => null,
                            'name' => $to_top->approvers->approver_info->first_name." ".$to_top->approvers->approver_info->last_name,
                            'position' => $to_top->approvers->approver_info->position,
                            'img' => "http://10.96.4.126:8668/storage/id_image/employee_image/".$to_top->approvers->approver_info->id.".png",
                        ]);
                    }     
                }
            }else{
                if($to_top->approver_info->status == "Active"){
                    array_push($datas, (object)[
                        "id" => $to_top->employee_head_id,
                        "pid" => null,
                        "name" => $to_top->approver_info->first_name." ".$to_top->approver_info->last_name,
                        "position" => $to_top->approver_info->position,
                        "img" => "http://10.96.4.126:8668/storage/id_image/employee_image/".$to_top->approver_info->id.".png",
                    ]);
                } 
            }
        }else {
            array_push($datas, (object)[
                "id" => $self->id,
                "pid" => null,
                "name" => $self->first_name." ".$self->last_name,
                "position" => $self->position,
                "img" => "http://10.96.4.126:8668/hrportal/public/id_image/employee_image/".$self->id.".png",
            ]);
        }   
        
        $datas = json_encode($datas);
        return view('org_chart',compact('datas','user'));
        
    }

    public function exportEmployees(){
        $all_employee = Employee::with(array('companies',
                                        'departments',
                                        'locations',
                                        'employee_accountabilities' => function($q){
                                            $q->whereNotNull('date_assigned');
                                            $q->whereNull('date_expired');
                                        },
                                        'immediate_superior.approver_info',
                                        'immediate_superior_2.approver_info',
                                        'department_manager.approver_info',
                                        'bu_head.approver_info',
                                        'cluster_head.approver_info',
                                    ))
                                    ->where('status','Active')
                                    ->orderBy('id','ASC')
                                    ->get();
        $filtered_data = [];

        foreach( $all_employee as $key => $employee ){

            $filtered_data[$key] = $employee;
            $filtered_data[$key]['user_id'] = $employee['user_id'];
            $filtered_data[$key]['id_number'] = $employee['id_number'];
            $filtered_data[$key]['first_name'] = strtoupper($employee['first_name']);
            $filtered_data[$key]['middle_name'] = strtoupper($employee['middle_name']);
            $filtered_data[$key]['last_name'] = strtoupper($employee['last_name']);
            $middle_initial =  $employee['middle_initial'] ? $employee['middle_initial'] . '. ' : " ";
            $filtered_data[$key]['full_name'] = strtoupper($employee['first_name'])  . ' '. $middle_initial . strtoupper($employee['last_name']);
            $filtered_data[$key]['position'] = $employee['position'];
            $filtered_data[$key]['level'] = $employee['level'];
            $filtered_data[$key]['cluster'] = $employee['cluster'];
            

            if($employee['companies']){
                if(isset($employee['departments'][0])){
                    $filtered_data[$key]['company'] = $employee['companies'][0] ? $employee['companies'][0]['name'] : "";
                }else{
                    $filtered_data[$key]['company'] = "";
                }
            }else{
                $filtered_data[$key]['company'] = "";
            }
           
            $filtered_data[$key]['position'] = $employee['position'];

            if($employee['departments']){
                if(isset($employee['departments'][0])){
                    $filtered_data[$key]['department'] = $employee['departments'][0]['name'];
                }else{
                    $filtered_data[$key]['department'] = "";
                }
               
            }else{
                $filtered_data[$key]['department'] = "";
            }

            if($employee['locations']){
                if(isset($employee['locations'][0])){
                    $filtered_data[$key]['location'] = $employee['locations'][0]['name'];
                }else{
                    $filtered_data[$key]['location'] = "";
                }
               
            }else{
                $filtered_data[$key]['location'] = "";
            }

            $filtered_data[$key]['mobile_number'] = $employee['mobile_number'];

            if(count($employee['employee_accountabilities']) > 0){
                $filtered_data[$key]['company_assign_phone'] = $employee['employee_accountabilities'][0]['inventories']['service_number'];
            }else{
                $filtered_data[$key]['company_assign_phone'] = "";
            }
            

            $filtered_data[$key]['area'] = $employee['area'];
            $filtered_data[$key]['date_hired'] = $employee['date_hired'];

            try {
                $filtered_data[$key]['basic_salary'] = $employee['monthly_basic_salary'] ? Crypt::decryptString($employee['monthly_basic_salary']) : "";
            } catch (DecryptException $e) {
                $filtered_data[$key]['basic_salary'] = "";
            }

            

            $today = date("Y-m-d");

            $date_hired = $employee['date_hired'];
           
            //Get Tenure
            $tenure = "";
            if($date_hired != '0000-00-00' && $date_hired){
                $diff = date_diff(date_create($date_hired), date_create($today));
                $year = $diff->format('%y');
                $month = $diff->format('%m');

                $tenure = $year . '.' . $month;
            }

            $filtered_data[$key]['tenure'] = $tenure;

            //Get 5th month
            $fifth_month = $employee['date_hired'] ? date('Y-m-d', strtotime("+150 days", strtotime($employee['date_hired']))) : "";
            $filtered_data[$key]['fifth_month'] = $fifth_month;

            //Get 6th month
            $six_month = $employee['date_hired'] ? date('Y-m-d', strtotime("+180 days", strtotime($employee['date_hired']))) : "";
            $filtered_data[$key]['six_month'] = $six_month;

            //Personal Phone number
            $filtered_data[$key]['mobile_number'] = str_replace("+63","",$employee['mobile_number']);

            $filtered_data[$key]['employee_status'] = $employee['classification'];

            $filtered_data[$key]['marital_status'] = $employee['marital_status'];

            //Birthdate
            $filtered_data[$key]['birthdate'] = $employee['birthdate'];

            $birthdate = $employee['birthdate'];
            $age = "";
            if($birthdate != '0000-00-00' && $birthdate){
                $diff = date_diff(date_create($birthdate), date_create($today));
                $age = $diff->format('%y');
            }
            $filtered_data[$key]['age'] = $age;

            $age_range = "";
            if($age < 21){
                $age_range = "21 Below";
            }else if($age >= 21 && $age <= 30){
                $age_range = "21 - 30 YEARS OLD";
            }else if($age >= 31 && $age <= 40){
                $age_range = "31 - 40 YEARS OLD";
            }else if($age >= 41 && $age <= 50){
                $age_range = "41 - 50 YEARS OLD";
            }else if($age >= 51 && $age <= 60){
                $age_range = "51 - 60 YEARS OLD";
            }
            $filtered_data[$key]['age_range'] = $age_range;

            $filtered_data[$key]['gender'] = $employee['gender'];

            $filtered_data[$key]['status'] = $employee['status'];
        }
       
        return $filtered_data;
    }

    public function exportInactiveEmployees(){

        $all_employee = Employee::with(array('companies',
                                        'departments',
                                        'locations',
                                        'employee_accountabilities',
                                        'immediate_superior.approver_info',
                                        'immediate_superior_2.approver_info',
                                        'department_manager.approver_info',
                                        'bu_head.approver_info',
                                        'cluster_head.approver_info',
                                    ))
                                    ->where('status','Inactive')
                                    ->orderBy('id','ASC')
                                    ->get();

        $filtered_data = [];

        foreach( $all_employee as $key => $employee ){

            $filtered_data[$key] = $employee;
            $filtered_data[$key]['user_id'] = $employee['user_id'];
            $filtered_data[$key]['id_number'] = $employee['id_number'];
            $filtered_data[$key]['first_name'] = strtoupper($employee['first_name']);
            $filtered_data[$key]['middle_name'] = strtoupper($employee['middle_name']);
            $filtered_data[$key]['last_name'] = strtoupper($employee['last_name']);
            $middle_initial =  $employee['middle_initial'] ? $employee['middle_initial'] . '. ' : " ";
            $filtered_data[$key]['full_name'] = strtoupper($employee['first_name'])  . ' '. $middle_initial . strtoupper($employee['last_name']);
            $filtered_data[$key]['position'] = $employee['position'];
            $filtered_data[$key]['level'] = $employee['level'];
            $filtered_data[$key]['cluster'] = $employee['cluster'];
            

            if($employee['companies']){
                if(isset($employee['departments'][0])){
                    $filtered_data[$key]['company'] = $employee['companies'][0] ? $employee['companies'][0]['name'] : "";
                }else{
                    $filtered_data[$key]['company'] = "";
                }
            }else{
                $filtered_data[$key]['company'] = "";
            }
           
            $filtered_data[$key]['position'] = $employee['position'];

            if($employee['departments']){
                if(isset($employee['departments'][0])){
                    $filtered_data[$key]['department'] = $employee['departments'][0]['name'];
                }else{
                    $filtered_data[$key]['department'] = "";
                }
               
            }else{
                $filtered_data[$key]['department'] = "";
            }

            if($employee['locations']){
                if(isset($employee['locations'][0])){
                    $filtered_data[$key]['location'] = $employee['locations'][0]['name'];
                }else{
                    $filtered_data[$key]['location'] = "";
                }
               
            }else{
                $filtered_data[$key]['location'] = "";
            }

            $filtered_data[$key]['mobile_number'] = $employee['mobile_number'];

            if(count($employee['employee_accountabilities']) > 0){
                $filtered_data[$key]['company_assign_phone'] = $employee['employee_accountabilities'][0]['inventories']['service_number'];
            }else{
                $filtered_data[$key]['company_assign_phone'] = "";
            }
            

            $filtered_data[$key]['area'] = $employee['area'];
            $filtered_data[$key]['date_hired'] = $employee['date_hired'];

            $today = date("Y-m-d");

            $date_hired = $employee['date_hired'];
            try {
                $filtered_data[$key]['basic_salary'] = $employee['monthly_basic_salary'] ? Crypt::decryptString($employee['monthly_basic_salary']) : "";
            } catch (DecryptException $e) {
                $filtered_data[$key]['basic_salary'] = "";
            }
           
            //Get Tenure
            $tenure = "";
            if($date_hired != '0000-00-00' && $date_hired){
                $diff = date_diff(date_create($date_hired), date_create($today));
                $year = $diff->format('%y');
                $month = $diff->format('%m');

                $tenure = $year . '.' . $month;
            }

            $filtered_data[$key]['tenure'] = $tenure;

            //Get 5th month
            $fifth_month = $employee['date_hired'] ? date('Y-m-d', strtotime("+150 days", strtotime($employee['date_hired']))) : "";
            $filtered_data[$key]['fifth_month'] = $fifth_month;

            //Get 6th month
            $six_month = $employee['date_hired'] ? date('Y-m-d', strtotime("+180 days", strtotime($employee['date_hired']))) : "";
            $filtered_data[$key]['six_month'] = $six_month;

            //Personal Phone number
            $filtered_data[$key]['mobile_number'] = str_replace("+63","",$employee['mobile_number']);

            $filtered_data[$key]['employee_status'] = $employee['classification'];

            $filtered_data[$key]['marital_status'] = $employee['marital_status'];

            //Birthdate
            $filtered_data[$key]['birthdate'] = $employee['birthdate'];

            $birthdate = $employee['birthdate'];
            $age = "";
            if($birthdate != '0000-00-00' && $birthdate){
                $diff = date_diff(date_create($birthdate), date_create($today));
                $age = $diff->format('%y');
            }
            $filtered_data[$key]['age'] = $age;

            $age_range = "";
            if($age < 21){
                $age_range = "21 Below";
            }else if($age >= 21 && $age <= 30){
                $age_range = "21 - 30 YEARS OLD";
            }else if($age >= 31 && $age <= 40){
                $age_range = "31 - 40 YEARS OLD";
            }else if($age >= 41 && $age <= 50){
                $age_range = "41 - 50 YEARS OLD";
            }else if($age >= 51 && $age <= 60){
                $age_range = "51 - 60 YEARS OLD";
            }
            $filtered_data[$key]['age_range'] = $age_range;

            $filtered_data[$key]['gender'] = $employee['gender'];

            $filtered_data[$key]['status'] = $employee['status'];
        }
        return $filtered_data;
    }

    public function updateemployeeNpaRequest(Request $request){

        $data = $request->all();

        $npa_request = EmployeeNpaRequest::where('id',$data['id'])->first();

        $this->validate($request, [
            'subject' => 'required',
        ]);
        if($npa_request){
            DB::beginTransaction();
            try {   
                unset($data['id']);
                if($npa_request->update($data)){
                    DB::commit();
                    return Employee::with('companies','departments','locations')->where('id',$npa_request['employee_id'])->first();
                }
               
                return Employee::with('companies','departments','locations')->where('id',$npa_request['employee_id'])->first();
            }catch (Exception $e) {
                DB::rollBack();
                return Employee::with('companies','departments','locations')->where('id',$npa_request['employee_id'])->first();
            }
        }
        return $data;
    }

    public function employeeNpaRequest(Request $request){

        $data = $request->all();

        $this->validate($request, [
            'subject' => 'required',
        ]);
        
        $employee_data = Employee::with('companies','departments','locations','assign_heads')->where('id',$data['employee_id'])->first();
         
        if($employee_data){
            DB::beginTransaction();
            try { 
                $select_last_request = EmployeeNpaRequest::select('ctrl_no')->orderBy('ctrl_no','DESC')->orderBy('created_at','DESC')->first();

                $ctrl_no = "";
                if($select_last_request){
                    $ctrl_no = $select_last_request['ctrl_no'] + 1;
                }else{
                    $ctrl_no = 2020656;
                }

                $check_user = User::with('roles')->where('id',Auth::user()->id)->first();
                $requested_by = Employee::select('id')->where('user_id',$check_user['id'])->first();

                $data['ctrl_no'] = $ctrl_no;
                $data['employee_name'] = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                $data['requested_by'] = $requested_by['id'];
                $data['date_prepared'] = date('Y-m-d h:m:s');
                $data['status'] = 'Pending';

                if(EmployeeNpaRequest::create($data)){
                    DB::commit();

                     //Recommended By
                    if($data['recommended_by']){

                        $recommended_by = Employee::with('user')->where('id',$data['recommended_by'])->first();

                        $email_recommended_by = $recommended_by['user']['email'];
                        $reciever_name_recommended_by = $recommended_by['user']['name'];

                        $npa_data = [
                            'reciever_name' => $reciever_name_recommended_by,
                            'employee_name' => $employee_data['first_name'] . ' ' . $employee_data['last_name'],
                            'company' => $employee_data['companies'][0]['name'],
                            'position' => $employee_data['position'],
                            'npa_title' => $data['subject'],
                            'link' => 'http://10.96.4.126:8668/employees?employee_id='.$employee_data['id'].'&type=npa',
                        ];
                        $send_update = Mail::to($email_recommended_by)->send(new EmployeeNpaNotification($npa_data));
                    }

                    //Approved By
                    if($data['approved_by']){

                        $approved_by = Employee::with('user')->where('id',$data['approved_by'])->first();

                        $email_approved_by = $approved_by['user']['email'];
                        $reciever_name_approved_by = $approved_by['user']['name'];
    
                        $npa_data = [
                            'reciever_name' => $reciever_name_approved_by,
                            'employee_name' => $employee_data['first_name'] . ' ' . $employee_data['last_name'],
                            'company' => $employee_data['companies'][0]['name'],
                            'position' => $employee_data['position'],
                            'npa_title' => $data['subject'],
                            'link' => 'http://10.96.4.126:8668/employees?employee_id='.$employee_data['id'].'&type=npa',
                        ];
                        $send_update = Mail::to($email_approved_by)->send(new EmployeeNpaNotification($npa_data));
                    }


                    //BU Head
                    if($data['bu_head']){

                        $bu_head = Employee::with('user')->where('id',$data['bu_head'])->first();

                        $email_bu_head = $bu_head['user']['email'];
                        $reciever_name_bu_head = $bu_head['user']['name'];

                        $npa_data = [
                            'reciever_name' => $reciever_name_bu_head,
                            'employee_name' => $employee_data['first_name'] . ' ' . $employee_data['last_name'],
                            'company' => $employee_data['companies'][0]['name'],
                            'position' => $employee_data['position'],
                            'npa_title' => $data['subject'],
                            'link' => 'http://10.96.4.126:8668/employees?employee_id='.$employee_data['id'].'&type=npa',
                        ];
                        $send_update = Mail::to($email_bu_head)->send(new EmployeeNpaNotification($npa_data));
                    }

                    return Employee::with('companies','departments','locations')->where('id',$employee_data['id'])->first();
                }
               
                return Employee::with('companies','departments','locations')->where('id',$employee_data['id'])->first();
            }catch (Exception $e) {
                DB::rollBack();
                return $employee_data;
            }
        }
        return $data;
    }

    public function getNPARequestLists($employee_id){
        return $npa_requests_list = EmployeeNpaRequest::where('employee_id' , $employee_id)->orderBy('created_at','DESC')->get();
    }

    public function destroyNPARequest(EmployeeNpaRequest $npa_request){
        if($npa_request->delete()){
            return 'Deleted';
        }
    }

    public function viewNPARequest(EmployeeNpaRequest $npa_request){
        return $npa_request = EmployeeNpaRequest::with('from_company','from_location','from_immediate_manager','from_department','to_company','to_location','to_immediate_manager','to_department','prepared_by','recommended_by','approved_by','bu_head')->where('id',$npa_request->id)->first();
    }

    public function approvedByHRRecommend(EmployeeNpaRequest $npa_request){
        if($npa_request){
            $data = [];
            $data['recommended_by_status'] = 'Approved';
            $data['status'] = 'Pre-approved';
            $npa_request->update($data);
            return 'Approved';
        }
    }

    public function approveByHRApprover(EmployeeNpaRequest $npa_request){
        if($npa_request){
            $data = [];
            $data['approved_by_status'] = 'Approved';
            $data['status'] = 'Pre-approved';
            if($npa_request->update($data)){
                $check_and_update = $this->changeApprovedNPAtoEmployee($npa_request->employee_id,$npa_request->id);
            }
            return 'Approved';
        }
    }

    public function approveByBUHead(EmployeeNpaRequest $npa_request){
        if($npa_request){
            $data = [];
            $data['bu_head_status'] = 'Approved';
            $data['status'] = 'Pre-approved';
            if($npa_request->update($data)){
                $check_and_update = $this->changeApprovedNPAtoEmployee($npa_request->employee_id,$npa_request->id);
            }
            return 'Approved';
        }
    }

    public function changeApprovedNPAtoEmployee($employee_id,$npa_request_id){
        
        $employee = Employee::with('companies','departments','locations','assign_heads')->where('id',$employee_id)->first();

        $npa_request = EmployeeNpaRequest::where('id',$npa_request_id)->first();

        if($npa_request['approved_by_status'] == 'Approved' && $npa_request['bu_head_status'] == 'Approved'){

            DB::beginTransaction();
            try {
                
                if($npa_request['subject'] == "REGULARIZATION"){
                    $data = [];
                    $data['classification'] = "Regular";
                    $data['date_regularized'] = date('Y-m-d');
                    $employee->update($data);
                }

                if($npa_request['to_position_title']){
                    $data = [];
                    $data['position'] =  $npa_request['to_position_title'];
                    $employee->update($data);
                }

                $immediate_manager = "";
                if($npa_request['to_immediate_manager']){
                    $immediate_manager =  $npa_request['to_immediate_manager'];
                    $assign_head = AssignHead::where('employee_id',$employee['id'])->where('head_id','3')->first();
                    $data = [];
                    $data['employee_head_id'] =  $immediate_manager;
                    $assign_head->update($data);
                }
                
                $department = "";
                if($npa_request['to_department']){
                    $department =  $npa_request['to_department'];
                    $employee->departments()->sync( (array) $department);
                }

                $location = "";
                if($npa_request['to_location']){
                    $location =  $npa_request['to_location'];
                    $employee->locations()->sync( (array) $location);
                }

                $monthly_basic_salary = "";
                if($npa_request['to_monthly_basic_salary']){
                    $data = [];
                    $data['monthly_basic_salary'] = Crypt::encryptString($npa_request['to_monthly_basic_salary']);
                    $employee->update($data);

                    $salary_data = [];
                    $salary_data['employee_id'] = $employee['id'];
                    $salary_data['old_salary'] = Crypt::encryptString($npa_request['from_monthly_basic_salary']);
                    $salary_data['new_salary'] = Crypt::encryptString($npa_request['to_monthly_basic_salary']);
                    $salary_data['reason'] = ucwords($npa_request['subject']);
                    $salary_data['salary_date'] = date('Y-m-d');
                    EmployeeSalaryRecord::create($salary_data);
                }

                $npa_request_data = [];
                $npa_request_data['status'] = 'Approved';
                if($npa_request->update($npa_request_data)){
                    DB::commit();
                    return $employee;
                }
            }catch (Exception $e) {
                DB::rollBack();
                return $employee;
            }

        }
    }

    public function decryptMonthlyBasicSalary(Employee $employee){
        try {
            return $monthly_basic_salary = $employee['monthly_basic_salary'] ? Crypt::decryptString($employee['monthly_basic_salary']) : "";
        } catch (DecryptException $e) {
            return "";
        }
    }

    public function decryptMonthlyBasicSalaryRecord(Employee $employee){
        $salary_records = EmployeeSalaryRecord::where('employee_id',$employee->id)->get();

        if($salary_records){
            $salary_record_data = [];
            foreach($salary_records as $k => $salary_record){
                $salary_record_data[$k] = $salary_record;
                $salary_record_data[$k]['old_salary'] = $salary_record['old_salary'] ? Crypt::decryptString($salary_record['old_salary']) : "";
                $salary_record_data[$k]['new_salary'] = $salary_record['new_salary'] ? Crypt::decryptString($salary_record['new_salary']) : "";
            }
            return $salary_record_data;
        }else{
            return [];
        }
    }
    
    public function addCardBiometricsID(Employee $employee){

        $full_name = $employee->first_name . ' ' . $employee->last_name;
            
        $get_rfid_users = RfidUser::first();
        $get_users = json_decode($get_rfid_users['door_users'],true);
        $get_face_users = json_decode($get_rfid_users['face_users'],true);

        //Get User
        $face_user =  $this->get_user($get_face_users, $full_name);

        if($face_user){
            $face_name = '';
            $face_user_id = '';

            foreach($face_user as $user_data){
                $face_name = $user_data['name'];
                $face_user_id = $user_data['user_id'];
            }

            $client = new Client();

            $client = new Client([
                'base_uri' => 'http://10.96.4.61:8795/v2/',
                'cookies' => true,
            ]);

            $client->request('POST', 'login', [
                'json' => [
                    'name' => 'fcovid19',
                    'password' => '$upr3m@!',
                    'user_id' => 'admin'
                ]
            ]);

            try{

                $new_id_card = ltrim($employee->door_id_number, "0");  

                //Saved Card 
                $save_card = $client->request('POST', 'cards/wiegand_card', [
                    'json' => [
                        'wiegand_card_id_list' => [[
                            'card_id'=> "$new_id_card"
                        ]],
                        'wiegand_format_id' => 5,
                    ]
                ]);

                $save_card_response = json_decode($save_card->getBody(), true);

                if($save_card_response['status_code'] == "CREATED"){
                    $id_data = [];
                    $id_data['add_card_id'] = $save_card_response['id'];
                    $employee->update($id_data);

                    return 'saved';
                }else{
                    return 'not_saved';
                }
            }
            catch (BadResponseException $e) {
                return 'not_saved';
            }       
            catch (ServerException $e) {
                return 'not_saved';
            }       
            catch (ClientException $e) {
                return 'not_saved';
            }       
        }
    }

    public function overideBiometricsID(Employee $employee){

        $full_name = $employee->first_name . ' ' . $employee->last_name;
            
        $get_rfid_users = RfidUser::first();
        $get_users = json_decode($get_rfid_users['door_users'],true);
        $get_face_users = json_decode($get_rfid_users['face_users'],true);

        //Get User
        $face_user =  $this->get_user($get_face_users, $full_name);

        if($face_user){
            $face_name = '';
            $face_user_id = '';

            foreach($face_user as $user_data){
                $face_name = $user_data['name'];
                $face_user_id = $user_data['user_id'];
            }

            // return $this->getUserCards($face_user_id);

            if($employee->door_id_number){
                
                $new_id_card = ltrim($employee->door_id_number, "0");  

                $unassigned_card = $this->getUnassignedCard("$new_id_card");

                $client = new Client();

                $client = new Client([
                    'base_uri' => 'http://10.96.4.61:8795/v2/',
                    'cookies' => true,
                ]);

                $client->request('POST', 'login', [
                    'json' => [
                        'name' => 'fcovid19',
                        'password' => '$upr3m@!',
                        'user_id' => 'admin'
                    ]
                ]);

                
                
                try{
                    if($unassigned_card){
                        $card_id = $unassigned_card['card_id'];
                        $id = $unassigned_card['id'];

                        if($card_id){
                            //Assign card for user 
                            $put_card = $client->request('PUT', 'users/' . $face_user_id . '/cards',[
                                'json' => [
                                    'card_list' => [[
                                        'card_id'=>'3631098',
                                        'id'=>'1038'
                                    ]]
                                ]
                            ]);
                            return $put_cardresponse = json_decode($put_card->getBody(), true);
                        }
                    }else{
                        return 'card_existing';
                    }  
                }
                catch (BadResponseException $e) {
                    return $e;
                }       
                catch (ServerException $e) {
                    return $e;
                }       
                catch (ClientException $e) {
                    return $e;
                }        
            }
        }else{
            return 'no_access';
        }
        
        
    }
    
    public function getUserCards($user_id){
        $client = new Client();

        $client = new Client([
            'base_uri' => 'http://10.96.4.61:8795/v2/',
            'cookies' => true,
        ]);

        $client->request('POST', 'login', [
            'json' => [
                'name' => 'fcovid19',
                'password' => '$upr3m@!',
                'user_id' => 'admin'
            ]
        ]);

        //Get User cards
        $get_user_cards = $client->request('GET', 'users/' . $user_id . '/cards');
        return $get_user_cards = json_decode($get_user_cards->getBody(), true);
    }

    public function getUnassignedCard($card_id){

        $client = new Client();

        $client = new Client([
            'base_uri' => 'http://10.96.4.61:8795/v2/',
            'cookies' => true,
        ]);

        $client->request('POST', 'login', [
            'json' => [
                'name' => 'fcovid19',
                'password' => '$upr3m@!',
                'user_id' => 'admin'
            ]
        ]);

        //Get Unassigned Cards
        $get_unassigned_cards = $client->request('GET', 'cards/unassigned?limit=10000&offset=0');
        $get_unassigned_cards = json_decode($get_unassigned_cards->getBody(), true);
        
        $selected_card = [];
        if($get_unassigned_cards){
            foreach($get_unassigned_cards['records'] as $card){
                if($card['card_id'] == $card_id){
                    $selected_card['id'] = $card['id'];
                    $selected_card['card_id'] = $card['card_id'];
                }
            }
        }

        return $selected_card;
    }

    public function get_user($get_users, $user){
        $get_users_arr = [];

        $result = array_filter($get_users, function ($item) use ($user) {
            if (stripos($item['name'], $user) !== false) {
                $get_users_arr['name'] = $item['name'];
                $get_users_arr['user_id'] = $item['user_id'];

                return $get_users_arr;
            }else{
                return $get_users_arr = [];
            }
        });

        return $result;
    }

    public function printEmployeeQR(Request $request){
        $data = $request->all();

        $data = [
            'ids'=> json_encode($request->ids)
        ];
        $qrlog = QrCodeLog::create($data);
        return $qrlog->id;
    }

    public function printPreviewEmployeeQR(QrCodeLog $qrlog){
        $qrlog_ids = json_decode($qrlog->ids);
    
        if($qrlog_ids){
            foreach($qrlog_ids as $log_id){

                Fpdf::AddPage("P", [24,20]);
                Fpdf::SetMargins(0,0,0,0);
                Fpdf::SetAutoPageBreak(false);

                $employee = Employee::select('id','id_number')->where('id',$log_id)->first();
                $id_number = $employee['id_number'];
                $qr_text = "https://myvisitors.lafilgroup.com:8671/calling_card/" . $id_number;

                if(file_exists(base_path().'/public/qr_employees/'.$employee['id'].'.png')){
                    Fpdf::Image(base_path().'/public/qr_employees/'.$employee['id'].'.png', 2, 2, 15, 15,'PNG');
                    Fpdf::SetXY(2,17);
                    Fpdf::SetFont('Arial', '', 5);
                    Fpdf::MultiCell(15,2, $employee['id_number'] ,0,'C');
                }else{
                    QRCode::text($qr_text)->setSize(40)->setMargin(2)->setOutfile(base_path().'/public/qr_employees/'.$employee['id'].'.png')->png();
                    Fpdf::Image(base_path().'/public/qr_employees/'.$employee['id'].'.png', 2, 2, 15, 15,'PNG');
                    Fpdf::SetXY(2,17);
                    Fpdf::SetFont('Arial', '', 5);
                    Fpdf::MultiCell(15,2, $employee['id_number'] ,0,'C');
                }

            }
        }
        Fpdf::Output($qrlog->id . ".pdf", 'I');
        exit();
    }
     
}
