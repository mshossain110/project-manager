<?php
namespace App\Transformers;

use App\Task_List;
use App\Task;
use League\Fractal\TransformerAbstract;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Illuminate\Pagination\Paginator;
use League\Fractal\Resource\Collection;


class TaskListTransformer extends TransformerAbstract {

    use ResourceEditors;

    protected $defaultIncludes = [
    ];

    protected $availableIncludes = [
        'assignees',
        'tasks',
        'complete_tasks',
        'incomplete_tasks',
        'comments',
        'files',
        'creator', 'updater', 'milestone'
    ];

    public function transform( Task_List $item ) {
        return [
            'id'          => (int) $item->id,
            'title'       => $item->title,
            'description' =>  $item->description,
            'order'       => (int) $item->order,
            'status'      => $item->status,
            'created_at'  => $item->created_at,
            // 'meta'        => $this->meta( $item ),
        ];

        
    }


    public function meta( Task_List $item ) {
        $meta = $item->metas()->get()->pluck('meta_value', 'meta_key')->toArray();

        return array_merge( $meta, [
            'total_tasks'            => $item->tasks()->count(),
            'total_complete_tasks'   => $item->tasks()->where( 'status', Task::COMPLETE )->count(),
            'total_incomplete_tasks' => $item->tasks()->where( 'status', Task::INCOMPLETE )->count(),
            'total_comments'         => $item->comments()->count(),
            'totla_files'            => $item->files()->count(),
            'total_assignees'        => $item->assignees()->count(),
        ] );
    }

    public function includeAssignees( Task_List $item ) {
        $assignees = $item->assignees;

        return $this->collection( $assignees, new User_Transformer );
    }

    public function includeComments( Task_List $item ) {
        $page = isset( $_GET['comment_page'] ) ? intval($_GET['comment_page']) : 1;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        }); 

        $comments = $item->comments()
            ->orderBy( 'created_at', 'ASC' )
            ->paginate( pm_config('app.comment_per_page') );

        $comment_collection = $comments->getCollection();
        $resource = $this->collection( $comment_collection, new CommentTransformer );

        $resource->setPaginator( new IlluminatePaginatorAdapter( $comments ) );

        return $resource;
    }

    public function includeFiles( Task_List $item ) {
        $page = isset( $_GET['file_page'] ) ? intval($_GET['file_page']) : 1;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        }); 

        $files = $item->files()->paginate( 10 );

        $file_collection = $files->getCollection();
        $resource = $this->collection( $file_collection, new FileTransformer );

        $resource->setPaginator( new IlluminatePaginatorAdapter( $files ) );

        return $resource;
    }

    public function includeMilestone( Task_List $item ) {
        $milestone = $item->milestones->first();

        if ( $milestone ) {
            return $this->item( $milestone, new MilestoneTransformer );
        }

        return null;
    }

    public function includeTasks( Task_List $item ) {
        $tasks = $item->tasks;
        return $this->collection( $tasks, new TaskTransformer );
    }


    public function includeCompleteTasks( Task_List $item ) {
        $page = isset( $_GET['complete_task_page'] ) ? intval($_GET['complete_task_page']) : 1;
        $per_page = pm_get_settings( 'complete_tasks_per_page' );
        $per_page = $per_page ? $per_page : 5;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        }); 

        $tasks = $item->tasks()
                ->where( 'status', 1 );

        $tasks = apply_filters( 'pm_complete_task_query', $tasks,  $item->project_id, $item );
        if ( $per_page == '-1' ) {
            $per_page = $tasks->count();
        }
        $tasks =  $tasks->orderBy( pm_tb_prefix() . 'pm_boardables.order', 'ASC' )
            ->paginate( $per_page );

        return $this->make_paginated_tasks( $tasks );
    }

    public function includeIncompleteTasks( Task_List $item ) {
        $page = isset( $_GET['incomplete_task_page'] ) ? intval( $_GET['incomplete_task_page'] ) : 1;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        }); 

        $per_page = pm_get_settings( 'incomplete_tasks_per_page' );
        $per_page = $per_page ? $per_page : 5;
        $tasks = $item->tasks()
            ->where( 'status', 0 );
        $tasks = apply_filters( 'pm_incomplete_task_query', $tasks,  $item->project_id, $item );
        
        if ( $per_page == '-1' ) {
            $per_page = $tasks->count();
        }
        
        $tasks = $tasks->orderBy( pm_tb_prefix() . 'pm_boardables.order', 'ASC' )
            ->paginate( $per_page );
        return $this->make_paginated_tasks( $tasks );
    }

    private function make_paginated_tasks( $tasks ) {
        $task_collection = $tasks->getCollection();
        $resource = $this->collection( $task_collection, new Task_Transformer );

        $resource->setPaginator( new IlluminatePaginatorAdapter( $tasks ) );

        return $resource;
    }
}
