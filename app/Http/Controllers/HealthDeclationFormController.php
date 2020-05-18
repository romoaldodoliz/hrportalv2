<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Employee;

class HealthDeclationFormController extends Controller
{
    public function index(){
        return view('health_declartion_form.index');
    }

    public function fetchEmployees(Request $request){
        $keyword = $request->keyword;
        $employee = Employee::select('id','first_name','last_name','position','mobile_number')->with('companies','departments','locations')
                            ->where('first_name', 'like' , '%' .  $keyword . '%')
                            ->orWhere('last_name', 'like' , '%' .  $keyword . '%')
                            ->where('status','=','Active')
                            ->orderBy('last_name','DESC')
                            ->get();
        return $employee;
    }
}
