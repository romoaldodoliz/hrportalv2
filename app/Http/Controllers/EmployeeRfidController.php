<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;

use App\Employee;
use App\RfidCardLog;

class EmployeeRfidController extends Controller
{

    public function index(){
        $get_rfid_users = $this->getUsers();
        $get_unassigned_card = $this->getUnassigned();
        $get_door_unassigned_card = $this->getDoorUnassigned();
        session(['header_text' => 'EMPLOYEE RFID']);
        return view('employee_rfid.index');
    }

    //--------------------Face Biometric Access

    public function getUsers(){
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

        //Get User cards
        $get_users = $client->request('GET', 'users?limit=10000&offset=0');
        $get_users = json_decode($get_users->getBody(), true);
        session(['biometric_users' => $get_users]);

        return $get_users;
    }

    public function getUserCard(Request $request){

        $user_id = $request->user_id;
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

        //Get User cards
        $get_user_cards = $client->request('GET', 'users/' . $user_id . '/cards');
        return $get_user_cards = json_decode($get_user_cards->getBody(), true);
    }

    public function getUnassigned(){
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

        //Get User cards
        $get_unassigned_cards = $client->request('GET', 'cards/unassigned?limit=10000&offset=0');
        $get_unassigned_cards = json_decode($get_unassigned_cards->getBody(), true);
        session(['biometric_unassigned_cards' => $get_unassigned_cards]);
        return $get_unassigned_cards;
    }

    public function getBiometricDevices(){
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

        //Get Devices
        $get_devices = $client->request('GET', 'devices?limit=10000&offset=0');
        $get_devices = json_decode($get_devices->getBody(), true);
        // session(['get_devices' => $get_devices]);
        return $get_devices;
    }

    public function getBiometricDeviceUsers(Request $request){
        $device_id = $request->device_id;

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

        //Get Device Users
        $get_device_users = $client->request('GET', 'devices/' . $device_id . '/users');
        return $get_device_users = json_decode($get_device_users->getBody(), true);
    }

    private function validateCard($biometric_card_id){
        $unassigned_cards = session("biometric_unassigned_cards");

        $selected_card = [];
        if($unassigned_cards){
            foreach($unassigned_cards['records'] as $card){
                if($card['card_id'] == $biometric_card_id){
                    $selected_card['id'] = $card['id'];
                    $selected_card['card_id'] = $card['card_id'];
                }
            }
        }

        return $selected_card;

    }

