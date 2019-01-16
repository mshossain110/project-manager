<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    protected $fillable = [
        'entity_id',
        'entity_type',
        'meta_key',
        'meta_value',
        'project_id',
        'created_by',
        'updated_by',
    ];

    public function milestone() {
        return $this->belongsTo( 'App\Milestone', 'entity_id' );
    }

    public function getMetaValueAttribute( $value ) {
        return maybe_unserialize( $value );
    }

    public function setMetaValueAttribute( $value ) {
        if( !is_serialized( $value ) ) { 
            $value = maybe_serialize($value); 
        }

        $this->attributes['meta_value'] = $value;
    }
}
