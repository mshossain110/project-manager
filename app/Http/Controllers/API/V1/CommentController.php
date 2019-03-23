<?php
namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Transformers\CategoryTransformer;
use App\Traits\Last_activity;
use App\Transformers\CommentTransformer;
use App\Comment;


class CommentController  extends ApiController {


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
        $data       = $request->all();
        $type       = $request->get('type');

        $commentable_type = $request->get( 'commentable_type' );
        $commentable_id = $request->get('commentable_id');   
        $comment = Comment::create( $data );

        if ( $type ) {
            $comment->type = $type;
        }

        return $this->respondWithItem($comment, new CommentTransformer);
        
    }

    public function update( Request $request, $id ) {
        // Grab non-empty inputs
        $data = $request->all( );

        $comment = Comment::find( $id );

        $comment->update( $data );
        return $this->respondWithItem($comment, new CommentTransformer);
    }

    public function destroy( $id ) {

        $comment    = Comment::find( $id );
        
        $resource_type = $comment->commentable_type;
        $resource_id = $comment->commentable_id;
        $comment->replies()->delete();
        $comment->delete();
        return $this->respondWithMessage("Comment delete Successfully");
    }
}
