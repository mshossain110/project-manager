<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'project_id',
        'created_by',
        'updated_by'
    ];

    public static $hideSettings = [
        'zapier_api'
    ];

    public function setValueAttribute( $value ) {
        $this->attributes['value'] = serialize( $value );
    }

    public function getValueAttribute( $value ) {
        return unserialize( $value );
    }
}
