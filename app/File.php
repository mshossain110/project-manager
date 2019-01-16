<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'fileable_id',
        'fileable_type',
        'parent',
        'type',
        'attachment_id',
        'parent',
        'project_id',
        'created_by',
        'updated_by'
    ];

    public function comment() {
        return $this->hasOne( 'App\Comment', 'id', 'fileable_id');
        return $this->belongsToMany( 'App\Board', 'comments', 'id', 'commentable_id', 'fileable_id');
    }

    public function meta() {
        return $this->hasMany( 'App\Meta', 'entity_id' )->where('entity_type', 'file');
    }
}
