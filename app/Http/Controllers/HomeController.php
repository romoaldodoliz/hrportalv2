<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Employee;
use App\EmployeeApprovalRequest;
use App\EmployeeDetailVerification;

use DB;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
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
        
        if(auth()->user()->roles[0]->name){
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
            else{
                return redirect('/user_profile');
            }
        }else{
            return redirect('/user_profile');
        }
    }

    public function user_profile(){
        return view('user_profile')->with('employee');
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

    public function verifiedEmployees(){
        return EmployeeDetailVerification::with('employee')->orderBy('created_at','DESC')->get();
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
    
}
