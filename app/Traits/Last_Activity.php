<?php 

namespace App\Traits;

use App\Activity;
use App\Transformers\ActivityTransformer;

trait Last_activity {

    function last_activity ( $resource, $resource_id ) {
        return Activity::where('resource_type', $resource)->where('resource_id', $resource_id)->orderBy('created_at', 'desc')->first();
        
    }
}