<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Carbon\Carbon;

class OverviewGraphTransformer extends TransformerAbstract {
    public function transform( $item ) {
        return [
            'date_time'  => $item['date_time'],
            'tasks'      => $item['tasks'],
            'activities' => $item['activities'],
        ];
    }
}
