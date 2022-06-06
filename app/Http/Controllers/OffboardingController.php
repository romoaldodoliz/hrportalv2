<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UploadPdf;
use App\Employee;
use App\ResetLog;
use App\ClearanceSignatory;
use Auth;
use DB;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ClientException;

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
        return UploadPdf::with('clearance.signatories.user','clearance.signatories.department','letter','employee.departments','employee.locations','employee.companies','cancelled_by')->orderBy('updated_at','DESC')->get();
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
                    'cancel_remarks'=>$request->cancel_remarks,
                ]);
                DB::commit();

               

                $upload_pdf = UploadPdf::with('user','clearance.signatories.user','clearance.signatories.department','letter','employee.departments','employee.locations','employee.companies','cancelled_by')
                                        ->where('id',$request->id)
                                        ->first();
                if($upload_pdf){
                    // $message_to_cancel = "<p> Hi! ".$upload_pdf->employee->first_name.",  please be advised that we cancelled your resignation letter due to:</p>
                    //                     <hr>
                    //                     <ul>
                    //                         <li>Remarks : ".$request->cancel_remarks."</li>
                    //                     </ul>
                    //                     <p>Cancelled By : ".Auth::user()->email."</p>
                    //                     <small><i>Note: This is an auto generated message please do not reply</i></small>";
                                        
                    // $send_webex_to_admin = $this->sendWebexMessage($upload_pdf->user->email,$message_to_cancel);

                    $message_to_cancel_to_group =  "<p> HR cancelled a resignation letter, check details below:</p>
                                                    <ul>
                                                        <li>Email : ".$upload_pdf->user->email."</li>
                                                        <li>Remarks : ".$request->cancel_remarks."</li>
                                                        <li>Date : ".date('Y-m-d h:i A')."</li>
                                                    </ul>
                                                    <p>Cancelled By : ".Auth::user()->email."</p><hr>";
                                                    
                    $send_webex = $this->sendGroupWebexMessage($message_to_cancel_to_group);
                }
                
                
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



    public function notifyGroupOnceEmployeeResigned(Request $request){

        $upload_pdfs = UploadPdf::with('employee.departments','employee.locations','employee.companies')
                                    ->whereDate('created_at','>=',date('Y-m-d'))
                                    ->where(function($q){
                                        $q->where('notification_webex','!=','1');
                                        $q->orWhere('notification_webex',null);
                                    })
                                    ->where(function($q){
                                        $q->where('cancel','!=','1');
                                        $q->orWhere('cancel',null);
                                    })
                                    ->get();
        
        if(count($upload_pdfs) > 0){

            foreach($upload_pdfs as $k => $item){
                $message = "<span>Resignation Letter has been uploaded. Please check details below. Thank you. </span>
                            <ul>
                                <li>Employee: ".$item['employee']['first_name'] . ' ' . $item['employee']['last_name']."</li>
                                <li>Position: ".$item['employee']['position']."</li>
                                <li>Company: ".$item['employee']['companies'][0]['name']."</li>
                                <li>Department: ".$item['employee']['departments'][0]['name']."</li>
                                <li>Location: ".$item['employee']['locations'][0]['name']."</li>
                                <li>Type: ".$item['type']."</li>
                                <li>Date: ".$item['created_at']."</li>
                            </ul>
                            <p>Link : http://10.96.4.126:8668/uploaded-pdf</p>
                            <hr>";
                $send_webex = $this->sendGroupWebexMessage($message);
                UploadPdf::where('id',$item['id'])->update(['notification_webex'=>'1']);
            }
            return 'sent';
        }

    }

    public function sendGroupWebexMessage($message){

        $httpClient = new Client(); 

        if($message){
            $body = [
                //prod
                'roomId' => 'Y2lzY29zcGFyazovL3VzL1JPT00vMDVlNjYzODAtZGM5Ny0xMWVjLWFmNDMtYzFhMzRjODhkYjBh',
                'html' => $message
            ];

            try{
                $response = $httpClient->post(
                    'https://api.ciscospark.com/v1/messages',
                    [
                        RequestOptions::BODY => json_encode($body),
                        RequestOptions::HEADERS => [
                            'Content-Type' => 'application/json',
                            //prod
                            'Authorization' => 'Bearer NmI3NTIzNzctNzI1NC00M2I1LThmMDctNzliNzg2ZGM2NzMyYjdkZGE0Y2UtOTdh_PF84_72c16376-f5a4-4a5c-ad51-a60a7b78a790',
                        ],
                    ]
                );

            return 'sent';

            }catch(ServerException $e){
                return 'not';
            }
            catch(RequestException $e){
                return 'not';
            }
            catch(ConnectException $e){
                return 'not';
            }
            catch(ClientException $e){
                return 'not';
            }

        }
    }

    public function sendWebexMessage($email,$message){

        $httpClient = new Client(); 

        if($email && $message){
            $body = [
                'toPersonEmail' => $email,
                'html' => $message
            ];

            try{
                $response = $httpClient->post(
                    'https://api.ciscospark.com/v1/messages',
                    [
                        RequestOptions::BODY => json_encode($body),
                        RequestOptions::HEADERS => [
                            'Content-Type' => 'application/json',
                            //prod
                            'Authorization' => 'Bearer NmI3NTIzNzctNzI1NC00M2I1LThmMDctNzliNzg2ZGM2NzMyYjdkZGE0Y2UtOTdh_PF84_72c16376-f5a4-4a5c-ad51-a60a7b78a790',
                        ],
                    ]
                );

            return 'sent';

            }catch(ServerException $e){
                return 'not';
            }
            catch(RequestException $e){
                return 'not';
            }
            catch(ConnectException $e){
                return 'not';
            }
            catch(ClientException $e){
                return 'not';
            }

        }
    }

    public function resetClearanceSignatory(Request $request){
        
        $data = $request->all();
        DB::beginTransaction();
        try {
            $clearance_signatory = ClearanceSignatory::where('id',$data['id'])->first();
            if($clearance_signatory){
                $new_reset_fields = [
                    'status' => null,
                    'amount' => null,
                    'accountabilities' => null,
                    'date_verified' => null,
                    'reset_by' => Auth::user()->id,
                ];
                $old_reset_fields = [
                    'status' => $clearance_signatory->status,
                    'amount' => $clearance_signatory->amount,
                    'accountabilities' => $clearance_signatory->accountabilities,
                    'date_verified' => $clearance_signatory->date_verified,
                    'reset_by' => Auth::user()->id,
                ];

                if($clearance_signatory->update($new_reset_fields)){

                    //Save Reset Logs
                    $reset_logs_data = [
                        'user_id'=>Auth::user()->id,
                        'reset_id'=>$request->id,
                        'old'=>json_encode($old_reset_fields,true),
                        'new'=>json_encode($new_reset_fields,true),
                    ];
                    ResetLog::create($reset_logs_data);
                    
                    DB::commit();
                    $clearance_signatory_data = ClearanceSignatory::with('user','department')
                                                                    ->where('id',$request->id)
                                                                    ->first();
                    return $response = [
                        'status'=>'saved',
                        'clearance_signatory_data'=>$clearance_signatory_data
                    ];
                }
            }
        }catch (Exception $e) {
            DB::rollBack();
            return $response = [
                'status'=>'error'
            ];
        }
    }

}
