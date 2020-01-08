<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Location extends Model implements AuditableContract
{
    use Auditable;
    protected $auditIncluded = [];
    protected $auditTimestamps = true;

    protected $fillable = [
    	'name',
    	'address_id',
    ];

    public function addresses()
    {
        return $this->belongsToMany('App\Address');
    
    }
}
