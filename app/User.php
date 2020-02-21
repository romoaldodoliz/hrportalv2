<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
// use OwenIt\Auditing\Contracts\UserResolver;
use Illuminate\Support\Facades\Auth;


class User extends Model implements Auditable, Authenticatable
{
    use Notifiable;
    use AuthenticableTrait;
    use EntrustUserTrait;

    use \OwenIt\Auditing\Auditable;

    // public static function resolveId()
    // {
    //     return Auth::check() ? Auth::user()->id : null;
    // }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'view_confidential'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function employees()
    {
        return $this->hasOne('App\Employee');
    }

}
