<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Employee;
use App\HdfIcEmployee;
use App\HealthDeclarationForm;
use App\HealthDeclarationIcForm;
use App\RfidUser;
use App\HdfEmployee;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;

use Illuminate\Support\Facades\Storage;

class HealthDeclationFormController extends Controller
{
    public function index(){
        return view('health_declartion_form.index');
    }

    public function index_ic(){
        return view('health_declartion_form.index_ic');
    }

    public function fetchEmployees(Request $request){
        $keyword = $request->keyword;
        $employees = Employee::select('id','first_name','last_name','position','mobile_number')->with('companies','departments','locations')
                            ->where(function ($query) use ($keyword) {
                                $query->where('first_name', 'like' , '%' .  $keyword . '%')->orWhere('last_name', 'like' , '%' .  $keyword . '%');
                                $query->orWhereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ["%{$keyword}%"]);
                            })
                            ->where('status','=','Active')
                            ->orderBy('last_name','DESC')
                            ->take(20)
                            ->get();

        if($employees){
            $get_rfid_users = RfidUser::first();
            $get_users = json_decode($get_rfid_users['door_users'],true);
            $get_face_users = json_decode($get_rfid_users['face_users'],true);
            
            foreach($employees as $key => $employee){
                $name = '';
                $user_id = '';
                $face_name = '';
                $face_user_id = '';

                $employee_name = $employee['first_name'] . ' ' . $employee['last_name'];
                $user =  $this->get_user($get_users, $employee_name);
                $face_user =  $this->get_user($get_face_users, $employee_name);

                foreach($user as $user_data){
                    $name = $user_data['name'];
                    $user_id = $user_data['user_id'];
                }
                foreach($face_user as $user_data){
                    $face_name = $user_data['name'];
                    $face_user_id = $user_data['user_id'];
                }
                $employees[$key]['name'] = $name;
                $employees[$key]['user_id'] = $user_id;
                $employees[$key]['face_name'] = $face_name;
                $employees[$key]['face_user_id'] = $face_user_id;

                $already_check_hdf_employee = HdfEmployee::where('employee_id',$employee['id'])->whereDate('created_at',date('Y-m-d'))->first();
                if($already_check_hdf_employee){
                    $employees[$key]['already_check'] = true;
                }else{
                    $employees[$key]['already_check'] = false;
                }

            }
        }


