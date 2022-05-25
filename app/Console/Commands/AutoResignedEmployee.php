<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\UploadPdf;
use App\Employee;

class AutoResignedEmployee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:auto_resigned_employee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto resigned employees';

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
        $auto_resigned_employee = $this->set_status_resigned();
        $this->info($auto_resigned_employee);
    }

    public function set_status_resigned(){
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
            return $x . ' resigned employees';
        }
        
    }   
}
