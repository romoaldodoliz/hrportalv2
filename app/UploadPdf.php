<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadPdf extends Model
{
    protected $connection = "mysql_offboarding";

    protected $guarded = [];

    public function employee(){
        return $this->belongsTo('App\Employee','user_id','user_id')->select('id','user_id','first_name','last_name','middle_initial','name_suffix','position');
    }
    
    public function cancelled_by(){
        return $this->belongsTo('App\Employee','cancel_by','user_id')->select('id','user_id','first_name','last_name','middle_initial','name_suffix','position');
    }

    public function letter(){
        return $this->hasOne('App\Letter');
    }

    public function clearance(){
        return $this->hasOne('App\Clearance')->select('id','upload_pdf_id');
    }
}
