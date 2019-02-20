<?php
namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Transformers\CategoryTransformer;
use App\Traits\Last_activity;
use App\Transformers\CommentTransformer;
use App\Comment;


class Comment_Controller  extends ApiController {


    public function index( Request $request ) {
        $project_id = $request->get( 'project_id' );


        $per_page = config('pm.comment_per_page');

        $on = $request->get( 'on' );
        $id = $request->get( 'id' );
        $by = $request->get( 'by' );

        if ( $on ) {
            $query = Comment::where( 'commentable_type', $on );
        }

        if ( $id ) {
            $query = $query->where( 'commentable_id', $id );
        }

        if ( $by ) {
            $query = $query->where( 'created_by', $by );
        }

        if ( $query ) {
            $comments = $query->where( 'project_id', $project_id )
                ->orderBy( 'created_at', 'ASC' )
                ->paginate( $per_page, ['*'], 'page', $page );
        } else {
            $comments = Comment::where( 'project_id', $project_id )
                ->orderBy( 'created_at', 'ASC' )
                ->paginate( $per_page, ['*'], 'page', $page );
        }

        return $this->respondWithPaginator($comments, new CommentTransformer);
    }

    public function show( Request $request ) {
        $comment_id = $request->get( 'comment_id' );
        $comment    = Comment::find( $comment_id );
        
        return $this->respondWithItem($comment, new CommentTransformer);
    }

    public function store( Request $request ) {
        $data       = $request->all(  );
        // $media_data = $request->get_file_params();
        $type       = $request->get('type');

        $commentable_type = $request->get( 'commentable_type' );
        $commentable_id = $request->get('commentable_id');
    
        // $files      = array_key_exists( 'files', $media_data ) ? $media_data['files'] : null;
        
        $comment = Comment::create( $data );

        if ( $type ) {
            $comment->type = $type;
        }

        // if ( $files ) {
        //     $this->attach_files( $comment, $files );
        // }

        

        // $message = [
            
        //     // 'activity' => $this->last_activity( $commentable_type, $commentable_id ),
        // ];

        return $this->respondWithItem($comment, new CommentTransformer);
        
    }

    public function update( Request $request ) {
        // Grab non-empty inputs
        $data = $request->all( );

        // Grab file data inputs
        // $media_data = $request->get_file_params();

        // An array of files
        // $files = array_key_exists( 'files', $media_data ) ? $media_data['files'] : null;

        // An array of file ids that needs to be deleted
        // $files_to_delete = $request->get( 'files_to_delete' );

        $comment = Comment::find( $data['comment_id'] );

        $comment->update( $data );

        // if ( $files ) {
        //     $this->attach_files( $comment, $files );
        // }

        // if ( $files_to_delete ) {
        //     $this->detach_files( $comment, $files_to_delete );
        // }

        // $message = [
            
        //     'activity' => $this->last_activity( $comment->commentable_type, $comment->commentable_id  ),
        // ];

        return $this->respondWithItem($comment, new CommentTransformer);
    }

    public function destroy( $id ) {

        $comment    = Comment::find( $$id );
        
        $resource_type = $comment->commentable_type;
        $resource_id = $comment->commentable_id;

        // $this->detach_files( $comment );
        $comment->replies()->delete();
        // $comment->files()->delete();
        $comment->delete();

        // $message = [
            
        //     'activity' => $this->last_activity( $resource_type, $resource_id ),
        // ];

        return $this->respondWithMessage("Comment delete Successfully");
    }
}
