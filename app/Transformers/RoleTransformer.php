<?php
namespace App\Transformers;

use App\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract {

    public function transform( Role $item ) {
        return [
            'id'          => (int) $item->id,
            'title'       => $item->title,
            'description' => $item->description,
            'status'      => $item->status,
            'permission'  => $item->permission,
            'created_at'  => $item->created_at,
            'slug'        => $item->slug
        ];
    }
}