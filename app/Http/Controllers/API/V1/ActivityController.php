<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Activity;
use App\Transformers\ActivityTransformer;

class ActivityController  extends ApiController {

    use Transformer_Manager;

    public function index( Request $request ) {
        
        $project_id = $request->get( 'project_id' );

        $per_page   =  20;

        if ( empty( $project_id ) ) {
            $activities = Activity::orderBy( 'created_at', 'DESC' )
            ->paginate( $per_page );
        } else {
            $activities = Activity::where('activities.project_id', $project_id )
            ->orderBy( 'activities.created_at', 'desc' )
            ->paginate( $per_page );
            
        }
        return $this->respondWithPaginator( $activities,  new ActivityTransformer);
    }
}

            


            
