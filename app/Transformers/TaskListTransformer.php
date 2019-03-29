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
        'tasks', 'complete_tasks', 'incomplete_tasks',
    ];

    protected $availableIncludes = [
        'assignees',
        'tasks',
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
            'meta'        => $this->meta( $item ),
        ];

        
    }


    public function meta( Task_List $item ) {
        $meta = [];// $item->metas()->get()->pluck('meta_value', 'meta_key')->toArray();

        return array_merge( $meta, [
            'total_tasks'            => $item->tasks()->count(),
            'total_complete_tasks'   => $item->tasks()->where( 'status', Task::COMPLETE )->count(),
            'total_incomplete_tasks' => $item->tasks()->where( 'status', Task::INCOMPLETE )->count(),
            // 'total_comments'         => $item->comments()->count(),
            // 'totla_files'            => $item->files()->count(),
            // 'total_assignees'        => $item->assignees()->count(),
        ] );
    }

    public function includeAssignees( Task_List $item ) {
        $assignees = $item->assignees;

        return $this->collection( $assignees, new User_Transformer );
    }

    public function includeComments( Task_List $item ) {
        $comments = $item->comments;
        return $this->collection( $comments, new CommentTransformer );
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
        $per_page = 20;
        $tasks = $item->tasks()->where( 'status', 1 )->paginate( $per_page );
        

        return $this->make_paginated_tasks( $tasks );
    }

    public function includeIncompleteTasks( Task_List $item ) {
    
        $per_page = 20;
        $tasks = $item->tasks()
            ->where( 'status', 0 )->paginate( $per_page );
        
        return $this->make_paginated_tasks( $tasks );
    }

    private function make_paginated_tasks( $tasks ) {
        $task_collection = $tasks->getCollection();
        $resource = $this->collection( $task_collection, new TaskTransformer );

        $resource->setPaginator( new IlluminatePaginatorAdapter( $tasks ) );

        return $resource;
    }
}
