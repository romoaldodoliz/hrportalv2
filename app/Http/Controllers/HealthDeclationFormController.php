<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Employee;
use App\HealthDeclarationForm;
use App\RfidUser;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;

class HealthDeclationFormController extends Controller
{
    public function index(){
        return view('health_declartion_form.index');
    }

    public function fetchEmployees(Request $request){
        $keyword = $request->keyword;
        $employees = Employee::select('id','first_name','last_name','position','mobile_number')->with('companies','departments','locations')
                            ->where('first_name', 'like' , '%' .  $keyword . '%')
                            ->orWhere('last_name', 'like' , '%' .  $keyword . '%')
                            ->where('status','=','Active')
                            ->orderBy('last_name','DESC')
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

    public function saveDeclaration(Request $request){

        $this->validate($request, [
            'temperature' => 'required',
            'one_question' => 'required',
            'two_question' => 'required',
            'three_question' => 'required',
            'four_question' => 'required',
            'five_question' => 'required',
            'seven_question' => 'required',
            'eight_question' => 'required',
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

            if($data['four_question'] == 'Yes'){
                $yes_count++;
            }

            if($data['five_question'] == 'Yes'){
                $yes_count++;
            }

            if($data['six_question'] == 'Yes'){
                $yes_count++;
            }

            if($data['eight_question'] == 'Yes'){
                $yes_count++;
            }

            //Save Data
            $data['date_time'] = date('h:m:s A m/d/Y');
            
            
            if($yes_count > 1){

                //Door Access
                if($data['user_id']){
                    $user_id = $data['user_id'];
                    $card_access = $this->getCardAccess($user_id);

                    if($card_access['card_list']){
                        if($card_access['card_list'][0]['is_blocked'] == false){
                            $disable_card_access = $this->disableCardAccess($card_access['card_list'][0]['id']);
                        }
                    }
                }

                //Face Access
                if($data['face_user_id']){
                    $face_user_id = $data['face_user_id'];
                    $face_card_access = $this->getFaceCardAccess($face_user_id);

                    if($face_card_access['card_list']){
                        if($face_card_access['card_list'][0]['is_blocked'] == false){
                            $disable_face_card_access = $this->disableFaceCardAccess($face_card_access['card_list'][0]['id']);
                        }
                    }
                }
                

                $data['status'] = 'Not Allowed';
                HealthDeclarationForm::create($data);
                return 'not_allowed';
            }else{
                $data['status'] = 'Allowed';
                HealthDeclarationForm::create($data);
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

    public function disableCardAccess($card_id){

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
            return $cards = json_decode($response->getBody(), true);
        }catch(ServerException $e){
            return $cards = [];
        }
        catch(RequestException $e){
            return $cards = [];
        }

    
        return $cards;

    }

    public function enableCardAccess($card_id){

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
                }
            }
            $overide = true;
        }
        
        //Face Access
        if($data['face_user_id']){
            $face_user_id = $data['face_user_id'];
            $face_card_access = $this->getFaceCardAccess($face_user_id);

            if($face_card_access['card_list']){
                if($face_card_access['card_list'][0]['is_blocked'] == 'true'){
                    $disable_face_card_access = $this->enableFaceCardAccess($face_card_access['card_list'][0]['id']);
                }
            }
            $overide = true;
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


    //FACE ID ACCESS
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
            ]
        ]);

        $response = $client->request('GET', 'users/' . $user_id . '/cards');

        $cards = json_decode($response->getBody(), true);

        return $cards;
    }

    public function disableFaceCardAccess($card_id){

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
            ]
        ]);
        $cards = [];
        try{
            $response = $client->request('POST', 'cards/' .$card_id . '/block' );
            return $cards = json_decode($response->getBody(), true);
        }catch(ServerException $e){
            return $cards = [];
        }
        catch(RequestException $e){
            return $cards = [];
        }

    
        return $cards;

    }

    public function enableFaceCardAccess($card_id){

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
            ]
        ]);


        $response = $client->request('POST', 'cards/' .$card_id . '/unblock' );

        $cards = json_decode($response->getBody(), true);

        return $cards;

    }


}
