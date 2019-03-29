<?php 

namespace App\Observers;

trait ObserverTrait {
    public function call_attribute_methods( $resource ) {
        $fillable_attributes = $resource->getFillable();
        $old = $resource->getOriginal();
        $new = $resource->getAttributes();

        foreach ( $fillable_attributes as $attribute ) {
            if ( !isset( $old[$attribute] ) ) {
                continue;
            }

            if ( !isset( $new[$attribute] ) ) {
                continue;
            }
            if ( $old[$attribute] != $new[$attribute]  && method_exists( $this, $attribute ) ) {
                $this->$attribute( $resource, $old[$attribute] );
            }
        }
    }
}