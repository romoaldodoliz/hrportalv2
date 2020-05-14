<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeAccountabilities extends Model
{
    protected $connection = "mysql";
    
    public function inventories()
    {
        return $this->hasOne('App\Inventories','id','inventory_id');
    }
}
