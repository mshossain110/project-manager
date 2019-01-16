<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    const INCOMPLETE = 0;
    const COMPLETE   = 1;
    const PENDING    = 2;

    protected $fillable = [
        'title',
        'description',
        'estimation',
        'start_at',
        'due_date',
        'complexity',
        'priority',
        'payable',
        'recurrent',
        'status',
        'project_id',
        'completed_by',
        'completed_at',
        'parent_id',
        'created_by',
        'updated_by'
    ];

    protected $dates = ['start_at', 'due_date', 'completed_at'];

    protected $attributes = [
        'priority' => 1,
    ];

    public function scopeCompleted($query) {
        return $query->where('status', Task::COMPLETE);
    }

    public function scopeIncomplete($query) {
        return $query->where('status', Task::INCOMPLETE);
    }

    public function scopeOverdue( $query ) {
        $today = Carbon::now();
        return $query->whereDate('due_date', '<', $today);
    }

    public function scopeParent( $query ){
        return $query->where('parent_id', 0);
    }

    public function task_lists() {
        return $this->belongsToMany( 'App\Task_List', 'boardables', 'boardable_id', 'board_id' )
            ->where( 'boardables.board_type', 'task_list')
            ->where('boardables.boardable_type', 'task');
    }

    public function boards() {
        return $this->belongsToMany( 'App\Board',  'boardables', 'boardable_id', 'board_id' )
            ->where( 'boardables.boardable_type', 'task');
    }

    public function boardables() {
        return $this->hasMany( 'App\Boardable', 'boardable_id' )->where( 'boardable_type', 'task' );
    }

    public function files() {
        return $this->hasMany( 'App\File', 'fileable_id' )->where( 'fileable_type', 'task' );
    }

    public function comments() {
        return $this->hasMany( 'App\Comment', 'commentable_id' )->whereIn( 'commentable_type', ['task'] );
    }

    public function assignees() {
        return $this->hasMany( 'App\Assignee', 'task_id' );
    }
    
    public function user() {
        return $this->belongsToMany( 'App\User', 'assignees', 'task_id', 'assigned_to' )
            ->withPivot('completed_at', 'assigned_at', 'started_at', 'status');
    }

    public function activities() {
        return $this->hasMany( 'WeDevs\PM\Activity\Models\Activity', 'resource_id' )->where( 'resource_type', 'task' )->orderBy( 'created_at', 'DESC' );
    }

    public function projects() {
        return $this->belongsTo( 'WeDevs\PM\Project\Models\Project', 'project_id');
    }

    public function task_model( $key = '' ) {
        return apply_filters( 'task_model', $this, $key );
    }

    public function metas() {
        return $this->hasMany( 'WeDevs\PM\Common\Models\Meta', 'entity_id' )
            ->where( 'entity_type', 'task' );
    }

    public function completer() {
        return $this->belongsTo( 'WeDevs\PM\User\Models\User', 'completed_by' );
    }

     // Allowed status for a task
     public static $status = [
        0 => 'incomplete',
        1 => 'complete',
        2 => 'pending',
    ];

    public static $priorities = [
        0 => 'low',
        1 => 'medium',
        2 => 'high',
    ];

    public static $recurrency = [
        0 => '0', // no repeat
        1 => '1', // weekly
        2 => '2', // Monthly
        3 => '3', // Annually
        9 => '9', // never
    ];

    public static $payability = [
        0 => 'no',
        1 => 'yes'
    ];

    public static $complexity = [
        0 => 'basic',
        1 => 'intermediate',
        2 => 'advance'
    ];

    public function getStatusAttribute( $value ) {
        $value = (int) $value;

        if ( array_key_exists( $value, self::$status ) ) {
            return self::$status[(int) $value];
        }

        return self::$status[0];
    }

    public function setStatusAttribute( $value ) {
        $value = strtolower( $value );
        $key   = array_search( $value, self::$status );

        if ( array_key_exists( $value, self::$status ) ) {
            $this->attributes['status'] = $value;
        } else {
            $this->attributes['status'] = $key;
        }
    }

    public function getPriorityAttribute( $value ) {
        $value = (int) $value;

        if ( array_key_exists( $value, self::$priorities ) ) {
            return self::$priorities[(int) $value];
        }

        return self::$priorities[0];
    }

    public function setPriorityAttribute( $value ) {
        $value = strtolower( $value );
        $key   = array_search( $value, self::$priorities );

        if ( array_key_exists( $value, self::$priorities ) ) {
            $this->attributes['priority'] = $value;
        } else {
            $this->attributes['priority'] = $key;
        }
    }

    public function setStartAtAttribute( $date ) {
        $this->attributes['start_at'] = make_carbon_date( $date );
    }

    public function setDueDateAttribute( $date ) {
        $this->attributes['due_date'] = make_carbon_date( $date );
    }

    public function getRecurrentAttribute( $value ) {
        $value = (int) $value;

        if ( array_key_exists( $value, self::$recurrency ) ) {
            return self::$recurrency[(int) $value];
        }

        return self::$recurrency[0];
    }

    public function setRecurrentAttribute( $value ) {
        $value = strtolower( $value );
        $key   = array_search( $value, self::$recurrency );

        if ( array_key_exists( $value, self::$recurrency ) ) {
            $this->attributes['recurrent'] = $value;
        } else {
            $this->attributes['recurrent'] = $key;
        }
    }

    public function getPayableAttribute( $value ) {
        $value = (int) $value;

        if ( array_key_exists( $value, self::$payability ) ) {
            return self::$payability[(int) $value];
        }

        return self::$payability[0];
    }

    public function setPayableAttribute( $value ) {
        $value = strtolower( $value );
        $key   = array_search( $value, self::$payability );

        if ( array_key_exists( $value, self::$payability ) ) {
            var_dump( $value );
            $this->attributes['payable'] = $value;
        } else {
            $this->attributes['payable'] = $key;
        }
    }

    public function getComplexityAttribute( $value ) {
        $value = (int) $value;

        if ( array_key_exists( $value, self::$complexity ) ) {
            return self::$complexity[(int) $value];
        }

        return self::$complexity[0];
    }

    public function setComplexityAttribute( $value ) {
        $value = strtolower( $value );
        $key   = array_search( $value, self::$complexity );

        if ( array_key_exists( $value, self::$complexity ) ) {
            $this->attributes['complexity'] = $value;
        } else {
            $this->attributes['complexity'] = $key;
        }
    }


    public function getPrivacyAtAttribute() {
        return $this->mates()->where('meta_key', 'privacy')->first()->meta_value === 1;
    }

    public function getTaskListAttribute() {
        $task_list = $this->task_lists()->first();
        if ($task_list) {
            return $task_list->id;
        }
    }
}
