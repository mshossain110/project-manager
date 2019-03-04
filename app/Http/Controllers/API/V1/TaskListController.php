<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use League\Fractal\Resource\Item as Item;
use League\Fractal\Resource\Collection as Collection;
use App\Transformers\TaskListTransformer;
use App\Boardable;
use App\Milestone;
use App\Board;
use App\Task_List;
use App\Task;


class TaskListController extends ApiController{

    public function index( Request $request) {

        $project_id = $request->get( 'project_id' );
        $status     = $request->get( 'status' );
        $per_page   = 15;
        
        $status = isset( $status ) ? intval( $status ) : 1;
        


        $task_lists = Task_List::where( 'project_id', $project_id)
            ->where( 'status', $status );

        $task_lists = $task_lists->orderBy( 'order', 'DESC' )
            ->paginate( $per_page );

        return $this->respondWithPaginator($task_lists, new TaskListTransformer);
    }
    
    public function show( Request $request, $id ) {
        $project_id   = $request->get( 'project_id' );
        $task_list_id = $request->get( 'id' );

        $task_list = Task_List::with( 'tasks' )->findOrFail($id);

        return $this->respondWithItem($task_list, New TaskListTransformer);
    }

    public function store( Request $request ) {
        $milestone_id = $request->get( 'milestone' );
        $project_id = $request->get( 'project_id' );
        $data = $request->only([
            'title',
            'description',
            'order',
            'status',
            'project_id'
        ]);

        // $milestone     = Milestone::find( $milestone_id );
        $latest_order  = Task_List::latest_order($project_id);
        $data['order'] = $latest_order + 1;
        $task_list     = Task_List::create( $data );

        // if ( $milestone ) {
        //     $this->attach_milestone( $task_list, $milestone );
        // }

        return $this->respondWithItem($task_list,  new TaskListTransformer);
    }

    public function update( Request $request, $id ) {
        $project_id   = $request->get( 'project_id' );
        $milestone_id = $request->get( 'milestone' );

        $milestone = Milestone::find( $milestone_id );
        $task_list = Task_List::find($id);

        $task_list->update_model( $data );

        // if ( $milestone ) {
        //     $this->attach_milestone( $task_list, $milestone );
        // } else {
        //     $task_list->milestones()->detach();
        // }
        
        return $this->respondWithItem($task_list, new TaskListTransformer);
    }

    public function destroy( Request $request, $id ) {
        // Grab user inputs
        $project_id   = $request->get( 'project_id' );

        // Select the task list to be deleted
        $task_list = Task_List::find($id);
        // Delete relations
        $this->detach_all_relations( $task_list );

        // Delete the task list
        $task_list->delete();
        return $this->respondWithMessage("Task List delete Successfully.");
    }

    private function attach_milestone( Task_List $task_list, Milestone $milestone ) {
        $boardable = Boardable::where( 'boardable_id', $task_list->id )
            ->where( 'boardable_type', 'task_list' )
            ->where( 'board_type', 'milestone' )
            ->first();

        if ( !$boardable ) {
            $boardable = Boardable::firstOrCreate([
                'boardable_id'   => $task_list->id,
                'boardable_type' => 'task_list',
                'board_id'       => $milestone->id,
                'board_type'     => 'milestone'
            ]);
        } else {
            $boardable->update([
                'board_id' => $milestone->id
            ]);
        }
    }

    private function detach_all_relations( Task_List $task_list ) {
        $comments = $task_list->comments;
        foreach ( $comments as $comment ) {
            $comment->replies()->delete();
            $comment->files()->delete();
        }
        $task_list->comments()->delete();
        
        $tasks = $task_list->tasks;
        foreach ( $tasks as $task ) {
            $task->files()->delete();
            $task->comments()->delete();
            $task->assignees()->delete();
            $task->metas()->delete();
            Task::where('parent_id', $task->id)->delete();
            $task->delete();
        }
        $task_list->metas()->delete();
        $task_list->files()->delete();
        $task_list->milestones()->detach();
    }

    public function attach_users( Request $request, $id ) {
        $project_id = $request->get( 'project_id' );

        $task_list = Task_List::find($id);

        $user_ids = explode( ',', $request->get( 'users' ) );

        if ( !empty( $user_ids ) ) {
            foreach ( $user_ids as $user_id ) {
                $data = [
                    'board_id' => $task_list->id,
                    'board_type' => 'task_list',
                    'boardable_id' => $user_id,
                    'boardable_type' => 'user'
                ];
                Boardable::firstOrCreate( $data );
            }
        }

        return $this->respondWithItem( $task_list, new TaskListTransformer );

        
    }

    public function detach_users( Request $request ) {
        $project_id = $request->get( 'project_id' );
        $task_list_id = $request->get( 'task_list_id' );

        $task_list = Task_List::where( 'id', $task_list_id )
            ->where( 'project_id', $project_id )
            ->first();

        $user_ids = explode( ',', $request->get( 'users' ) );

        $task_list->users()->whereIn( 'boardable_id', $user_ids )->delete();

        return $this->respondWithItem( $task_list, new TaskListTransformer );
    }

    public function privacy( Request $request ) {
        $project_id = $request->get( 'project_id' );
        $task_list_id = $request->get( 'task_list_id' );
        $privacy = $request->get( 'is_private' );
        pm_update_meta( $task_list_id, $project_id, 'task_list', 'privacy', $privacy );
        return $this->respondWithMessage( '');
    }

    public function list_sorting( Request $request ) {

        $orders  = $request->get( 'orders' );
        $orders  = array_reverse( $orders );

        foreach ( $orders as $index => $order ) {
            //$index   = empty( $order['index'] ) ? 0 : intval( $order['index'] );
            $list_id = empty( $order['id'] ) ? '' : intval( $order['id'] );

            $board = Board::where( 'id', $list_id )
                    ->where( 'type', 'task_list' )
                    ->first();

            if ( $board ) {
                $board->order = $index;
                $board->save();
            }
        }

    }

    public function list_search( Request $request ) {
        global $wpdb;
        $project_id  = $request->get( 'project_id' );
        $title       = $request->get( 'title' );

        $task_lists = Task_List::where( function($q) use( $title ) {
            if ( !empty( $title ) ) {
                $q->where('title', 'like', '%'.$title.'%');
            } 
        })
        ->get();

        return $this->respondWithCollection( $task_lists, new TaskListTransformer );
    }

}
