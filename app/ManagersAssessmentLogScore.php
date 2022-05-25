<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ManagersAssessmentLogScore extends Model
{
    protected $connection = "mysql_performance";
    use SoftDeletes;
    public function settings_quarter_period()
    {
        return $this->belongsTo('App\SettingsQuarterPeriod','quarter_period_id','id')->select('id','name','year');
    }
}
