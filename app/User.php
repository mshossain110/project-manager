<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles() {
        return $this->belongsToMany( 'App\Role', 'project_role_user', 'user_id', 'role_id' )
            ->withPivot('project_id', 'role_id');
    }

    public function projects() {
        return $this->belongsToMany( 'App\Project', 'project_role_user', 'user_id', 'project_id' );
    }
}
