<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RfidUser extends Model
{
    protected $fillable = [
        'door_users',
        'face_users'
    ];
}
