<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Employee;
use App\Dependent;

class ReportController extends Controller
{
    public function employeeDependentReports(){
        return view('reports.employee_dependent_reports');
    }

    public function employeeDependentReportsData(){ 
        $employees = Employee::select('id','first_name','last_name','position')->with('companies','departments','locations','dependents')->whereHas('dependents')->where('status','Active')->orderBy('last_name','ASC')->get();
        
        $employee_depedents = [];
        if($employees){
            $x = 0;
            foreach($employees as $k=>$item){

                $id =  $item['id'];
                $first_name =  $item['first_name'];
                $last_name =  $item['last_name'];
                $position =  $item['position'];
                $companies =  $item['companies'][0]['name'];
                $departments =  $item['departments'][0]['name'];
                $locations =  $item['locations'][0]['name'];

                //Dependents
                foreach($item['dependents'] as $item_dependent){
                    $employee_depedents[$x]['id'] = $id;
                    $employee_depedents[$x]['first_name'] = $first_name;
                    $employee_depedents[$x]['last_name'] = $last_name;
                    $employee_depedents[$x]['position'] = $position;
                    $employee_depedents[$x]['companies'] = $companies;
                    $employee_depedents[$x]['departments'] = $departments;
                    $employee_depedents[$x]['locations'] = $locations;

                    $employee_depedents[$x]['dependent_id'] = $item_dependent['id'];
                    $employee_depedents[$x]['dependent_name'] = $item_dependent['dependent_name'];
                    $employee_depedents[$x]['dependent_relation'] = $item_dependent['relation'];
                    $employee_depedents[$x]['dependent_bdate'] = $item_dependent['bdate'];
                    $employee_depedents[$x]['dependent_gender'] = $item_dependent['dependent_gender'];
                    $employee_depedents[$x]['dependent_status'] = $item_dependent['dependent_status'];
                    $employee_depedents[$x]['dependent_created_date'] = date('Y-m-d h:m A',strtotime($item_dependent['created_at']));

                    $x++;
                }
                
            }
        }
        return $data = [
            'employee_dependents' => $employee_depedents,
            'total_count' => $employees->count()
        ];
    }
}
