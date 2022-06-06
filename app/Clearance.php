<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clearance extends Model
{
    protected $connection = "mysql_offboarding";

    protected $guarded = [];

    public function signatories(){
        return $this->hasMany('App\ClearanceSignatory')->select('id','clearance_id','user_id','department_id','attachment','accountabilities','status','remarks','date_verified')->orderBy('department_id','ASC');
    }
}
