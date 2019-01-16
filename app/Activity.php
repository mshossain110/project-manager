<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'actor_id',
        'action',
        'action_type',
        'resource_id',
        'resource_type',
        'meta',
        'project_id'
    ];

    public function actor() {
        return $this->belongsTo( 'App\User', 'actor_id' );
    }

    public function setMetaAttribute( $value ) {
        if ( ! empty($value) ) {
            $this->attributes['meta'] = serialize( $value );
        }
    }

    public function getMetaAttribute( $value ) {
        return unserialize( $value );
    }

    public function project() {
        return $this->belongsTo( 'App\Project', 'project_id' );
    }

    public function meta() {
        return $this->hasMany( 'App\Meta', 'entity_type', 'resource_type' );
    }
}
