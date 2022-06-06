<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Employee;
use App\AssignHead;

class AutoTransferResignedEmployee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:auto_transfer_resigned_employee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Transfer Resigned Employee';

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
        $auto_transfer = $this->transfer_all_level_employees();
        $this->info($auto_transfer);
    }

    public function transfer_all_level_employees(){

        $date_today = date('Y-m-d');
        $resigned_employees_today = Employee::with('immediate_superior')->whereDate('date_resigned',$date_today)->where('status','Inactive')->get();

        $updated_count = 0;
        if($resigned_employees_today){

            foreach($resigned_employees_today as $item){
                if($item['immediate_superior']){
                    $employee_head_id = $item['immediate_superior'][0]['employee_head_id'];

                    $employee_under = AssignHead::with('employee_info')
                                        ->where('employee_head_id',$item['id'])
                                        // ->where('head_id','3')
                                        ->whereHas(
                                            'employee_info',function($q) {
                                                $q->where('status','Active');
                                        })
                                        ->count();

                    if($employee_under > 0 && $employee_head_id){

                        $current_approvers = AssignHead::with('employee_info')
                                            ->where('employee_head_id',$item['id'])
                                            // ->where('head_id','3')
                                            ->whereHas('employee_info',function($q) {
                                                $q->where('status','Active');
                                            })
                                            ->get();

                        if($current_approvers){
                            foreach($current_approvers as $approver){
                                $approver->update(['employee_head_id'=>$employee_head_id]);
                                $updated_count += 1;
                            }
                        }
                    }
                }
            }
        }

        return 'Employee Resigned with transfer : ' . $updated_count;
    }
}
