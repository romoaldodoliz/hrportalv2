<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClearanceSignatory extends Model
{
    protected $connection = "mysql_offboarding";
    protected $table = "clearance_signatories";
    protected $guarded = [];


    public function user(){
        return $this->belongsTo('App\User')->select('id','name');
    }
    public function department(){
        return $this->belongsTo('App\Department')->select('id','name');
    }
}
