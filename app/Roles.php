<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'pm_roles';

    protected $fillable = [
        'title',
        'description',
        'slug',
        'status',
        'created_by',
        'updated_by',
    ];
}
