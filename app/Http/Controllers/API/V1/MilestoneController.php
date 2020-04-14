<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Transformers\CategoryTransformer;
use App\Traits\Last_activity;
use App\Transformers\MilestoneTransformer;
use App\Boardable;
use App\Milestone;
use App\File;

use App\Task;

class MilestoneController extends ApiController {

    public function index( Request $request ) {
        $project_id = $request->get( 'project_id' );
        
        $per_page =  15;

        $milestones = Milestone::where( 'project_id', $project_id );

        $milestones = $milestones->orderBy( 'created_at', 'DESC' )
                                ->paginate( $per_page );

        return $this->respondWithPaginator($milestones, new MilestoneTransformer);
    }

    public function show( Request $request ) {
        $project_id = $request->get( 'project_id' );
        $milestone_id = $request->get( 'milestone_id' );

        $milestone  = Milestone::where( 'id', $milestone_id );
        $milestone = $milestone->first();


        return $this->respondWithItem($milestone, new MilestoneTransformer);
    }

    public function store( Request $request ) {

        $data = $request->all( );
        // $media_data = $request->get_file_params();
        // $milestone_id = $request->get( 'milestone' );
        // $files = array_key_exists( 'files', $media_data ) ? $media_data['files'] : null;
        
        // $milestone = Milestone::find( $milestone_id );
        $milestone = Milestone::create( $data );

        // if ( $milestone ) {
        //     $this->attach_milestone( $milestone, $milestone );
        // }

        // if ( $files ) {
        //     $this->attach_files( $milestone, $files );
        // }
        return $this->respondWithItem($milestone, new MilestoneTransformer);
    }

    public function update( Request $request, $id ) {
        $data                = $request->all( );
        // $media_data          = $request->get_file_params();
        $project_id          = $request->get( 'project_id' );
        $milestone_id = $request->get( 'milestone_id' );
        $milestone_id        = $request->get( 'milestone' );
        // $files               = array_key_exists( 'files', $media_data ) ? $media_data['files'] : null;
        // $files_to_delete     = $request->get( 'files_to_delete' );

        $milestone = Milestone::find( $milestone_id );
        $milestone = Milestone::with('metas')->where( 'id', $milestone_id )
            ->where( 'project_id', $project_id )
            ->first();

        $milestone->fill( $data )->save();

        // if ( $milestone ) {
        //     $this->attach_milestone( $milestone, $milestone );
        // }

        // if ( $files ) {
        //     $this->attach_files( $milestone, $files );
        // }

        // if ( $files_to_delete ) {
        //     $this->detach_files( $milestone, $files_to_delete );
        // }
        return $this->respondWithItem($milestone, new MilestoneTransformer);
    }

    public function destroy( Request $request, $id ) {
        $project_id = $request->get( 'project_id' );
        $milestone_id = $request->get( 'milestone_id' );

        $milestone = Milestone::findOrFail($id);
       
        // $this->detach_files( $milestone );
        $milestone->users()->detach();

        $milestone->delete();

        return $this->respondWithMessage("Discuss Board delete Successfully");
    }

    private function attach_milestone( Milestone $board, Milestone $milestone ) {
        $boardable = Boardable::where( 'boardable_id', $board->id )
            ->where( 'boardable_type', 'milestone' )
            ->where( 'board_type', 'milestone' )
            ->first();

        if ( !$boardable ) {
            $boardable = Boardable::firstOrCreate([
                'boardable_id'   => $board->id,
                'boardable_type' => 'milestone',
                'board_id'       => $milestone->id,
                'board_type'     => 'milestone'
            ]);
        } else {
            $boardable->update([
                'board_id'   => $milestone->id
            ]);
        }
    }

    public function attach_users( Request $request ) {
        $project_id = $request->get( 'project_id' );
        $milestone_id = $request->get( 'milestone_id' );

        $milestone = Milestone::where( 'id', $milestone_id )
            ->where( 'project_id', $project_id )
            ->first();

        $user_ids = $request->get( 'users' );

        if ( is_array( $user_ids ) ) {
            foreach ( $user_ids as $user_id ) {
                $data = [
                    'board_id' => $milestone->id,
                    'board_type' => 'milestone',
                    'boardable_id' => $user_id,
                    'boardable_type' => 'user'
                ];
                Boardable::firstOrCreate( $data );
            }
        }

        return $this->respondWithItem($milestone, new MilestoneTransformer);
    }

    public function detach_users( Request $request ) {
        $project_id = $request->get( 'project_id' );
        $milestone_id = $request->get( 'milestone_id' );

        $milestone = Milestone::where( 'id', $milestone_id )
            ->where( 'project_id', $project_id )
            ->first();

        $user_ids = $request->get( 'users' );

        if ( is_array( $user_ids ) ) {
            $milestone->users()->detach( $user_ids );
        }

        return $this->respondWithItem($milestone, new MilestoneTransformer);
    }

    public function privacy( Request $request ) {
        $project_id = $request->get( 'project_id' );
        $milestone_id = $request->get( 'milestone_id' );
        $privacy = $request->get( 'is_private' );
        // pm_update_meta( $milestone_id, $project_id, 'milestone', 'privacy', $privacy );
        return $this->respondWithMessage("Successful");
    }
}



