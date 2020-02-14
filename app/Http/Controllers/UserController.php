<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    User
};

use DB;

class UserController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['header_text' => 'Users']);

        return view('user.index');
    }

    /**
     * Fetch all users
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        return User::with('roles')->orderBy('id', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required'
        ]);

        if($user = User::create($request->all())){
            // Assigning of role
            $user->syncRoles($request->role);
            return User::with('roles')->where('id', $user->id)->first();   
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email'  =>  'required|email|unique:users,email,' .$user->id,
            'role' => 'required',
        ]);

        if($user->update($request->all())){
            // Assigning of role
            DB::table('role_user')->where('user_id',$user->id)->delete();
            $user->attachRole($request->role);
            return User::with('roles')->where('id', $user->id)->first(); 
        }else{
            return User::with('roles')->where('id', $user->id)->first(); 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->delete()){
            return $user;
        }
    }

     /*
     * Change password 
     * 
     * @return \Illuminate\Http\Response
     */

    public function change_password(){
        return view('change_password')->with('employee');
    }

    public function changePassword(Request $request){

      
        $validator = $request->validate([
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ]);
    
        $user = User::findOrFail($request->user_id);
       
        $user->password = bcrypt($request->input('new_password'));
        $user->save();

        return $user;
    }
}
