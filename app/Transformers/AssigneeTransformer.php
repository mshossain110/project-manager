<?php

namespace App\Transformers;

use App\Assignee;
use League\Fractal\TransformerAbstract;

class AssigneeTransformer extends TransformerAbstract {

    use ResourceEditors;

    protected $defaultIncludes = [

    ];

    protected $availableIncludes = [
        'user', 'creator', 'updater'
    ];

    public function transform( Assignee $item ) {
        return [
            'id'           => (int) $item->id,
            'status'       => $item->status,
            'assigned_at'  => format_date( $item->assigned_at ),
            'started_at'   => format_date( $item->started_at ),
            'completed_at' => format_date( $item->completed_at ),
        ];
    }

    public function includeUser( Assignee $item ) {
        $user = $item->assigned_user;

        return $this->item( $user, new UserTransformer );
    }
}