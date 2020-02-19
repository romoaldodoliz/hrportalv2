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
        return Employee::with('companies','departments','locations')->orderBy('employee_number', 'ASC')->get();
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
            $user->password = Hash::make($request->input('first_name').".".$request->input('last_name'));
            $user->name =  $request->input('first_name') . " " . $request->input('last_name');
            $user->email = $request->input('first_name').".".$request->input('last_name')."@lafilgroup.com";
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

        if(empty($request->date_hired)){
            $data['date_hired'] = null;
        }
        
        if(empty($request->date_regularized)){
            $data['date_regularized'] = null;
        }
        if(empty($request->date_resigned)){
            $data['date_resigned'] = null;
        }

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

        $this->validate($request, [
            'last_name' => 'required',
            'nick_name' => 'required',
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
            
            $date_time = Carbon::now()->format('M_d_Y_h_m_s');

            if($request->file('marital_status_attachment')){
                $attachment = $request->file('marital_status_attachment');   
                $filename = $employee->id . '_' . $date_time . '_' . $attachment->getClientOriginalName();
                $path = Storage::disk('public')->putFileAs('marital_attachments/temps', $attachment , $filename);
                $employee_data['marital_status_attachment'] =  $filename;
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

    public function employeeIdIndex(){
        session(['header_text' => 'Employees']);
        $employee = Employee::select('id','employee_number','employee_number', 'first_name', 'last_name')->with('companies','departments','locations','print_id_logs','verification')->orderBy('employee_number','ASC')->get();
        return $employee;
    }

    public function employeeFilter(Request $request){

        // $request->all();
        $company = $request->company;
        $department = $request->department;
        $location = $request->location;
        
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
                    ->orderBy('employee_number','DESC')->get();
        return $employee;
    }

    public function print_id(Employee $employee){

        $employee =  Employee::with('departments','locations')->where('id',$employee->id)->first();
        
        $address = "";            
        if($employee->locations->first()->id){
            $location = Location::with('addresses')->where('id',$employee->locations->first()->id)->first();
            $address = isset($location->addresses->first()->name) ? $location->addresses->first()->name : '';
        }

        // function RotatedImage($file,$x,$y,$w,$h,$angle)
        // {
        //     //Image rotated around its upper-left corner
        //     Fpdf::Rotate($angle,$x,$y);
        //     Fpdf::Image($file,$x,$y,$w,$h);
        //     Fpdf::Rotate(0);
        // }

        function departmentColor($color){
            
            $color_arr = [];
            if($color == 'grey'){
                $color_arr['r'] = 128;
                $color_arr['g'] = 128;
                $color_arr['b'] = 128;
            }
            elseif($color == 'green'){
                $color_arr['r'] = 0;
                $color_arr['g'] = 128;
                $color_arr['b'] = 0;
            }
            elseif($color == 'orange'){
                $color_arr['r'] = 255;
                $color_arr['g'] = 165;
                $color_arr['b'] = 0;
            }
            elseif($color == 'red'){
                $color_arr['r'] = 255;
                $color_arr['g'] = 0;
                $color_arr['b'] = 0;
            }
            elseif($color == 'violet'){
                $color_arr['r'] = 238;
                $color_arr['g'] = 130;
                $color_arr['b'] = 238;
            }
            elseif($color == 'yellow'){
                $color_arr['r'] = 255;
                $color_arr['g'] = 255;
                $color_arr['b'] = 0;
            }else{
                $color_arr['r'] = 255;
                $color_arr['g'] = 255;
                $color_arr['b'] = 255;
            }
            return $color_arr;
        }
        // return $employee;
        Fpdf::AddPage("P", [53.98,85.60]);

        Fpdf::AddFont('Avenir-Bold','','AvenirNextCondensed-Bold.php');
        Fpdf::AddFont('Avenir-Regular','','AvenirNextCondensed-Regular.php');

        Fpdf::SetMargins(0,0,0,0);
        Fpdf::SetAutoPageBreak(false);
        
        Fpdf::SetFont('Courier', 'B', 18);

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
                Fpdf::Image(url("storage/id_image/temp_employee_image/") .'/'. $employee->id.'.png', 11.5, 25, 30, 30,'PNG');
            }
        }

        //Front BG
        if (file_exists( 'storage/id_image/new_id_image/front.png')){
            Fpdf::Image(url("storage/id_image/new_id_image/front.png"), 0, 0, 53.98, 85.60,'PNG');
        }
    
      
        Fpdf::Image(url("storage/id_image/new_id_image/lfuggoc_logo.png"), 0, 0, 53.98, 25,'PNG');
        

        $fullname_font = 9;
        $middle_name = $employee->middle_initial ? $employee->middle_initial .'. ' : '';
        if($employee->name_suffix == '-'){
            $suffix = '';
        }else{
            $suffix = $employee->name_suffix ? $employee->name_suffix : '';
        }
       
        $full_name = ucfirst($employee->first_name) .' ' .  $middle_name . $employee->last_name . ' ' .$suffix;

        
        
        Fpdf::SetFont('Avenir-Bold','',$fullname_font);
        Fpdf::SetXY(0,63);
        Fpdf::SetTextColor(3,119, 57);
        Fpdf::MultiCell(40,3, ucwords($full_name) ,0,'L');

        $current_y = Fpdf::gety();
        Fpdf::SetXY(0,$current_y + 0.5);
        Fpdf::SetTextColor(0,0,0);
        Fpdf::SetFont('Avenir-Regular', '', 7);
        Fpdf::MultiCell(35,2.5, $employee->position ? $employee->position : " " ,0,'L');

        $current_y = Fpdf::gety();
        Fpdf::SetXY(0,$current_y + 1.5);
        Fpdf::SetFont('Avenir-Regular', '', 6);
        Fpdf::MultiCell(35,2.5, 'ID No.: ' . $employee->employee_number ,0,'L');

        Fpdf::SetXY(0,76.5);
        Fpdf::SetFont('Avenir-Bold', '', 6);
        if(!empty($employee->division) && $employee->division != "null"){
            Fpdf::MultiCell(35,2.5, $employee->division ? $employee->division : " " ,0,'L');
        }
       

       
        Fpdf::SetXY(0,79);
        Fpdf::SetFont('Avenir-Regular', '', 6);
        Fpdf::MultiCell(35,2.5, $employee->departments[0]['name'] ,0,'L');

        $current_y = Fpdf::gety();
        Fpdf::SetXY(1.3,$current_y + 0.5);
        $color = departmentColor($employee->departments[0]['color']);
        Fpdf::SetFillColor( $color['r'], $color['g'], $color['b']);
        Fpdf::MultiCell(7,1.5, " ",0,'L',true);

        

        if (file_exists('storage/id_image/employee_signature/' . $employee->id.'.png')){

            $signature = 'storage/id_image/employee_signature/' . $employee->id . '.png';
            $destinationPath = storage_path('app/public/id_image/temp_employee_signature/');
    
            if(!File::isDirectory($destinationPath)){
                File::makeDirectory($destinationPath, 0777, true, true);
            }
    
            $img = Image::make($signature);
    
            $img->resize(500, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'. $employee->id . '.png');
    
            // if (file_exists('storage/id_image/temp_employee_signature/' . $employee->id.'.png')){
            //     RotatedImage(url("storage/id_image/temp_employee_signature/") . '/' . $employee->id.'.png',30, 67, 22, 8,15);
            // }
        }

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
                Fpdf::Image(url("storage/id_image/temp_company/") .'/'. $employee['companies'][0]->id.'.png', 15, 13, 25, 25,'PNG');
            }
            
        }

        //SSS, TIN, PHIC, HDMF

        Fpdf::SetFillColor(255,255,255);

        
        Fpdf::SetXY(1.3,38);

        Fpdf::SetFont('Arial', 'B', 5);
        Fpdf::SetDrawColor(253,209,62);
        Fpdf::Cell(26,21,'','R',1,'R',true);

        Fpdf::SetXY(1.3,39);
        Fpdf::Cell(26,2,'SSS',0,1,'R');
      

        Fpdf::SetFont('Arial', '', 5);
        Fpdf::SetX(1.3);
        Fpdf::Cell(26,2,$employee->sss_number,0,2,'R');

        Fpdf::Ln(1);

        Fpdf::SetFont('Arial', 'B', 5);
        Fpdf::SetX(1.3);
        Fpdf::Cell(26,2,'TIN',0,2,'R');

        Fpdf::SetFont('Arial', '', 5);
        Fpdf::SetX(1.3);
        Fpdf::Cell(26,2,$employee->tax_number,0,2,'R');

        Fpdf::Ln(1);

        Fpdf::SetFont('Arial', 'B', 5);
        Fpdf::SetX(1.3);
        Fpdf::Cell(26,2,'PHIC',0,2,'R');

        Fpdf::SetFont('Arial', '', 5);
        Fpdf::SetX(1.3);
        Fpdf::Cell(26,2,$employee->phil_number,0,2,'R');

        Fpdf::Ln(1);

        Fpdf::SetFont('Arial', 'B', 5);
        Fpdf::SetX(1.3);
        Fpdf::Cell(26,2,'HDMF',0,2,'R');

        Fpdf::SetFont('Arial', '', 5);
        Fpdf::SetX(1.3);
        Fpdf::Cell(26,2,$employee->tax_number,0,2,'R');

        Fpdf::SetFont('Arial', 'B',5);
        Fpdf::SetXY(27.3,39);
        Fpdf::Cell(26,2,'IN CASE OF EMERGENCY',0,1,'L');

        Fpdf::Ln(1);

        Fpdf::SetFont('Arial', 'B',5);
        Fpdf::SetX(27.3);
        Fpdf::MultiCell(26,2, $employee->contact_person ,0,'L');

        Fpdf::SetFont('Arial', '',5);
        Fpdf::SetX(27.3);
        Fpdf::Cell(26,2,$employee->contact_number,0,1,'L');

        Fpdf::Ln(2);

        Fpdf::SetFont('Arial', 'B',5);
        Fpdf::SetX(27.3);
        Fpdf::Cell(26,2,'EMPLOYEE ADDRESS ',0,1,'L');

        $current_y = Fpdf::gety();
        Fpdf::SetFont('Arial', '',5);
        Fpdf::SetXY(27.3,$current_y +1);
        Fpdf::MultiCell(25,2, $employee->current_address ,0,'L');

       
        Fpdf::SetTextColor(3,119, 57);
        Fpdf::SetFont('Arial', '',10);
        Fpdf::SetXY(2,63);
        Fpdf::MultiCell(50,3, 'www.lafilgroup.com' ,0,'C',true);
        
        $current_y = Fpdf::gety();
        Fpdf::SetTextColor(3,119, 57);
        Fpdf::SetFont('Arial', '',8);
        Fpdf::SetXY(2,$current_y += 1);
        Fpdf::MultiCell(50,2.5, '(02) 8516-73-62' ,0,'C',true);


        Fpdf::SetTextColor(1,1, 1);
        Fpdf::SetFont('Arial', '',6);
        Fpdf::SetXY(2,$current_y += 4);

        if($address){
            Fpdf::MultiCell(50,2, $address ,0,'C',true);
        }

        Fpdf::SetTextColor(3,119, 57);
        
        Fpdf::SetXY(7,80);
        Fpdf::SetFont('Arial', 'B',6);
        Fpdf::Cell(13,1.5,"INTEGRITY",0,1,'C');

        Fpdf::SetXY(21,80);
        Fpdf::Cell(13,1.5,"LOYALTY",0,1,'C');

        Fpdf::SetXY(36,80);
        Fpdf::Cell(13,1.5,"EXCELLENCE",0,1,'C');

        Fpdf::Output(utf8_decode($employee->last_name) .'_' . utf8_decode($employee->first_name) . '_' . $employee->employee_number  . ".pdf", 'I');
        exit();

    }

    public function print_id_logs(Request $request){
        
        $employee = Employee::where('id',$request->employee_id)->first();

        if($employee){
            $print_id_logs_data = [];
            $print_id_logs_data['employee_id'] = $employee->id;
            $print_id_logs_data['user_id'] = Auth::user()->id;
            $print_id_logs_data['remarks'] = 'Print on ' . date('Y-m-d') . ' at ' . date('h:m:s');

            PrintIdLog::create($print_id_logs_data);
        }

        return $employee;
    }
}
