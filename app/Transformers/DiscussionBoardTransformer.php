<?php

namespace App\Transformers;

use App\Discussion_Board;
use League\Fractal\TransformerAbstract;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Illuminate\Pagination\Paginator;

class DiscussionBoardTransformer extends TransformerAbstract {

    use ResourceEditors;

    protected $defaultIncludes = [
        
    ];

    protected $availableIncludes = [
        'comments', 'creator', 'updater', 'users', 'milestone', 'files'
    ];

    public function transform( Discussion_Board $item ) {
        return [
            'id'          => (int) $item->id,
            'title'       => $item->title,
            'description' => $item->description,
            'order'       => $item->order,
            'created_at'  =>  $item->created_at ,
            // 'meta'        => $this->meta( $item ),
        ];
    
    }


    public function meta( Discussion_Board $item ) {
        return [
            'total_comments' => $item->comments->count(),
            'total_users'    => $item->users->count(),
            'total_files'    => $item->files->count(),
        ];
    }

    public function includeUsers( Discussion_Board $item ) {
        $users = $item->users;

        return $this->collection( $users, new UserTransformer );
    }

    public function includeComments( Discussion_Board $item ) {
        $page = isset( $_GET['comment_page'] ) ? intval( $_GET['comment_page'] ) : 1;

        // Paginator::currentPageResolver(function () use ($page) {
        //     return $page;
        // });

        $comments = $item->comments()
            ->orderBy( 'created_at', 'ASC' )
            ->get();

        //$comment_collection = $comments->getCollection();
        $resource = $this->collection( $comments, new CommentTransformer );

        //$resource->setPaginator( new IlluminatePaginatorAdapter( $comments ) );
        
        return $resource;
    }

    public function includeFiles( Discussion_Board $item ) {
        $page = isset( $_GET['file_page'] ) ? intval($_GET['file_page']) : 1;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $files = $item->files()
            ->orderBy( 'created_at', 'DESC' )
            ->get();

        
        $resource = $this->collection( $files, new FileTransformer );

        return $resource;
    }

    public function includeMilestone( Discussion_Board $item ) {
        $milestone = $item->milestones->first();

        if ( $milestone ) {
            return $this->item( $milestone, new MilestoneTransformer );
        }

        return null;
    }
}