<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use OwenIt\Auditing\Auditable;
use Carbon\Carbon;
use App\User;
use App\Audit;
use DB;

class ActivityController extends Controller
{
    public function index()
    {
        session(['header_text' => 'Activities']);
        return view('activities.index', compact('audits','users'));
    }

    public function allActivities(){
        return Audit::with('user')->whereDate('created_at', DB::raw('CURDATE()'))->orderBy('created_at','DESC')->get();
    }

    public function getUsername($user_id){
        return User::where('id',$user_id)->first();
    }

    public function getAdminUser()
    {
        $admin_users = User::whereHas('roles', function($q) {
            $q->where('name','Administrator');
        })->get();

        return $admin_users;
    }

    public function filterAllActivities(Request $request){
        
        $data = $request->all();

        $this->validate($request,[
            'startDate' => 'required',
            'endDate' => 'required'
        ]);

        $start_date = $data['startDate'];
        $end_date = $data['endDate'];

        $audits = Audit::with('user')
                    ->whereDate('created_at', '>=', Carbon::parse($start_date))
                    ->whereDate('created_at', '<=', Carbon::parse($end_date))
                    ->orderBy('created_at','ASC')
                    ->get();

        return $audits;
    }


}
