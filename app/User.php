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

    protected $connection = "mysql";

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
        'name', 
        'email', 
        'password', 
        'view_confidential',
        'create',
        'edit',
        'read',
        'search',
        'download_export',
        'personal_info',
        'work_info',
        'contact_info',
        'identification_info',
        'npa_request',
        'monthly_basic_salary',
        'bank_account_details' , 
        'employee_201_file',
        'personal_info_edit',
        'work_info_edit',
        'contact_info_edit',
        'identification_info_edit',
        'employee_201_file_edit',
        'employee_201_file_edit',
        'job_history',
        'compensation_history',
        'performance_history',
        'job_history_edit',
        'compensation_history_edit',
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
