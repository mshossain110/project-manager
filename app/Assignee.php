<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignee extends Model
{
    protected $fillable = [
        'task_id',
        'assigned_to',
        'status',
        'assigned_at',
        'started_at',
        'completed_at',
        'created_by',
        'updated_by',
        'project_id'
    ];

    protected $dates = [
        'assigned_at', 'started_at', 'completed_at'
    ];

    public function assigned_user() {
        return $this->belongsTo( 'App\User', 'assigned_to' );
    }
}
