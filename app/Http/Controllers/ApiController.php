<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\AssignHead;

class ApiController extends Controller
{
    public function orgChartData(Request $request){
        if($request->user_id){
            
            $employee = Employee::select('id','user_id','first_name','last_name','middle_name','position','level','cluster','new_cluster')
                                        ->with('companies','departments')
                                        ->where('user_id',$request->user_id)
                                        ->first();
            
            $first_level = AssignHead::with('unders.unders')
                                        ->whereHas('employee_info',function($q){
                                            $q->where('status','Active');
                                        })
                                        ->where('employee_head_id',$employee->id)
                                        ->get();
            $employee_ids = [];                            
            if($first_level){
                foreach($first_level as $under)
                {
                    if($under->employee_info->status == "Active"){
                        array_push($employee_ids , $under->employee_id);
                    }
                }
            }

            $employees = Employee::select('id','user_id','first_name','last_name','middle_name','position','level','cluster','new_cluster')
                                        ->with('companies','departments')
                                        ->whereIn('id',$employee_ids)
                                        ->get();

            return $response = [
                'employee'=>$employee,
                'orgchart'=>$employees
            ];
        }
    }
}
