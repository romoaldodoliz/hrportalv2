<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Head extends Model implements AuditableContract
{
    use Auditable;
    protected $auditIncluded = [];
    protected $auditTimestamps = true;

    protected $fillable = [
    	'name',
    ];

}