    public function searchEmployee(Request $request){
        $data = $request->search;
        if($data){
            return Employee::select('id','user_id','first_name','last_name','id_number','door_id_number')
                            ->where(function($q) use($data) {
                                $q->orWhere('first_name', 'like', '%'.$data.'%');
                                $q->orWhere('last_name', 'like', '%'.$data.'%');
                                $q->orWhereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ["%{$data}%"]);
                            })
                            ->where('status','Active')
                            ->with('rfid_biometric_log')
                            ->take(5)->get();
        }
        return $data;
    }

    public function biometricUser(Request $request){
        $data = $request->search;
        $users = session("biometric_users");
        return $users['records'];
    }

    public function saveBiometricID(Request $request){

        $data = $request->all();

        $this->validate($request, [
            'biometric_user_id' => 'required',
            'selected_devices' => 'required',
        ]);

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


        try{
            $new_id_card = ltrim($data['id_card'], "0");  

            //Validate card if exist in unassign
            $validate_card = $this->validateCard($new_id_card);
            if($validate_card){
                //If exist get id
                $card_id = $validate_card['id'];
            }else{
                //If Not, Saved Card 
                $save_card = $client->request('POST', 'cards/wiegand_card', [
                    'json' => [
                        'wiegand_card_id_list' => [[
                            'card_id'=> "$new_id_card"
                        ]],
                        'wiegand_format_id' => 5,
                    ]
                ]);
                $save_card_response = json_decode($save_card->getBody(), true);
                $card_id = $save_card_response['id'];
            }

            $put_card = $client->request('PUT', 'users/' . $data['biometric_user_id'] . '/cards',[
                'json' => [
                    'card_list' => [[
                        'card_id'=>$new_id_card,
                        'id'=>$card_id
                    ]]
                ]
            ]);

            $put_cardresponse = json_decode($put_card->getBody(), true);

            if($put_cardresponse['status_code'] == "SUCCESSFUL"){

                 //Save Log
                 $rfid_log = [];
                 $rfid_log['employee_id'] = $data['employee_id'];
                 $rfid_log['biometric_user_id'] = $data['biometric_user_id'];
                 $rfid_log['card_id'] = $card_id;
                 $rfid_log['card_id_number'] = $data['id_card'];
                 RfidCardLog::create($rfid_log);
                
                //Biometrics Devices
                if($data['selected_devices']){
                    //Push to device
                    $selected_devices = explode(',',$data['selected_devices']);
                    foreach($selected_devices as $device_id){
                        $push_to_device = $client->request('POST', 'devices/'.$device_id.'/users', [
                            'json' => [
                                'ids'=> [
                                    $data['biometric_user_id']
                                ],
                                'overwrite'=>true
                            ]
                        ]);
            
                        $push_to_device_return = json_decode($push_to_device->getBody(), true);
                    }
                }

                //Biometric Door Access
                if($data['selected_door_devices']){
                    //Push to Door Access
                    $push_to_select_door_devices = $this->saveDoorAccess($data['biometric_user_id'],$new_id_card,$data['selected_door_devices']);
                }

                return 'saved';
            }else{
                return 'not_saved';
            }

        }
        catch (BadResponseException $e) {
            return 'response_error';
        }       
        catch (ServerException $e) {
            return 'response_error';
        }       
        catch (ClientException $e) {
            return 'response_error';
        }    
    }

    //--------------------Door Biometric Access

    public function getDoorBiometricDevices(){
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

        //Get Devices
        $get_devices = $client->request('GET', 'devices?limit=10000&offset=0');
        $get_devices = json_decode($get_devices->getBody(), true);
        // session(['get_devices' => $get_devices]);
        return $get_devices;
    }

    public function getDoorUnassigned(){
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

        //Get User cards
        $get_unassigned_cards = $client->request('GET', 'cards/unassigned?limit=10000&offset=0');
        $get_unassigned_cards = json_decode($get_unassigned_cards->getBody(), true);
        session(['biometric_door_unassigned_cards' => $get_unassigned_cards]);
        return $get_unassigned_cards;
    }

    public function getDoorUserCard(Request $request){

        $user_id = $request->user_id;
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

        //Get User cards
        $get_user_cards = $client->request('GET', 'users/' . $user_id . '/cards');
        return $get_user_cards = json_decode($get_user_cards->getBody(), true);
    }

    private function validateDoorCard($biometric_card_id){
        $unassigned_cards = session("biometric_door_unassigned_cards");

        $selected_card = [];
        if($unassigned_cards){
            foreach($unassigned_cards['records'] as $card){
                if($card['card_id'] == $biometric_card_id){
                    $selected_card['id'] = $card['id'];
                    $selected_card['card_id'] = $card['card_id'];
                }
            }
        }

        return $selected_card;

    }

    public function getBiometricDoorDeviceUsers(Request $request){
        $device_id = $request->device_id;

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

        //Get Device Users
        $get_device_users = $client->request('GET', 'devices/' . $device_id . '/users');
        return $get_device_users = json_decode($get_device_users->getBody(), true);
    }

    public function saveDoorAccess($user_id, $card_id_number,$door_devices){

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
            //Validate card if exist in unassign
            $validate_card = $this->validateDoorCard($card_id_number);

            if($validate_card){
                //If exist get id
                $card_id = $validate_card['id'];
            }else{
                //If Not, Saved Card 
                $save_card = $client->request('POST', 'cards/wiegand_card', [
                    'json' => [
                        'wiegand_card_id_list' => [[
                            'card_id'=> "$card_id_number"
                        ]],
                        'wiegand_format_id' => 5,
                    ]
                ]);
                $save_card_response = json_decode($save_card->getBody(), true);
                $card_id = $save_card_response['id'];
            }

            if($card_id){
                //Add Card to User in Door Access
                $put_card = $client->request('PUT', 'users/' . $user_id . '/cards',[
                    'json' => [
                        'card_list' => [[
                            'card_id'=>$card_id_number,
                            'id'=>$card_id
                        ]]
                    ]
                ]);

                $put_cardresponse = json_decode($put_card->getBody(), true);
                
                if($put_cardresponse['status_code'] == "SUCCESSFUL"){
                    
                    if($door_devices){
                        $selected_devices = explode(',',$door_devices);
                        foreach($selected_devices as $device_id){
                            $push_to_device = $client->request('POST', 'devices/'.$device_id.'/users', [
                                'json' => [
                                    'ids'=> [
                                        $user_id
                                    ],
                                    'overwrite'=>true
                                ]
                            ]);
                
                            $push_to_device_return = json_decode($push_to_device->getBody(), true);
                        }
                    }
                    return "door_access_saved";
                }

            }
        }
        catch (BadResponseException $e) {
            return 'response_error';
        }       
        catch (ServerException $e) {
            return 'response_error';
        }       
        catch (ClientException $e) {
            return 'response_error';
        }    
    }
    
}
