<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UploadPdf;
use App\Employee;
use Auth;
use DB;
class OffboardingController extends Controller
{
    public function set_status_resigned(Request $request){
        $date_today = date('Y-m-d');
        $last_one_months = date('Y-m-d',strtotime("-3 months"));
        $expired_employees = UploadPdf::where('cancel',null)
                                                ->where('last_day','>=',$last_one_months)
                                                ->where('last_day','<=',$date_today)
                                                ->where('type','!=','Transfer')
                                                ->get();
        if(count($expired_employees) > 0){
            $x = 0;
            foreach($expired_employees as $expired)
            {
                $employee = Employee::where('user_id',$expired->user_id)->where('status','!=','Inactive')->first();
                if($employee){
                    $employee->status = "Inactive";
                    $employee->date_resigned = $expired->last_day;
                    $employee->save();
                    $x++;
                }
            }
            return $x;
        }
        
    }   

    public function uploaded_pdf(){
        return view('offboarding.uploaded_pdf');
    }

    public function uploaded_pdf_data(){
        return UploadPdf::with('letter','employee.departments','employee.locations','employee.companies','cancelled_by')->orderBy('updated_at','DESC')->get();
    }

    public function cancel_upload_pdf(Request $request){
        $data = $request->all();
        $upload_pdf = UploadPdf::where('id',$data['id'])->first();
        DB::beginTransaction();
        try {
            if($upload_pdf){
                $upload_pdf->update([
                    'cancel'=>1,
                    'cancel_date'=>date('Y-m-d'),
                    'cancel_by'=>Auth::user()->id,
                ]);
                DB::commit();
                $upload_pdf = UploadPdf::with('letter','employee.departments','employee.locations','employee.companies','cancelled_by')
                                        ->where('id',$request->id)
                                        ->first();
                
                return $response = [
                    'status'=>'saved',
                    'upload_pdf'=>$upload_pdf,
                ];

            }
        }catch (Exception $e) {
            DB::rollBack();
            return $response = [
                'status'=>'error'
            ];
        }
    }
}
