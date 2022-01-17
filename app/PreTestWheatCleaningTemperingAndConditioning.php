<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreTestWheatCleaningTemperingAndConditioning extends Model
{
    protected $connection = "mysql_survey";
    protected $guarded = [];

    public function employee()
    {
        return $this->hasOne('App\Employee','user_id','user_id')->select('id','user_id','id_number','cluster')->where('status','Active');
    }
}
