<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $fillable = [
        'title',
        'description',
        'order',
        'type',
        'status',
        'project_id',
        'created_by',
        'updated_by',
    ];

    public function meta() {
        return $this->hasMany( 'App\Meta', 'entity_id' )
            ->whereIn( 'entity_type', [ 'milestone', 'task_list', 'discussion_board' ] );
    }
}
