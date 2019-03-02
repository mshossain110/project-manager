<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectRoleUser extends Model
{
    protected $table = 'project_role_user';

    protected $fillable = [
        'project_id',
        'role_id',
        'user_id',
        'assigned_by'
    ];

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
