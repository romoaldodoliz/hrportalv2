<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Employee;
use App\AssignHead;
use App\Dependent;
use App\Department;
use App\Company;
use App\Location;
use App\User;
use App\PrintIdLog;
use App\EmployeeApprovalRequest;
use App\EmployeeDetailVerification;
use App\EmployeeTransfer;
use App\DependentAttachment;
use App\Api;

use Carbon\Carbon;
use Fpdf;
use File;
use Image;

use Auth;
use Hash;
use DB;

class EmployeeController extends Controller
{
    public function index()
    {
        session(['header_text' => 'Employees']);
        return view('employees.index');
    }

    public function indexData()
    {
        $check_user = User::where('id',Auth::user()->id)->first();

        return Employee::with('companies','departments','locations')
                            ->when($check_user['view_confidential'] != "YES" , function($q) {
                                $q->where('confidential','NO');
                            })
                            ->orderBy('series_number','DESC')
                            ->get();
        
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
        return Employee::with('companies','departments')->whereMonth('created_at', Carbon::now()->month)->get();
    }
    public function employeeUpdateCount()
    {
        //return Employee::whereMonth('updated_at', Carbon::now()->month)->count();
        return EmployeeDetailVerification::count();
    }

    public function employeeApprovers(Employee $employee){
        return AssignHead::where('employee_id',$employee->id)->orderBy('created_at', 'DESC')->get();
    }

    public function employeeDependents(Employee $employee){
        return Dependent::where('employee_id',$employee->id)->orderBy('created_at', 'ASC')->get();
    }

    public function employeeDependentsAttachments(Employee $employee){
        return DependentAttachment::where('employee_id',$employee->id)->orderBy('created_at', 'ASC')->get();
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
        
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'marital_status' => 'required',
            'gender' => 'required',
            'birthdate' => 'required',
            'company_list' => 'required',
            'department_list' => 'required',
            'location_list' => 'required',
            'tax_status' => 'required',
        ],[
            'company_list.required' => 'This field is required',
            'department_list.required' => 'This field is required',
            'location_list.required' => 'This field is required',
            'tax_status.required' => 'This field is required',
        ]);

        $data = $request->all();

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
            $email = strtolower($request->input('first_name')) .".". strtolower($request->input('last_name')) . "@lafilgroup.com";
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
            'tax_status' => 'required',
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

        $employee = Employee::with('companies','departments','locations','print_id_logs','verification')
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

    public function employeeIdIndex(){
        session(['header_text' => 'Employees']);

        $check_user = User::where('id',Auth::user()->id)->first();

        $employee = Employee::select('id','id_number','series_number','first_name','last_name')->with('companies','departments','locations','print_id_logs','verification')
                            ->when($check_user['view_confidential'] != "YES" , function($q) {
                                $q->where('confidential','NO');
                            })
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

        $employee = Employee::select('id','id_number','series_number','first_name','last_name')->with('companies','departments','locations','print_id_logs','verification')
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

        $employee =  Employee::select('id','id_number','last_name','first_name','nick_name')->with('departments','locations')->where('id',$employee->id)->first();

        if($employee['nick_name'] == '' || $employee['nick_name'] == '-'){
            $nick_name = strtolower($employee['first_name']);
        }else{
            $nick_name = strtolower($employee['nick_name']);
        }
        
        $first_name = utf8_decode(strtolower($employee['first_name']));
        $convert_last_name = mb_strtolower($employee['last_name'],'UTF-8');
        $last_name_front = utf8_decode($convert_last_name);
        $last_name_back = utf8_decode($employee['last_name']);

        $department = $employee->departments ? strtolower($employee->departments[0]['name']) : "";
        
        $address = "";            
        if($employee->locations->first()->id){
            $location = Location::with('addresses')->where('id',$employee->locations->first()->id)->first();
            $address = isset($location->addresses->first()->name) ? $location->addresses->first()->name : '';
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
       
        $full_name = ucfirst($nick_name) .' ' . ucfirst($last_name_front);

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
        $full_name_back = strtoupper($first_name) .' ' . strtoupper($last_name_back);
        Fpdf::SetFont('Arial','B', 8);
        Fpdf::SetXY(0,37);
        Fpdf::MultiCell(54,6, strtoupper($full_name_back) ,0,'C');


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
                $transfer_logs_data['previous_division'] = $employee_data['division'];
                $transfer_logs_data['previous_department'] = $employee_data['departments'] ? $employee_data['departments'][0]['id'] : "";
                $transfer_logs_data['previous_company'] = $employee_data['companies'] ? $employee_data['companies'][0]['id'] : "";
                $transfer_logs_data['previous_location'] = $employee_data['locations'] ? $employee_data['locations'][0]['id'] : "";
                $transfer_logs_data['previous_system_approvers'] = $employee_data['assign_heads'] ? json_encode($assign_heads) : "";

                //New
                $transfer_logs_data['new_id_number'] = $new_id_number;
                $transfer_logs_data['new_position'] = $data['position'];
                $transfer_logs_data['new_date_hired'] = $data['date_hired'];
                $transfer_logs_data['new_division'] = $data['division'];
                $transfer_logs_data['new_department'] =  $data['department_list'] ? $data['department_list'] : "";
                $transfer_logs_data['new_company'] = $data['company_list'] ? $data['company_list'] : "";
                $transfer_logs_data['new_location'] = $data['location_list'] ? $data['location_list'] : "";
                $transfer_logs_data['new_system_approvers'] = $data['head_approvers'] ? json_encode($data['head_approvers']) : "";

                $transfer_logs_data['transferred_by'] =  Auth::user()->id;

                EmployeeTransfer::create($transfer_logs_data);

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

        $transfer_employee_logs = EmployeeTransfer::with('new_company','new_department','new_location','previous_company','previous_department','previous_location')->where('employee_id',$employee->id)->orderBy('new_date_hired','ASC')->get();
        
        if($transfer_employee_logs){
            foreach($transfer_employee_logs as $key => $transfer_employee_log){
                $transfer_employee_logs[$key] = $transfer_employee_log;
                $transfer_employee_logs[$key]['previous_system_approvers'] = json_decode($transfer_employee_log['previous_system_approvers']);
                $transfer_employee_logs[$key]['new_system_approvers'] = json_decode($transfer_employee_log['new_system_approvers']);
            }
        }
        return $transfer_employee_logs;

    }

    public function orgChart(Employee $employee){

        $api = Api::first();
        $user = $employee;
        $datas = '';
        if($employee->id){
            $rUrl =  $api->api_link.$employee->id;
            $datas = file_get_contents($rUrl);
        }

        return view('org_chart',compact('datas','user'));
        
    }

}
