<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;


class Task_List extends Model {
    
    use Common;
    
    protected $table = 'boards';

    protected $fillable = [
        'title',
        'description',
        'order',
        'status',
        'private',
        'project_id',
        'created_by',
        'updated_by',
    ];

    protected $attributes = ['type' => 'task_list'];



    public function newQuery( $except_deleted = true ) {
        return parent::newQuery( $except_deleted )->where( 'type', '=', 'task_list' );
    }

    public function board() {
        return $this->hasMany( 'App\Boardable', 'boardable_id' )->where( 'boardable_type', 'task_list' );
    }
    
    public function boardables() {
        return $this->hasMany( 'App\Boardable', 'board_id' )->where( 'board_type', 'task_list' );
    }

    public function tasks() {
        return $this->belongsToMany( 'App\Task', 'boardables', 'board_id', 'boardable_id' )
            ->where( 'boardable_type', 'task' )
            ->where( 'board_type', 'task_list' )
            ->withPivot( 'order' );
    }

    public function comments() {
        return $this->hasMany( 'App\Comment', 'commentable_id' )->where( 'commentable_type', 'task_list' );
    }

    public function assignees() {
        return $this->belongsToMany( 'App\User', 'boardables', 'board_id', 'boardable_id')
            ->where( 'board_type', 'task_list' )
            ->where( 'boardable_type', 'user' );
    }

    public function files() {
        return $this->hasMany( 'App\File', 'fileable_id' )->where( 'fileable_type', 'task_list' );
    }

    public function milestones() {
        return $this->belongsToMany( 'App\Milestone', 'boardables', 'boardable_id', 'board_id' )
            ->where( 'board_type', 'milestone' )
            ->where( 'boardable_type', 'task_list' );
    }

    public function metas() {
        return $this->hasMany( 'App\Meta', 'entity_id' )
            ->where( 'entity_type', 'task_list' );
    }

    public static function latest_order($project_id) {
        return self::where( 'type', 'task_list' )->where('project_id', $project_id)->where('order', '!=', '999999')
            ->max('order');
        
    }

}
