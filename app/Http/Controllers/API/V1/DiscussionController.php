<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Transformers\CategoryTransformer;
use App\Traits\Last_activity;
use App\Transformers\DiscussionBoardTransformer;
use App\Discussion_Board;
use App\Boardable;
use App\Milestone;
use App\File;

use App\Task;

class DiscussionController extends ApiController {

    public function index( Request $request ) {
        $project_id = $request->get( 'project_id' );
        
        $per_page =  15;

        $discussion_boards = Discussion_Board::where( 'project_id', $project_id );

        $discussion_boards = $discussion_boards->orderBy( 'created_at', 'DESC' )
                                ->paginate( $per_page );

        return $this->respondWithPaginator($discussion_boards, new DiscussionBoardTransformer);
    }

    public function show( Request $request ) {
        $project_id = $request->get( 'project_id' );
        $discussion_board_id = $request->get( 'discussion_board_id' );

        $discussion_board  = Discussion_Board::with('metas')->where( 'id', $discussion_board_id );
        $discussion_board = $discussion_board->first();


        return $this->respondWithItem($discussion_board, new DiscussionBoardTransformer);
    }

    public function store( Request $request ) {

        $data = $request->all( );
        // $media_data = $request->get_file_params();
        // $milestone_id = $request->get( 'milestone' );
        // $files = array_key_exists( 'files', $media_data ) ? $media_data['files'] : null;
        
        // $milestone = Milestone::find( $milestone_id );
        $discussion_board = Discussion_Board::create( $data );

        // if ( $milestone ) {
        //     $this->attach_milestone( $discussion_board, $milestone );
        // }

        // if ( $files ) {
        //     $this->attach_files( $discussion_board, $files );
        // }
        return $this->respondWithItem($discussion_board, new DiscussionBoardTransformer);
    }

    public function update( Request $request, $id ) {
        $data                = $request->all( );
        // $media_data          = $request->get_file_params();
        $project_id          = $request->get( 'project_id' );
        $discussion_board_id = $request->get( 'discussion_board_id' );
        $milestone_id        = $request->get( 'milestone' );
        // $files               = array_key_exists( 'files', $media_data ) ? $media_data['files'] : null;
        // $files_to_delete     = $request->get( 'files_to_delete' );

        $milestone = Milestone::find( $milestone_id );
        $discussion_board = Discussion_Board::with('metas')->where( 'id', $discussion_board_id )
            ->where( 'project_id', $project_id )
            ->first();

        $discussion_board->update_model( $data );

        if ( $milestone ) {
            $this->attach_milestone( $discussion_board, $milestone );
        }

        // if ( $files ) {
        //     $this->attach_files( $discussion_board, $files );
        // }

        // if ( $files_to_delete ) {
        //     $this->detach_files( $discussion_board, $files_to_delete );
        // }
        return $this->respondWithItem($discussion_board, new DiscussionBoardTransformer);
    }

    public function destroy( Request $request, $id ) {
        $project_id = $request->get( 'project_id' );
        $discussion_board_id = $request->get( 'discussion_board_id' );

        $discussion_board = Discussion_Board::findOrFail($id);
        $comments = $discussion_board->comments;
        foreach ($comments as $comment) {
            $comment->replies()->delete();
            // $this->detach_files( $comment );
        }
        $discussion_board->comments()->delete();
        // $this->detach_files( $discussion_board );
        $discussion_board->users()->detach();

        $discussion_board->delete();

        return $this->respondWithMessage("Discuss Board delete Successfully");
    }

    private function attach_milestone( Discussion_Board $board, Milestone $milestone ) {
        $boardable = Boardable::where( 'boardable_id', $board->id )
            ->where( 'boardable_type', 'discussion_board' )
            ->where( 'board_type', 'milestone' )
            ->first();

        if ( !$boardable ) {
            $boardable = Boardable::firstOrCreate([
                'boardable_id'   => $board->id,
                'boardable_type' => 'discussion_board',
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
        $discussion_board_id = $request->get( 'discussion_board_id' );

        $discussion_board = Discussion_Board::where( 'id', $discussion_board_id )
            ->where( 'project_id', $project_id )
            ->first();

        $user_ids = $request->get( 'users' );

        if ( is_array( $user_ids ) ) {
            foreach ( $user_ids as $user_id ) {
                $data = [
                    'board_id' => $discussion_board->id,
                    'board_type' => 'discussion_board',
                    'boardable_id' => $user_id,
                    'boardable_type' => 'user'
                ];
                Boardable::firstOrCreate( $data );
            }
        }

        return $this->respondWithItem($discussion_board, new DiscussionBoardTransformer);
    }

    public function detach_users( Request $request ) {
        $project_id = $request->get( 'project_id' );
        $discussion_board_id = $request->get( 'discussion_board_id' );

        $discussion_board = Discussion_Board::where( 'id', $discussion_board_id )
            ->where( 'project_id', $project_id )
            ->first();

        $user_ids = $request->get( 'users' );

        if ( is_array( $user_ids ) ) {
            $discussion_board->users()->detach( $user_ids );
        }

        return $this->respondWithItem($discussion_board, new DiscussionBoardTransformer);
    }

    public function privacy( Request $request ) {
        $project_id = $request->get( 'project_id' );
        $discussion_board_id = $request->get( 'discussion_board_id' );
        $privacy = $request->get( 'is_private' );
        // pm_update_meta( $discussion_board_id, $project_id, 'discussion_board', 'privacy', $privacy );
        return $this->respondWithMessage("Successful");
    }
}



