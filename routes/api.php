<?php

use Illuminate\Http\Request;
use App\User;
use App\Employee;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user-login',function(Request $request){
    $user = User::where('email', $request->email)->first();
    if ($user) {
        if (Hash::check($request->password, $user->password)) {
            return Employee::select('id','user_id','last_name','first_name')->whereHas('user',function($q) use($request){
                        $q->where('email',$request->email);
                    })
                    ->with(array(
                        'companies',
                        'departments',
                        'locations',
                        'user'=>function($query){
                            $query->select('id','email');
                        })
                    )
                    ->first();
        } else {
            $response = ["message" => "Password mismatch"];
            return response($response, 422);
        }
    } else {
        $response = ["message" =>'User does not exist'];
        return response($response, 422);
    }
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
