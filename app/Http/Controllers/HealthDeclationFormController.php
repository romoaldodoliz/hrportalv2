<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Employee;
use App\HealthDeclarationForm;

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
            $get_users = $this->getUserAccess();
            
            foreach($employees as $key => $employee){
                $name = '';
                $user_id = '';
                $employee_name = $employee['first_name'] . ' ' . $employee['last_name'];
                $user =  $this->get_user($get_users, $employee_name);
                foreach($user as $user_data){
                    $name = $user_data['name'];
                    $user_id = $user_data['user_id'];
                }
                $employees[$key]['name'] = $name;
                $employees[$key]['user_id'] = $user_id;

                $card_access = $this->getCardAccess($user_id);

                if($card_access['card_list']){
                    $employees[$key]['card_access_blocked'] =  $card_access['card_list'][0]['is_blocked'];
                }else{
                    $employees[$key]['card_access_blocked'] = '';
                }
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

            if($data['seven_question'] == 'Yes'){
                $yes_count++;
            }

            if($data['eight_question'] == 'Yes'){
                $yes_count++;
            }

            //Save Data
            $data['date_time'] = date('h:m:s A m/d/Y');
            
            
            if($yes_count > 1){

                
                $user_id = $data['user_id'];
                $card_access = $this->getCardAccess($user_id);

                if($card_access['card_list']){
                    $disable_card_access = $this->disableCardAccess($card_access['card_list'][0]['id']);
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

        $response = $client->request('GET', 'users?limit=10000&offset=10');

        $users = json_decode($response->getBody(), true);
        
        $user_data = [];
        if($users){
            foreach($users['records'] as $key => $user){
                $user_data[$key]['name'] = $user['name'];
                $user_data[$key]['user_id'] = $user['user_id'];
            }
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


        $response = $client->request('POST', 'cards/' .$card_id . '/unblock' );

        $cards = json_decode($response->getBody(), true);

        return $cards;

    }

    public function health_declaration_form_users_disable_set_up(){
        return view('health_declartion_form.health_declaration_form_users_disable_set_up');
    }

    public function saveDeclarationOveride(Request $request){
        $employee_id = $request->employee_id;

        $employee = Employee::where('id',$employee_id)->first();


        $get_users = $this->getUserAccess();
        
        $name = $employee['first_name'] . ' ' . $employee['last_name'];
        $user =  $this->get_user($get_users, $name);
        if($user){
            $user_id = "";
            foreach($user as $user_data){
                $user_id = $user_data['user_id'];
            }
        }

        $card_access = $this->getCardAccess($user_id);

        if($card_access['card_list']){
            $enable_card_access = $this->enableCardAccess($card_access['card_list'][0]['id']);
        }
        
        return $employee;
    }


    public function fetchFormList(Request $request){
        $employee_id = $request->employee_id;

        $employees = HealthDeclarationForm::where('employee_id',$employee_id)->whereDate('created_at' ,'>=',date('Y-m-d'))->get();

        return $employees;
    }

    

}
