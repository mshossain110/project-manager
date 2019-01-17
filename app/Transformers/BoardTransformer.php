<?php

namespace App\Transformers;

use App\Board;
use League\Fractal\TransformerAbstract;


class BoardTransformer extends TransformerAbstract {

    use ResourceEditors;

    protected $defaultIncludes = [
        'creator', 'updater'
    ];

    public function transform( Board $item ) {
        return [
            'id'          => (int) $item->id,
            'title'       => $item->title,
            'description' => $item->description,
            'board_type'  => $item->type,
            'created_at'  => format_date( $item->created_at ),
        ];
    }
}