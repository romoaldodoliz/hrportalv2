<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RfidNumber extends Model
{
    protected $connection = "sqlsrv_access";
    protected $table = "AccessLog";
}
