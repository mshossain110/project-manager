<?php

namespace App\Transformers;

use App\Task;
use App\User;
use App\Boardable;
use Illuminate\Pagination\Paginator;
use League\Fractal\TransformerAbstract;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;


class TaskTransformer extends TransformerAbstract {

    use ResourceEditors;

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'assignees'
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'boards', 'comments', 'files', 'completer', 'activities', 'creator', 'updater', 'task_list', 
    ];

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform( Task $item ) {
        
        return [
                'id'          => (int) $item->id,
                'title'       => $item->title,
                'description' => $item->description, //[ 'html' => pm_get_content( $item->description ), 'content' => $item->description ],
                'estimation'  => $item->estimation,
                'start_at'    =>  $item->start_at,
                'due_date'    => $item->due_date ,
                'complexity'  => $item->complexity,
                'priority'    => $item->priority,
                // 'order'       => (int) $order,
                'payable'     => $item->payable,
                'recurrent'   => $item->recurrent,
                'parent_id'   => $item->parent_id,     
                'status'      => $item->status,
                'private'      => $item->private,
                'project_id'  => $item->project_id,
                'category_id' => $item->category_id,
                'created_at'  => $item->created_at,
                'completed_at' => $item->completed_at,
                'updated_at'  => $item->updated_at,
                'task_list_id' => $item->task_list,
                //'meta'        => $this->meta( $item ),
        ];
    }


    public function meta( Task $item ) {
        $meta = $item->metas()->get()->toArray();
        $meta = wp_list_pluck( $meta, 'meta_value', 'meta_key' );

        $metas = array_merge( $meta, [
            'total_comment'  => $item->comments->count(),
            'total_files'    => $item->files->count(),
            'total_board'    => $item->boards->count(),
            'total_assignee' => $item->assignees->count(),
            'can_complete_task' => pm_user_can_complete_task( $item ),
        ] );
        
	    return $metas;
    }


    /**
     * Include task list
     *
     * @param Task $item
     * @return \League\Fractal\Resource\Item
     */
    public function includeTaskList( Task $item ) { 
        $task_list = $item->task_lists->first();
        
        if ( ! empty( $task_list ) ) {
            return $this->item( $task_list, new TaskListTransformer );
        }

        return null;
    }

    /**
     * Include boards in which the task is atttached
     *
     * @param Task $item
     * @return \League\Fractal\Resource\Collection
     */
    public function includeBoards( Task $item ) {
        $page = isset( $_GET['board_page'] ) ? intval($_GET['board_page']) : 1;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        }); 

        $boards = $item->boards()
            ->orderBy( 'created_at', 'DESC' )
            ->paginate( 10 );

        $board_collection = $boards->getCollection();
        $resource = $this->collection( $board_collection, new BoardTransformer );

        $resource->setPaginator( new IlluminatePaginatorAdapter( $boards ) );

        return $resource;
    }

    public function includeComments( Task $item ) {
        $page = isset( $_GET['comment_page'] ) ? intval( $_GET['comment_page'] ) : 1;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        }); 

        $comments = $item->comments()
            ->orderBy( 'created_at', 'ASC' )
            ->paginate( config('app.comment_per_page') );

        $comment_collection = $comments->getCollection();
        $resource = $this->collection( $comment_collection, new CommentTransformer );

        $resource->setPaginator( new IlluminatePaginatorAdapter( $comments ) );

        return $resource;
    }

    public function includeAssignees( Task $item ) {
        $users = $item->user;

        return $this->collection( $users, new UserTransformer );
    }

    public function includeActivities( Task $item ) {
        $page = isset( $_GET['activitie_page'] ) ? intval( $_GET['activitie_page'] ) : 1;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        }); 

        $activities = $item->activities()->paginate( 10 );
        return $this->collection( $activities, new ActivityTransformer );
    }

    public function includeFiles( Task $item ) {
        $page = isset( $_GET['file_page'] ) ? intval( $_GET['file_page'] ) : 1;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        }); 

        $files = $item->files()->paginate( 10 );

        $file_collection = $files->getCollection();
        $resource = $this->collection( $file_collection, new FileTransformer );

        $resource->setPaginator( new IlluminatePaginatorAdapter( $files ) );

        return $resource;
    }

    public function includeCompleter( $item ) {
        $completer = $item->completer;
        return $this->item( $completer, new UserTransformer );
    }
}
