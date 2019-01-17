<?php

namespace App\Transformers;

use App\Comment;
use League\Fractal\TransformerAbstract;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;


class CommentTransformer extends TransformerAbstract {

    use ResourceEditors;

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $availableIncludes = [
        'replies'
    ];

    protected $defaultIncludes = [
        'files', 'creator', 'updater'
    ];

    public function transform( Comment $item ) {
        return [
            'id'               => (int) $item->id,
            'content'          => pm_get_content( $item->content ),
            'commentable_type' => $item->commentable_type,
            'commentable_id'   => $item->commentable_id,
            'created_at'       => format_date( $item->created_at ),
            'meta'       => [
                'total_replies' => $item->replies->count(),
            ],

        ];
    }



    /**
     * Include replies to a comment
     *
     * @param Comment $item
     * @return \League\Fractal\Resource\Collection
     */
    public function includeReplies( Comment $item ) {
        $page = isset( $_GET['reply_page'] ) ? intval( $_GET['reply_page'] ) : 1;

        $replies = $item->replies()->paginate( config('app.comment_per_page'), ['*'], 'reply_page', $page );

        $reply_collection = $replies->getCollection();
        $resource = $this->collection( $reply_collection, new CommentTransformer );

        $resource->setPaginator( new IlluminatePaginatorAdapter( $replies ) );

        return $resource;
    }

    /**
     * Include files to a comment
     *
     * @param Comment $item
     * @return \League\Fractal\Resource\Collection
     */
    public function includeFiles( Comment $item ) {
        $page = isset( $_GET['file_page'] ) ? intval( $_GET['file_page'] ) : 1;

        $files = $item->files()->get();

        $resource = $this->collection( $files, new FileTransformer );

        return $resource;
    }
}
