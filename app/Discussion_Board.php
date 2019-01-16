<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    
    protected $table = 'boards';

    protected $fillable = [
        'title',
        'description',
        'order',
        'project_id',
        'created_by',
        'updated_by',
    ];

    protected $attributes = ['type' => 'discussion_board'];

    public function newQuery( $except_deleted = true ) {
        return parent::newQuery( $except_deleted )->where( 'type', '=', 'discussion_board' );
    }

    public function comments() {
        return $this->hasMany( 'App\Comment', 'commentable_id' )->where( 'commentable_type', 'discussion_board' );
    }

    public function files() {
        return $this->hasMany( 'App\File', 'fileable_id' )->where( 'fileable_type', 'discussion_board' );
    }

    public function users() {
        return $this->belongsToMany( 'App\User',  'boardables', 'board_id', 'boardable_id')
            ->where( 'board_type', 'discussion_board' )
            ->where( 'boardable_type', 'user' );
    }

    public function milestones() {
        return $this->belongsToMany( 'App\Milestone', 'boardables', 'boardable_id', 'board_id' )
            ->where( 'board_type', 'milestone' )
            ->where( 'boardable_type', 'discussion_board' );
    }

    public function boardables() {
        return $this->hasMany( 'App\Boardable', 'boardable_id' )->where( 'boardable_type', 'discussion_board' );
    }

    public function meta() {
        return $this->hasMany( 'App\Meta', 'entity_id' )
            ->where( 'entity_type', 'discussion_board' );
    }
}
