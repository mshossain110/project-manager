<?php

namespace App\Transformers;

use App\Settings;
use League\Fractal\TransformerAbstract;


class SettingsTransformer extends TransformerAbstract {

    use ResourceEditors;

    protected $availableIncludes = [
        'creator', 'updater'
    ];

    public function transform( Settings $item ) {
        return [
            'id'         => (int) $item->id,
            'key'        => $item->key,
            'value'      => $item->value,
            'created_at' => format_date( $item->created_at ),
        ];
    }
}