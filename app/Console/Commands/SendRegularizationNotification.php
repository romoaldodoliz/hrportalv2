<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


use Illuminate\Support\Facades\Mail;

use App\Mail\EmployeeRegularizationNotification;

use App\Employee;
use App\AssignHead;
use App\User;


class SendRegularizationNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_email:regularization_notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email employee regularization notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sent_email_notification = $this->send_regularization_notification();
        $this->info($sent_email_notification);
    }

    public function send_regularization_notification(){
        $data = [];
        

        $all_employees = Employee::select('id','first_name','last_name','date_hired','position','classification','status')->with('companies')->where('classification','Probationary')->where('status','Active')->orderBy('date_hired','DESC')->get();
        
        if($all_employees){
            
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
                        $data = [];
                        $data['id'] =  $employee['id'];
                        $data['employee_name'] =  $employee['first_name'] . ' ' . $employee['last_name'];
                        $data['company'] =  $employee['companies'] ? $employee['companies'][0]['name'] : "";
                        $data['position'] =  $employee['position'];
                        $data['date_hired'] =  date('F m, Y',strtotime($employee['date_hired']));
                        $data['classification'] =  $employee['classification'];
                        $data['status'] =  $employee['status'];
                        $data['months'] =  $months;
                        $data['reciever_name'] =  $immediate_superior_details ? $immediate_superior_details['first_name'] . ' ' . $immediate_superior_details['last_name']  : "";
                        $data['email_reciever'] =  $email ? $email['email'] : "";

                        $date_of_regularization = date('Y-m-d', strtotime("+6 months", strtotime($employee['date_hired'])));
                        $data['date_of_regularization'] =  $date_of_regularization ? date('F m, Y',strtotime($date_of_regularization)) : "";

                        if($data['email_reciever']){
                            Mail::to('jay.lumagdong@gmail.com')->send(new EmployeeRegularizationNotification($data));
                        }
                       

                    }
                }   
            }
        }

        return 'Email Sent';
    }

}
