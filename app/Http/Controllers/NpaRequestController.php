<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use setasign\Fpdi\Fpdi;

use App\EmployeeNpaRequest;

class NpaRequestController extends Controller
{
    public function printEmployeeNPARequest(EmployeeNpaRequest $employee){

        $npa_request = EmployeeNpaRequest::with('from_company','from_location','from_immediate_manager','from_department','to_company','to_location','to_immediate_manager','to_department','prepared_by','recommended_by','approved_by','bu_head')->where('id',$employee->id)->first();
        $npa_request_data = json_encode($npa_request,true); 
        $npa_request_data = json_decode($npa_request_data,true); 

        $pdf = new Fpdi();
        $pdf->AddPage('P','A4');
        $pdf->setSourceFile("npa/npa_template_2.pdf");
        $tplIdx = $pdf->importPage(1);
        $pdf->useTemplate($tplIdx, 0, 0, 220);

        //Header
        $pdf->SetFont('Helvetica','','9');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(153, 67.5);
        $pdf->Write(0, $npa_request_data['ctrl_no']);

        $pdf->SetFont('Helvetica','','9');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(60, 72.5);
        $pdf->Write(0, $npa_request_data['date_prepared']);

        $pdf->SetFont('Helvetica','','9');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(60, 77.9);
        $pdf->Write(0, $npa_request_data['subject']);

        $pdf->SetFont('Helvetica','','9');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(60, 83);
        $pdf->Write(0, $npa_request_data['employee_name']);


        $line_height = 3.2;
        $width = 52.5;

        //From Company
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(78.5, 98.1);

        $from_company = $npa_request_data['from_company'] ? $npa_request_data['from_company']['name'] : "";    
        $height = (ceil(($pdf->GetStringWidth($from_company) / $width)) * $line_height);
        if($height == $line_height){
            $height  = $height * 2;
        }else{
            $height = $line_height;
        }
        $pdf->Multicell($width,$height,$from_company,0,'L');

        //To Company
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(78.5 + 52.5, 98.1);

        $to_company = $npa_request_data['to_company'] ? $npa_request_data['to_company']['name'] : "";    
        $height = (ceil(($pdf->GetStringWidth($to_company) / $width)) * $line_height);
        if($height == $line_height){
            $height  = $height * 2;
        }else{
            $height = $line_height;
        }
        $pdf->Multicell($width,$height,$to_company,0,'L');

        //From Position
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(78.5, 104.5);

        $from_position_title = $npa_request_data['from_position_title'] ? $npa_request_data['from_position_title'] : "";    
        $height = (ceil(($pdf->GetStringWidth($from_position_title) / $width)) * $line_height);
        if($height == $line_height){
            $height  = $height * 2;
        }else{
            $height = $line_height;
        }
        $pdf->Multicell($width,$height,$from_position_title,0,'L');

        //To Position
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(78.6 + 52.6, 104.6);

        $to_position_title = $npa_request_data['to_position_title'] ? $npa_request_data['to_position_title'] : "";    
        $height = (ceil(($pdf->GetStringWidth($to_position_title) / $width)) * $line_height);
        if($height == $line_height){
            $height  = $height * 2;
        }else{
            $height = $line_height;
        }
        $pdf->Multicell($width,$height,$to_position_title,0,'L');

        //Date hired
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(78.6, 111);
        $date_hired = $npa_request_data['date_hired'];    
        $height = (ceil(($pdf->GetStringWidth($date_hired) / $width)) * $line_height);
        if($height == $line_height){
            $height  = $height * 2;
        }else{
            $height = $line_height;
        }
        $pdf->Multicell($width,$height,$date_hired,0,'L');

        //From Immediate Manager
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(78.6, 117.5);

        $from_immediate_manager = $npa_request_data['from_immediate_manager'] ? $npa_request_data['from_immediate_manager']['first_name'] . ' ' . $npa_request_data['from_immediate_manager']['last_name'] : "";    
        $height = (ceil(($pdf->GetStringWidth($from_immediate_manager) / $width)) * $line_height);
        if($height == $line_height){
            $height  = $height * 2;
        }else{
            $height = $line_height;
        }
        $pdf->Multicell($width,$height,$from_immediate_manager,0,'L');

        //To Immediate Manager
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(78.6 + 52.6, 117.5);

        $to_immediate_manager = $npa_request_data['to_immediate_manager'] ? $npa_request_data['to_immediate_manager']['first_name'] . ' ' . $npa_request_data['to_immediate_manager']['last_name'] : "";    
        $height = (ceil(($pdf->GetStringWidth($to_immediate_manager) / $width)) * $line_height);
        if($height == $line_height){
            $height  = $height * 2;
        }else{
            $height = $line_height;
        }
        $pdf->Multicell($width,$height,$to_immediate_manager,0,'L');


        //From Department
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(78.6, 124);
        $from_department = $npa_request_data['from_department'] ? $npa_request_data['from_department']['name']: "";    
        $height = (ceil(($pdf->GetStringWidth($from_department) / $width)) * $line_height);
        if($height == $line_height){
            $height  = $height * 2;
        }else{
            $height = $line_height;
        }
        $pdf->Multicell($width,$height,$from_department,0,'L');

        //To Department
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(78.6 + 52.6, 124);
        $to_department = $npa_request_data['to_department'] ? $npa_request_data['to_department']['name']: "";    
        $height = (ceil(($pdf->GetStringWidth($to_department) / $width)) * $line_height);
        if($height == $line_height){
            $height  = $height * 2;
        }else{
            $height = $line_height;
        }
        $pdf->Multicell($width,$height,$to_department,0,'L');

        //Effectivity
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(78.6, 130.5);
        $effectivity_date = $npa_request_data['effectivity_date'];    
        $height = (ceil(($pdf->GetStringWidth($effectivity_date) / $width)) * $line_height);
        if($height == $line_height){
            $height  = $height * 2;
        }else{
            $height = $line_height;
        }
        $pdf->Multicell($width,$height,$effectivity_date,0,'L');

        //From Basic Salary
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(78.8, 137);
        $from_monthly_basic_salary = $npa_request_data['from_monthly_basic_salary'];    
        $height = (ceil(($pdf->GetStringWidth($from_monthly_basic_salary) / $width)) * $line_height);
        if($height == $line_height){
            $height  = $height * 2;
        }else{
            $height = $line_height;
        }
        $pdf->Multicell($width,$height,$from_monthly_basic_salary,0,'L');

        //To Basic Salary
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(78.8 + 52.6, 137);
        $to_monthly_basic_salary = $npa_request_data['to_monthly_basic_salary'];    
        $height = (ceil(($pdf->GetStringWidth($to_monthly_basic_salary) / $width)) * $line_height);
        if($height == $line_height){
            $height  = $height * 2;
        }else{
            $height = $line_height;
        }
        $pdf->Multicell($width,$height,$to_monthly_basic_salary,0,'L');


        //Prepare By
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(27, 157);
        $pdf->Multicell(58,3.2,$npa_request_data['prepared_by'] ? $npa_request_data['prepared_by']['first_name'] . ' ' . $npa_request_data['prepared_by']['last_name'] : "",0,'L');
        $getY = $pdf->getY();
        $pdf->SetXY(27,  $getY);
        $pdf->Multicell(58,3.2,$npa_request_data['prepared_by'] ? $npa_request_data['prepared_by']['position'] : "",0,'L');

        //Recommended By
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(112, 157);
        $pdf->Multicell(58,3.2,$npa_request_data['recommended_by'] ? $npa_request_data['recommended_by']['first_name'] . ' ' . $npa_request_data['recommended_by']['last_name'] : "",0,'L');
        $getY = $pdf->getY();
        $pdf->SetXY(112,  $getY);
        $pdf->Multicell(58,3.2,$npa_request_data['recommended_by'] ? $npa_request_data['recommended_by']['position'] : "",0,'L');

        //Recommended for approval
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(27, 178);
        $pdf->Multicell(58,3.2,$npa_request_data['approved_by'] ? $npa_request_data['approved_by']['first_name'] . ' ' . $npa_request_data['approved_by']['last_name'] : "",0,'L');
        $getY = $pdf->getY();
        $pdf->SetXY(27,  $getY);
        $pdf->Multicell(58,3.2,$npa_request_data['approved_by'] ? $npa_request_data['approved_by']['position'] : "",0,'L');

        //BU Head
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(112, 178);
        $pdf->Multicell(58,3.2,$npa_request_data['bu_head'] ? $npa_request_data['bu_head']['first_name'] . ' ' . $npa_request_data['bu_head']['last_name'] : "",0,'L');
        $getY = $pdf->getY();
        $pdf->SetXY(112,  $getY);
        $pdf->Multicell(58,3.2,$npa_request_data['bu_head'] ? $npa_request_data['bu_head']['position'] : "",0,'L');

        //Employee Name
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(27, 216);
        $pdf->Multicell(58,3.2, $npa_request_data['employee_name'],0,'L');

        //Date
        $pdf->SetFont('Helvetica','','7');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(37, 221);
        $pdf->Multicell(40,3.2, date("Y-m-d"),0,'C');

        $pdf->Output('I', 'NPA_Requests.pdf');
    }
}
