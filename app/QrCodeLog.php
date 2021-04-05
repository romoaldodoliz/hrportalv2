<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QrCodeLog extends Model
{
    protected $connection = "mysql";
    
    protected $auditIncluded = [];
    protected $auditTimestamps = true;

    protected $table = 'qr_code_logs';

    protected $fillable = [
    	'ids'
    ];
}