        return $employees;
    }

    public function fetchICEmployees(Request $request){
        $keyword_ic = $request->keyword_ic;
        
        $client = new Client();
        $response = $client->request('GET', 'http://10.96.4.87:8668/api/get-laborers');

        $ic_employees = json_decode($response->getBody(), true);

        $get_ic_employees = $this->get_ic_employee($ic_employees, $keyword_ic);

        if($get_ic_employees){
            foreach($get_ic_employees as $key => $employee){
                $get_ic_employees[$key]['name'] = $employee['name'];
                $get_ic_employees[$key]['agency_name'] = $employee['agency_name'];
                $get_ic_employees[$key]['active'] = $employee['active'];
                $get_ic_employees[$key]['usruid'] = $employee['usruid'];

                $already_check_hdf_employee = HdfIcEmployee::where('employee_id',$employee['usruid'])->whereDate('created_at',date('Y-m-d'))->first();
                if($already_check_hdf_employee){
                    $get_ic_employees[$key]['already_check'] = true;
                }else{
                    $get_ic_employees[$key]['already_check'] = false;
                }
            }

        }

        return $get_ic_employees;
    }

    public function get_ic_employee($get_users, $user){
        $get_users_arr = [];

        $result = array_filter($get_users, function ($item) use ($user) {
            if (stripos($item['name'], $user) !== false) {
                $get_users_arr['name'] = $item['name'];
                $get_users_arr['usruid'] = $item['usruid'];
                $get_users_arr['agency_name'] = $item['agency_name'];
                $get_users_arr['active'] = $item['active'];

                return $get_users_arr;
            }else{
                return $get_users_arr = [];
            }
        });

        return $result;
    }

    public function fetchEmployeesOveride(Request $request){
        $keyword = $request->keyword;

        $this->validate($request, [
            'keyword' => 'required',
        ]);

       $employees = HdfEmployee::where('name', 'like' , '%' .  $keyword . '%')
                            ->whereDate('created_at',date('Y-m-d'))
                            ->orderBy('name','DESC')
                            ->get();

        if($employees){
            $get_rfid_users = RfidUser::first();
            $get_users = json_decode($get_rfid_users['door_users'],true);
            $get_face_users = json_decode($get_rfid_users['face_users'],true);
            
            foreach($employees as $key => $employee){
                $name = '';
                $user_id = '';
                $face_name = '';
                $face_user_id = '';

                $employee_name = $employee['name'];
                $user =  $this->get_user($get_users, $employee_name);
                $face_user =  $this->get_user($get_face_users, $employee_name);

                foreach($user as $user_data){
                    $name = $user_data['name'];
                    $user_id = $user_data['user_id'];
                }
                foreach($face_user as $user_data){
                    $face_name = $user_data['name'];
                    $face_user_id = $user_data['user_id'];
                }
                $employees[$key]['name'] = $employee['name'];
                $employees[$key]['user_id'] = $user_id;
                $employees[$key]['face_name'] = $face_name;
                $employees[$key]['face_user_id'] = $face_user_id;

                //Get Door Access if  Blocked
                // if($user_id){
                //     $card_access = $this->getCardAccess($user_id);

                //     if($card_access['card_list']){
                //         $employees[$key]['card_access_blocked'] =  $card_access['card_list'][0]['is_blocked'];
                //     }else{
                //         $employees[$key]['card_access_blocked'] = '';
                //     }
                // }

                //Get Door Access if  Blocked
                // if($face_user_id){
                //     $face_access = $this->getFaceCardAccess($face_user_id);

                //     if($face_access['card_list']){
                //         $employees[$key]['face_access_blocked'] =  $face_access['card_list'][0]['is_blocked'];
                //     }else{
                //         $employees[$key]['face_access_blocked'] = '';
                //     }
                // }
            }
        }

        return $employees;
    }

    public function fetchEmployeesOverideIC(Request $request){
        $keyword_ic = $request->keyword_ic;

        $this->validate($request, [
            'keyword_ic' => 'required',
        ]);

        $employees = HdfIcEmployee::where('name', 'like' , '%' .  $keyword_ic . '%')
                            ->whereDate('created_at',date('Y-m-d'))
                            ->orderBy('name','DESC')
                            ->get();

        return $employees;
    }

    public function saveDeclaration(Request $request){

        $clientIP = request()->ip();

        $this->validate($request, [
            'temperature' => 'required',
            'one_question' => 'required',
            'two_question' => 'required',
            'three_question' => 'required',
            'four_question' => 'required',
            'five_question' => 'required',
            'six_question' => 'required',
            'seven_question' => 'required',
        ]);

        $data = $request->all();

        if($data){
            $not_allowed = false;
        
            $yes_count = 0;
            if($data['one_question'] == 'Yes'){
                $yes_count++;
            }
            if($data['two_question'] == 'Yes'){
                $yes_count++;
            }

            if($data['three_question'] == 'Yes'){
                $yes_count++;
            }

            if($data['four_question'] == 'Yes'){
                $yes_count++;
            }

            if($data['five_question'] == 'Yes'){
                $yes_count++;
            }

            //Save Data
            $data['date_time'] = date('h:m:s A m/d/Y');
            $data['ip_address'] =  $clientIP;

            $location = '';
            if(stristr($clientIP, '10.97')){
                $location = "BGC";
            }
            else if(stristr($clientIP, '10.96.16') || stristr($clientIP, '10.96.17') || stristr($clientIP, '10.96.18')){
                $location = "Manila";
            }
            else if(stristr($clientIP, '10.96.224') || stristr($clientIP, '10.96.225')){
                $location = "Bulacan";
            }
            else if(stristr($clientIP, '10.96.131') || stristr($clientIP, '10.96.132') || stristr($clientIP, '10.96.133') || stristr($clientIP, '10.96.134') || stristr($clientIP, '10.96.135')){
                $location = "Bataan";
            }
            else if(stristr($clientIP, '10.160.1') || stristr($clientIP, '10.160.2')){
                $location = "Davao";
            }
            else if(stristr($clientIP, '10.128.97') || stristr($clientIP, '10.128.98') || stristr($clientIP, '10.128.99')){
                $location = "Capiz";
            }
            else if(stristr($clientIP, '10.128.129') || stristr($clientIP, '10.128.130') || stristr($clientIP, '10.128.131')){
                $location = "Bacolod";
            }
            else if(stristr($clientIP, '10.96.33') || stristr($clientIP, '10.96.34') || stristr($clientIP, '10.96.35') || stristr($clientIP, '10.96.36')){
                $location = "Isabela";
            }
            else if(stristr($clientIP, '10.128.16') || stristr($clientIP, '10.128.17') || stristr($clientIP, '10.128.36') || stristr($clientIP, '10.128.37') || stristr($clientIP, '10.128.38') || stristr($clientIP, '10.128.39')){
                $location = "Iloilo";
            }
            else{
                $location = "";
            }
            
            if($yes_count >= 1){
  
                $data['status'] = 'Not Allowed';

                $hdf_employee = [];
                $hdf_employee['employee_id'] = $data['employee_id'];
                $hdf_employee['name'] = $data['name'];
                $hdf_employee['dept_bu_position'] = $data['dept_bu_position'];
                $hdf_employee['contact_number'] = $data['contact_number'];
                $hdf_employee['status'] = 'Not Allowed';
                $hdf_employee['date_time'] = date('Y-m-d');
                HdfEmployee::create($hdf_employee);

                HealthDeclarationForm::create($data);

                $message = $data['name'];
                if($location){
                    $message = $data['name'] . ' - Location: ' . $location;
                }else{
                    $message = $data['name'];
                }
                $send_message = $this->send_message($message,"not_allowed");
                
                session([
                    'lockscreen' => [
                        'status'=> 'lock',
                        'lock_data' => $hdf_employee
                    ],
                    
                ]);

                return 'not_allowed';

            }else{

                $data['status'] = 'Allowed';

                $hdf_employee = [];
                $hdf_employee['employee_id'] = $data['employee_id'];
                $hdf_employee['name'] = $data['name'];
                $hdf_employee['dept_bu_position'] = $data['dept_bu_position'];
                $hdf_employee['contact_number'] = $data['contact_number'];
                $hdf_employee['status'] = 'Allowed';
                $hdf_employee['date_time'] = date('Y-m-d');
                HdfEmployee::create($hdf_employee);

                HealthDeclarationForm::create($data);

                return 'saved';
             
            }
            
        }else{
            return 'error';
        }
    }

    public function saveDeclarationIC(Request $request){

        $clientIP = request()->ip();

        $this->validate($request, [
            'temperature' => 'required',
            'one_question' => 'required',
            'two_question' => 'required',
            'three_question' => 'required',
            'four_question' => 'required',
            'five_question' => 'required',
            'six_question' => 'required',
            'seven_question' => 'required',
        ]);

        $data = $request->all();

        if($data){
            $not_allowed = false;
        
            $yes_count = 0;
            if($data['one_question'] == 'Yes'){
                $yes_count++;
            }
            if($data['two_question'] == 'Yes'){
                $yes_count++;
            }

            if($data['three_question'] == 'Yes'){
                $yes_count++;
            }

            if($data['four_question'] == 'Yes'){
                $yes_count++;
            }

            if($data['five_question'] == 'Yes'){
                $yes_count++;
            }

            //Save Data
            $data['date_time'] = date('h:m:s A m/d/Y');
            $data['ip_address'] =  $clientIP;

            $location = '';
            if(stristr($clientIP, '10.97')){
                $location = "BGC";
            }
            else if(stristr($clientIP, '10.96.16') || stristr($clientIP, '10.96.17')){
                $location = "Manila";
            }
            else if(stristr($clientIP, '10.96.224') || stristr($clientIP, '10.96.225')){
                $location = "Bulacan";
            }
            else if(stristr($clientIP, '10.96.131') || stristr($clientIP, '10.96.132') || stristr($clientIP, '10.96.133') || stristr($clientIP, '10.96.134') || stristr($clientIP, '10.96.135')){
                $location = "Bataan";
            }
            else if(stristr($clientIP, '10.160.1') || stristr($clientIP, '10.160.2')){
                $location = "Davao";
            }
            else if(stristr($clientIP, '10.128.97') || stristr($clientIP, '10.128.98') || stristr($clientIP, '10.128.99')){
                $location = "Capiz";
            }
            else if(stristr($clientIP, '10.128.129') || stristr($clientIP, '10.128.130') || stristr($clientIP, '10.128.131')){
                $location = "Bacolod";
            }
            else if(stristr($clientIP, '10.96.33') || stristr($clientIP, '10.96.34') || stristr($clientIP, '10.96.35') || stristr($clientIP, '10.96.36')){
                $location = "Isabela";
            }
            else if(stristr($clientIP, '10.128.16') || stristr($clientIP, '10.128.17') || stristr($clientIP, '10.128.36') || stristr($clientIP, '10.128.37') || stristr($clientIP, '10.128.38') || stristr($clientIP, '10.128.39')){
                $location = "Iloilo";
            }
            else{
                $location = "";
            }
            
            if($yes_count >= 1){
                
                $data['status'] = 'Not Allowed';

                $hdf_employee = [];
                $hdf_employee['employee_id'] = $data['employee_id'];
                $hdf_employee['name'] = $data['name'];
                $hdf_employee['dept_bu_position'] = $data['dept_bu_position'];
                $hdf_employee['status'] = 'Not Allowed';
                $hdf_employee['date_time'] = date('Y-m-d');
                
                HdfIcEmployee::create($hdf_employee);
                
                HealthDeclarationIcForm::create($data);

                $message = $data['name'];
                if($location){
                    $message = $data['name'] . ' - Location: ' . $location;
                }else{
                    $message = $data['name'];
                }
                $send_message = $this->send_message($message,"not_allowed");

                

                return 'not_allowed';
            }else{
                $data['status'] = 'Allowed';

                $hdf_employee = [];
                $hdf_employee['employee_id'] = $data['employee_id'];
                $hdf_employee['name'] = $data['name'];
                $hdf_employee['dept_bu_position'] = $data['dept_bu_position'];
                $hdf_employee['status'] = 'Allowed';
                $hdf_employee['date_time'] = date('Y-m-d');
                HdfIcEmployee::create($hdf_employee);

                HealthDeclarationIcForm::create($data);

                return 'saved';
                
            }
            
        }else{
            return 'error';
        }
    }

    public function get_user($get_users, $user){
        $get_users_arr = [];

        $result = array_filter($get_users, function ($item) use ($user) {
            if (stripos($item['name'], $user) !== false) {
                $get_users_arr['name'] = $item['name'];
                $get_users_arr['user_id'] = $item['user_id'];

                return $get_users_arr;
            }else{
                return $get_users_arr = [];
            }
        });

        return $result;
    }

    //DOOR ACCESS

    public function enableDoorAccessOveride(Request $request){

        $data = $request->all();
        if($data['user_id']){
            $user_id = $data['user_id'];
            $employee_id = $data['employee_id'];
            $name = $data['name'];
            
            $card_access = $this->getCardAccess($user_id);
            if($card_access['card_list']){
                if($card_access['card_list'][0]['is_blocked'] == true){
                    $enable_card_access = $this->enableCardAccess($card_access['card_list'][0]['id'],$name);

                    $check_hdf_employee = HdfEmployee::where('employee_id',$employee_id)->whereDate('created_at',date('Y-m-d'))->first();
                    if($check_hdf_employee){
                        $hdf_employee = [];
                        $hdf_employee['status'] = 'Allowed : Overide';
                        $check_hdf_employee->update($hdf_employee);
                    }

                    return 'Overide';
                }else{
                    return 'Not';
                }
            }else{
                return 'Not';
            }
        }else{
            return 'Not';
        }
    }

    public function disableDoorAccessOveride(Request $request){

        $data = $request->all();
        if($data['user_id']){
            $user_id = $data['user_id'];
            $employee_id = $data['employee_id'];
            $name = $data['name'];
            
            $card_access = $this->getCardAccess($user_id);
            if($card_access['card_list']){
                if($card_access['card_list'][0]['is_blocked'] == false){
                    $disable_card_access = $this->disableCardAccess($card_access['card_list'][0]['id'], $name);

                    $check_hdf_employee = HdfEmployee::where('employee_id',$employee_id)->whereDate('created_at',date('Y-m-d'))->first();
                    if($check_hdf_employee){
                        $hdf_employee = [];
                        $hdf_employee['status'] = 'Not Allowed : Go Home';
                        $check_hdf_employee->update($hdf_employee);
                    }

                    return 'Overide';
                }else{
                    return 'Not';
                }
            }else{
                return 'Not';
            }
        }else{
            return 'Not';
        }
    }

    public function getUserAccess(){

        $client = new Client();

        $client = new Client([
            'base_uri' => 'http://10.96.4.60:8795/v2/',
            'cookies' => true,
        ]);

        $client->request('POST', 'login', [
            'json' => [
                'name' => 'covid19',
                'password' => '$upr3m@!',
                'user_id' => 'admin'
            ]
        ]);

        $response = $client->request('GET', 'users?limit=10000&offset=0');

        $users = json_decode($response->getBody(), true);
        
        $user_data = [];
        if($users){
            foreach($users['records'] as $key => $user){
                $user_data[$key]['name'] = $user['name'];
                $user_data[$key]['user_id'] = $user['user_id'];
            }
        }

        $check_rfid_user = RfidUser::orderBy('created_at','DESC')->first();

        if($check_rfid_user){
            $data = [];
            $data['door_users'] = json_encode($user_data);
            $check_rfid_user->update($data);
        }else{
            $data = [];
            $data['door_users'] = json_encode($user_data);
            RfidUser::create($data);
        }

        return $user_data;
    }

    public function getCardAccess($user_id){

        $client = new Client();

        $client = new Client([
            'base_uri' => 'http://10.96.4.60:8795/v2/',
            'cookies' => true,
        ]);

        $client->request('POST', 'login', [
            'json' => [
                'name' => 'covid19',
                'password' => '$upr3m@!',
                'user_id' => 'admin'
            ]
        ]);

        $response = $client->request('GET', 'users/' . $user_id . '/cards');

        $cards = json_decode($response->getBody(), true);

        return $cards;
    }

    public function disableCardAccess($card_id,$name){

        $client = new Client();

        $client = new Client([
            'base_uri' => 'http://10.96.4.60:8795/v2/',
            'cookies' => true,
        ]);

        $client->request('POST', 'login', [
            'json' => [
                'name' => 'covid19',
                'password' => '$upr3m@!',
                'user_id' => 'admin'
            ]
        ]);
        $cards = [];
        try{
            $response = $client->request('POST', 'cards/' .$card_id . '/block' );

            $send_message = $this->send_message($name,"door_not_allowed");

            return $cards = json_decode($response->getBody(), true);
        }catch(ServerException $e){
            return $cards = [];
        }
        catch(RequestException $e){
            return $cards = [];
        }

    
        return $cards;

    }

    public function enableCardAccess($card_id,$name){

        $client = new Client();

        $client = new Client([
            'base_uri' => 'http://10.96.4.60:8795/v2/',
            'cookies' => true,
        ]);

        $client->request('POST', 'login', [
            'json' => [
                'name' => 'covid19',
                'password' => '$upr3m@!',
                'user_id' => 'admin'
            ]
        ]);

        try{        
            $response = $client->request('POST', 'cards/' .$card_id . '/unblock' );

            $send_message = $this->send_message($name,"door_allowed");

            return $cards = json_decode($response->getBody(), true);
        }catch(ServerException $e){
            return $cards = [];
        }
        catch(RequestException $e){
            return $cards = [];
        }

        return $cards;

    }

    //SAVING OVERIDE ACCESS
    public function health_declaration_form_users_disable_set_up(){
        return view('health_declartion_form.health_declaration_form_users_disable_set_up');
    }

    public function saveDeclarationOveride(Request $request){

        $data = $request->all();

        //Door Access
        $overide = false;
        if($data['face_user_id']){
            $user_id = $data['user_id'];
            $card_access = $this->getCardAccess($user_id);

            if($card_access['card_list']){
                if($card_access['card_list'][0]['is_blocked'] == 'true'){
                    $disable_card_access = $this->enableCardAccess($card_access['card_list'][0]['id']);
                    $overide = true;
                }
            }
           
        }
        
        //Face Access
        if($data['face_user_id']){
            $face_user_id = $data['face_user_id'];
            $face_card_access = $this->getFaceCardAccess($face_user_id);

            if($face_card_access['card_list']){
                if($face_card_access['card_list'][0]['is_blocked'] == 'true'){
                    $disable_face_card_access = $this->enableFaceCardAccess($face_card_access['card_list'][0]['id']);
                    $overide = true;
                }
            }
           
        }

        if($overide == true){
            return 'Overide';
        }else{
            return 'Cannot Overide';
        }
        
    }

    public function fetchFormList(Request $request){
        $employee_id = $request->employee_id;

        $employees = HealthDeclarationForm::where('employee_id',$employee_id)->whereDate('created_at' ,'>=',date('Y-m-d'))->get();

        return $employees;
    }

    public function fetchFormListIC(Request $request){
        $employee_id = $request->employee_id;

        $employees = HealthDeclarationIcForm::where('employee_id',$employee_id)->whereDate('created_at' ,'>=',date('Y-m-d'))->get();

        return $employees;
    }

    //FACE ID ACCESS

    public function enableFaceAccessOveride(Request $request){

        $data = $request->all();

        

        if($data['face_user_id']){
            $face_user_id = $data['face_user_id'];
            $employee_id = $data['employee_id'];
            $name = $data['name'];

            $card_access = $this->getFaceCardAccess($face_user_id);

            if($card_access['card_list']){

                if($card_access['card_list'][0]['is_blocked'] == true){
                    $enable_card_access = $this->enableFaceCardAccess($card_access['card_list'][0]['id'],$name);
                    $check_hdf_employee = HdfEmployee::where('employee_id',$employee_id)->whereDate('created_at',date('Y-m-d'))->first();
                    if($check_hdf_employee){
                        $hdf_employee = [];
                        $hdf_employee['status'] = 'Allowed : Overide';
                        $check_hdf_employee->update($hdf_employee);
                    }

                    return 'Overide';
                }else{
                    return 'Not';
                }
            }else{
                return 'Not';
            }
        }else{
            return 'Not';
        }
    }

    public function disableFaceAccessOveride(Request $request){

        $data = $request->all();


        if($data['face_user_id']){
            $face_user_id = $data['face_user_id'];
            $employee_id = $data['employee_id'];
            $name = $data['name'];

            $card_access = $this->getFaceCardAccess($face_user_id);

            if($card_access['card_list']){              
                if($card_access['card_list'][0]['is_blocked'] == false){
                    $disable_card_access = $this->disableFaceCardAccess($card_access['card_list'][0]['id'],$name);

                    $check_hdf_employee = HdfEmployee::where('employee_id',$employee_id)->whereDate('created_at',date('Y-m-d'))->first();
                    if($check_hdf_employee){
                        $hdf_employee = [];
                        $hdf_employee['status'] = 'Not Allowed : Go Home';
                        $check_hdf_employee->update($hdf_employee);
                    }

                    return 'Overide';
                }else{
                    return 'Not';
                }
            }else{
                return 'Not';
            }
        }else{
            return 'Not';
        }
    }

    public function fetchFaceUserAccess(){
        $client_face = new Client();

        $client_face = new Client([
            'base_uri' => 'http://10.96.4.61:8795/v2/',
            'cookies' => true,
        ]);

        $client_face->request('POST', 'login', [
            'json' => [
                'name' => 'fcovid19',
                'password' => '$upr3m@!',
                'user_id' => 'admin'
            ]
        ]);

        $response = $client_face->request('GET', 'users?limit=10000&offset=0');

        $users = json_decode($response->getBody(), true);
        // return $users;
        $user_data = [];
        if($users){
            foreach($users['records'] as $key => $user){
                $user_data[$key]['name'] = $user['name'];
                $user_data[$key]['user_id'] = $user['user_id'];
            }
        }

        $check_rfid_user = RfidUser::orderBy('created_at','DESC')->first();

        if($check_rfid_user){
            $data = [];
            $data['face_users'] = json_encode($user_data);
            $check_rfid_user->update($data);
        }else{
            $data = [];
            $data['face_users'] = json_encode($user_data);
            RfidUser::create($data);
        }
        return $user_data;
    }

    public function getFaceCardAccess($user_id){

        $client = new Client();

        $client = new Client([
            'base_uri' => 'http://10.96.4.61:8795/v2/',
            'cookies' => true,
        ]);

        $client->request('POST', 'login', [
            'json' => [
                'name' => 'fcovid19',
                'password' => '$upr3m@!',
                'user_id' => 'admin'
            ],
            ['timeout' => 60],
            ['delay' => 10000]
        ]);

        $response = $client->request('GET', 'users/' . $user_id . '/cards');

        $cards = json_decode($response->getBody(), true);

        return $cards;
    }

    public function disableFaceCardAccess($card_id,$name){

        $client = new Client();

        $client = new Client([
            'base_uri' => 'http://10.96.4.61:8795/v2/',
            'cookies' => true,
        ]);

        $client->request('POST', 'login', [
            'json' => [
                'name' => 'fcovid19',
                'password' => '$upr3m@!',
                'user_id' => 'admin'
            ],
            ['timeout' => 60],
            ['delay' => 10000]
        ]);
        $cards = [];
        try{
            $response = $client->request('POST', 'cards/' .$card_id . '/block' );

            $send_message = $this->send_message($name,"face_not_allowed");

            return $cards = json_decode($response->getBody(), true);
        }catch(ServerException $e){
            return $cards = [];
        }
        catch(RequestException $e){
            return $cards = [];
        }

    
        return $cards;

    }

    public function enableFaceCardAccess($card_id,$name){

        $client = new Client();

        $client = new Client([
            'base_uri' => 'http://10.96.4.61:8795/v2/',
            'cookies' => true,
        ]);

        $client->request('POST', 'login', [
            'json' => [
                'name' => 'fcovid19',
                'password' => '$upr3m@!',
                'user_id' => 'admin'
            ],
            ['timeout' => 60],
            ['delay' => 10000]
        ]);

        $cards = [];
        try{
            $response = $client->request('POST', 'cards/' .$card_id . '/unblock' );

            $send_message = $this->send_message($name,"face_allowed");

            return $cards = json_decode($response->getBody(), true);

        }catch(ServerException $e){
            return $cards = [];
        }
        catch(RequestException $e){
            return $cards = [];
        }    
        return $cards;

    }

    public function send_message($name,$message){

        $httpClient = new Client(); 

        if($message == 'not_allowed'){
            $message_data = "Attention: " . $name . ' is not allowed to pass. - ('.date('m/d/Y').')';    
        }else if($message == 'door_allowed'){
            $message_data = "Attention: " . $name . ' door access successfully enabled. - ('.date('m/d/Y').')';    
        }else if($message == 'door_not_allowed'){
            $message_data = "Attention: " . $name . ' door access successfully disabled. - ('.date('m/d/Y').')';    
        }else if($message == 'face_allowed'){
            $message_data = "Attention: " . $name . ' biometric access successfully enabled. - ('.date('m/d/Y').')';    
        }else if($message == 'face_not_allowed'){
            $message_data = "Attention: " . $name . ' biometric access successfully disabled. - ('.date('m/d/Y').')';    
        }else{
            $message_data = "";
        }     
        
        if($message_data && $name){
            $body = [
                //prod
                'roomId' => "Y2lzY29zcGFyazovL3VzL1JPT00vMzIzMDY1OTAtOWE1ZC0xMWVhLThiNjktYzFjN2Q4MDQxODBm",
                //test server
                // 'roomId' => 'Y2lzY29zcGFyazovL3VzL1JPT00vOTZmZGE1ZTAtZTFlNC0xMWVhLWE1MTAtYWZjYmY3NjM2ZDI4',
                'text' => $message_data
            ];

            try{
                $response = $httpClient->post(
                    'https://api.ciscospark.com/v1/messages',
                    [
                        RequestOptions::BODY => json_encode($body),
                        RequestOptions::HEADERS => [
                            'Content-Type' => 'application/json',
                            //prod
                            'Authorization' => 'Bearer NWNiZjI4YTgtOGViZS00NGMxLWFhMTUtOGU3YTdjOWZjMWQ4ODc0Mjg0YmUtNjUy_PF84_72c16376-f5a4-4a5c-ad51-a60a7b78a790',
                            //test server
                            // 'Authorization' => 'Bearer YjViNGU3YTgtNTk1Ni00OGI3LWE4M2YtYmQxMTJhM2Q5YmEwOWNhMGExNzQtYjA1_PF84_72c16376-f5a4-4a5c-ad51-a60a7b78a790',
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
        }
    }



    public function employeeUpdateStatus(Request $request){
        $data = $request->all();
        
        $check_hdf_employee = HdfEmployee::where('employee_id',$data['employee_id'])->whereDate('created_at',date('Y-m-d'))->first();

        $attachment_file = '';
        if(isset($request->attachment_file)){
            if($request->file('attachment_file')){
                $attachment = $request->file('attachment_file');   
                $filename = $check_hdf_employee['id'] . '_' . $attachment->getClientOriginalName();
                $path = Storage::disk('public')->putFileAs('hdf_attachments', $attachment , $filename);
                $attachment_file =  $filename;
            }
        }

        if($check_hdf_employee){
            if($data){

                $hdf_employee = [];

                $hdf_employee['remarks'] =  $data['remarks'];
                $hdf_employee['status'] =$data['status'];
                $hdf_employee['attachment_file'] = $attachment_file;
                

                if($hdf_employee){
                    $check_hdf = HealthDeclarationForm::where('employee_id',$data['employee_id'])->whereDate('created_at',date('Y-m-d'))->first();
                    if($check_hdf){
                        $hdf = [];
                        $hdf['remarks'] =  $data['remarks'];
                        $hdf['status'] =$data['status'];
                        $check_hdf->update($hdf);
                    }
                    $check_hdf_employee->update($hdf_employee);
                }
               
                return $check_hdf_employee = HdfEmployee::where('employee_id',$data['employee_id'])->whereDate('created_at',date('Y-m-d'))->first();
            }else{
                return $check_hdf_employee = HdfEmployee::where('employee_id',$data['employee_id'])->whereDate('created_at',date('Y-m-d'))->first();
            }
        }else{
            return 'warning';
        }

    }

    public function employeeICUpdateStatus(Request $request){
        $data = $request->all();
    
        $check_hdf_employee = HdfIcEmployee::where('employee_id',$data['employee_id'])->whereDate('created_at',date('Y-m-d'))->first();
            if($check_hdf_employee){
                if($data){
                    $hdf_employee = [];
                    $hdf_employee['remarks'] =  $data['remarks'];
                    $hdf_employee['status'] =$data['status'];

                   $check_hdf_employee->update($hdf_employee);

                   return 'saved';
                }else{
                    return 'not saved';
                }
            }else{
                return 'warning';
            }

    }


    public function fetchApplyFilterHDFEmployee(Request $request){
        $request->validate([
            'from' => 'required',
            'to' => 'required|after_or_equal:from'
        ]);

        $from = $request->from ? $request->from : ""; 
        $to = $request->to ? $request->to : "";
        $location = $request->to ? $request->location : "";
        
        $ip_address = [];
        if($location == "BGC Taguig"){
            $ip_address = [
                '10.97'
            ];
        }else if($location == "Manila"){
            $ip_address = [
                '10.96.16',
                '10.96.17'
            ];
        }else if($location == "Bulacan"){
            $ip_address = [
                '10.96.224',
                '10.96.225'
            ];
        }else if($location == "Bataan"){
            $ip_address = [
                '10.96.132',
                '10.96.133',
                '10.96.134',
                '10.96.135'
            ];
        }else if($location == "Iloilo"){
            $ip_address = [
                '10.128.16',
                '10.128.17',
                '10.128.36',
                '10.128.37',
                '10.128.38',
                '10.128.39',
            ];
        }else if($location == "Davao"){
            $ip_address = [
                '10.160.1',
                '10.160.2'
            ];
        }else if($location == "Capiz"){
            $ip_address = [
                '10.128.97',
                '10.128.98',
                '10.128.99'
            ];
        }else if($location == "Bacolod"){
            $ip_address = [
                '10.128.129',
                '10.128.130',
                '10.128.131'
            ];
        }else if($location == "Isabela"){
            $ip_address = [
                '10.96.33',
                '10.96.34',
                '10.96.35',
                '10.96.36'
            ];
        }

        return $summary = HealthDeclarationForm::where('created_at', '>=',  $from)
                                                ->whereDate('created_at' ,'<=', $to)
                                                ->where(function ($query) use($ip_address) {
                                                    if($ip_address){
                                                        for ($i = 0; $i < count($ip_address); $i++){
                                                            $query->orwhere('ip_address', 'like',  '%' . $ip_address[$i] .'%');
                                                         } 
                                                    }   
                                               })
                                                ->orderBy('created_at','DESC')
                                                ->get();
    }

    public function fetchApplyFilterHDFICEmployee(Request $request){
        $request->validate([
            'from' => 'required',
            'to' => 'required|after_or_equal:from'
        ]);

        $from = $request->from ? $request->from : ""; 
        $to = $request->to ? $request->to : "";
        $location = $request->to ? $request->location : "";
        
        $ip_address = [];
        if($location == "BGC Taguig"){
            $ip_address = [
                '10.97'
            ];
        }else if($location == "Manila"){
            $ip_address = [
                '10.96.16',
                '10.96.17'
            ];
        }else if($location == "Bulacan"){
            $ip_address = [
                '10.96.224',
                '10.96.225'
            ];
        }else if($location == "Bataan"){
            $ip_address = [
                '10.96.132',
                '10.96.133',
                '10.96.134',
                '10.96.135'
            ];
        }else if($location == "Iloilo"){
            $ip_address = [
                '10.128.16',
                '10.128.17',
                '10.128.36',
                '10.128.37',
                '10.128.38',
                '10.128.39',
            ];
        }else if($location == "Davao"){
            $ip_address = [
                '10.160.1',
                '10.160.2'
            ];
        }else if($location == "Capiz"){
            $ip_address = [
                '10.128.97',
                '10.128.98',
                '10.128.99'
            ];
        }else if($location == "Bacolod"){
            $ip_address = [
                '10.128.129',
                '10.128.130',
                '10.128.131'
            ];
        }else if($location == "Isabela"){
            $ip_address = [
                '10.96.33',
                '10.96.34',
                '10.96.35',
                '10.96.36'
            ];
        }

        return $summary = HealthDeclarationIcForm::where('created_at', '>=',  $from)
                                                            ->whereDate('created_at' ,'<=', $to)
                                                            ->where(function ($query) use($ip_address) {
                                                                if($ip_address){
                                                                    for ($i = 0; $i < count($ip_address); $i++){
                                                                        $query->orwhere('ip_address', 'like',  '%' . $ip_address[$i] .'%');
                                                                     } 
                                                                }   
                                                            })
                                                            ->orderBy('created_at','DESC')
                                                            ->get();
    }

    public function autolockScreen(){
        return session('lockscreen');
    }

    public function unlockScreen(Request $request){
        $request->validate([
            'lock_password' => 'required'
        ]);
        $lock_password = $request->lock_password;

        if($lock_password == 'lfuggoc1'){
            session([
                'lockscreen'=>[]
            ]);
            return 'unlock';
        }else{
            return 'not';
        }
    }

}
