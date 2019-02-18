<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;


class CategoryTransformer extends TransformerAbstract {

    use ResourceEditors;

    protected $availableIncludes = [
        'creator', 'updater',
    ];

    public function transform( Category $item ) {
        return [
            'id'               => (int) $item->id,
            'title'            => $item->title,
            'description'      => $item->description,
            'categorible_type' => $item->categorible_type,
            'created_at'       => $item->created_at ,
        ];
    }

}