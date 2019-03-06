<?php


namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use League\Fractal\Resource\Item as Item;
use League\Fractal\Resource\Collection as Collection;
use App\Transformers\TaskListTransformer;
use App\Transformers\TaskTransformer;
use App\Transformers\ActivityTransformer;
use App\Traits\Last_activity;
use App\Task_List;
use App\Project;
use App\Boardable;
use App\Board;
use App\Assignee;
use App\Comment;
use App\Activity;
use App\Task;
use Carbon\Carbon;
use Auth;

class TaskController extends ApiController {

    use Last_activity;

    public function index( Request $request ) {
        $project_id = $request->get( 'project_id' );
        $search     = $request->get( 's' );
        $per_page   = 20;



        if ( $search ) {
            $tasks = Task::where( 'project_id', $project_id )
                ->parent()
                ->where('title', 'LIKE', '%'.$search.'%')
                ->orderBy( 'created_at', 'DESC')
                ->get();

            return $this->respondWithCollection( $tasks, new TaskTransformer );
        } else {
            $tasks = Task::where( 'project_id', $project_id )
                ->parent()
                ->orderBy( 'created_at', 'DESC')
                ->paginate( $per_page );

            return $this->respondWithPaginator($tasks,  new TaskTransformer );
        }
    }

    public function show( Request $request, $id ) {
        $project_id = $request->get( 'project_id' );

        $task = Task::with('task_lists')->find($id);
        
        return $this->respondWithItem( $task, New TaskTransformer );
    }

    public function store( Request $request ) {
        $project_id    = $request->get( 'project_id' );
        $list_id      = $request->get( 'list_id' );
        $assignees     = $request->get( 'assignees' );
        $data          = $request->all();
        
        
        // if ( empty( $board_id ) ) {
        //     $inbox = pm_get_meta($project_id, $project_id, 'task_list', 'list-inbox');
        //     $board_id = $inbox->meta_value;
        //     $data['board_id'] = $inbox->meta_value;
        // }

        $project       = Project::find( $project_id );
        $board         = Board::find( $list_id );
        
        if ( $project ) {
            $task = Task::create( $data );
        }

        
        if ( $task && $board ) {
            $latest_order = Boardable::latest_order( $board->id, $board->type, 'task' );
            $Boardable    = Boardable::create([
                'board_id'       => $board->id,
                'board_type'     => $board->type,
                'boardable_id'   => $task->id,
                'boardable_type' => 'task',
                'order'          => $latest_order + 1,
            ]);
        }

        if ( is_array( $assignees ) && $task ) {
            $this->attach_assignees( $task, $assignees );
        }

        return $this->respondWithItem( $task,  New TaskTransformer );
    }

    private function attach_assignees( Task $task, $assignees = [] ) {
        foreach ( $assignees as $user_id ) {
            if ( ! intval( $user_id ) ) {
                continue ;
            }
            $data = [
                'task_id'     => $task->id,
                'assigned_to' => $user_id,
                'project_id'  => $task->project_id,
            ];

            $assignee = Assignee::firstOrCreate( $data );

            if ( !$assignee->assigned_at ) {
                $assignee->assigned_at = Carbon::now();
                $assignee->save();
            }
        }
    }

    private function update_task_status( Task $task ){
        $user = Auth::id();
        $data = [
            'task_id'     => $task->id,
            'assigned_to' => $user->ID,
            'project_id'  => $task->project_id,
        ];

        $assignee = Assignee::where( $data )->first();

        if ( !$assignee) {
            return false;
        }

        if(  $task->status == 'complete' && !$assignee->completed_at ){
            $assignee->completed_at = Carbon::now();
            $assignee->status = 2;
            $assignee->save();
        }

        if(  $task->status == 'incomplete' && $assignee->completed_at ){
            $assignee->completed_at = null;
            $assignee->status = 0;
            $assignee->save();
        }
    }

    public function update( Request $request, $id ) {
        $data       = $request->all();
        $project_id = $request->get( 'project_id' );
        $list_id    = $request->get( 'list_id' );
        $assignees  = $request->get( 'assignees' );
        $assignees  = $assignees ? $assignees : [];
        
        $task = Task::with('assignees')->find( $task_id );

        if ( !empty( $assignees ) && is_array( $assignees ) && $task ) {
            $task->assignees()->whereNotIn( 'assigned_to', $assignees )->delete();
            $this->attach_assignees( $task, $assignees );
        }
                
        $task->update_model( $data );
        return $this->respondWithItem($task,  New TaskTransformer);

    }

    public function change_status( Request $request, $id ) {
        $task         = Task::with('assignees')->find( $id );
        $status       = $request->get( 'status' );
        $old_value    = $task->status;
        $task->status = $status;

        if ($status) {
            $task->completed_by = Auth::id();
            $task->completed_at = Carbon::now();
        } else {
            $task->completed_by = null;
            $task->completed_at = null;
        }
        
        if ( $task->save() ) {
            $this->update_task_status( $task );
        }
        
        return $this->respondWithItem($task,  New TaskTransformer);
        
    }



    public function destroy( Request $request, $id ) {
        $project_id = $request->get( 'project_id' );

        $task = Task::find($id);
        
        
        // Delete relations assoicated with the task
        $task->Boardables()->delete();
        $task->files()->delete();
        $comments = $task->comments;
        
        foreach ($comments as $comment) {
            $comment->replies()->delete();
            $comment->files()->delete();
        }
        
        $task->comments()->delete();
        $task->assignees()->delete();
        $task->metas()->delete();
        Task::where('parent_id', $task->id)->delete();
        // Delete the task
        $task->delete();
        
        return $this->respondWithMessage("Task delete successfully.");
    }

    public function attach_to_board( Request $request, $id ) {
        $board_id = $request->get( 'board_id' );

        $task  = Task::find( $id );
        $board = Board::find( $board_id );

        $latest_order = Boardable::latest_order( $board->id, $board->type, 'task' );
        $Boardable    = Boardable::firstOrCreate([
            'board_id'       => $board->id,
            'board_type'     => $board->type,
            'boardable_id'   => $task->id,
            'boardable_type' => 'task',
            'order'          => $latest_order + 1,
        ]);

        return $this->respondWithItem( $task, new TaskTransformer );

        
    }

    public function detach_from_board( Request $request, $id ) {
        $board_id = $request->get( 'board_id' );

        $task  = Task::find( $id );
        $board = Board::find( $board_id );

        $Boardable = Boardable::where( 'board_id', $board->id )
            ->where( 'board_type', $board->type )
            ->where( 'boardable_id', $task->id )
            ->where( 'boardable_type', 'task' )
            ->first();

        $Boardable->delete();
    }

    public function attach_users( Request $request, $id ) {
        $project_id = $request->get( 'project_id' );
        $user_ids   = $request->get( 'users' );

        $project    = Project::find( $project_id );
        $task       = Task::find( $id );

        if ( $project && $task && is_array( $user_ids ) ) {
            foreach ( $user_ids as $user_id ) {
                $data = [
                    'task_id'     => $task->id,
                    'assigned_to' => $user_id,
                    'assigned_at' => Carbon::now(),
                    'project_id'  => $project->id,
                ];
                Assignee::create( $data );
            }
        }

        return $this->respondWithItem( $task, new TaskTransformer );   
    }

    public function detach_users( Request $request, $id ) {
        $project_id = $request->get( 'project_id' );
        $user_ids   = $request->get( 'users' );

        $project    = Project::find( $project_id );
        $task       = Task::find($id);

        if ( $task && is_array( $user_ids ) ) {
            $task->assignees()->whereIn( 'assigned_to', $user_ids )->delete();
        }

        return $this->respondWithItem( $task, new TaskTransformer );
    }

    public function reorder( Request $request ) {
        $board_id = $request->get( 'board_id' );
        $board_type = $request->get( 'board_type' );
        $task_orders = $request->get( 'task_orders' );

        if ( is_array( $task_orders ) ) {
            foreach ( $task_orders as $task_order ) {
                $Boardable = Boardable::where( 'board_id', $board_id )
                    ->where( 'board_type', $board_type )
                    ->where( 'boardable_id', $task_order['id'] )
                    ->where( 'boardable_type', 'task' )
                    ->first();

                if ( $Boardable ) {
                    $Boardable->order = (int) $task_order['order'];
                    $Boardable->save();
                }
            }
        }
    }

    public function privacy( Request $request, $id ) {
        $project_id = $request->get( 'project_id' );
        $privacy = $request->get( 'is_private' );
    }

    public function task_sorting( Request $request ) {
        
        $project_id = $request->get( 'project_id' );
        $list_id    = $request->get( 'list_id' );
        $task_id    = $request->get( 'task_id' );
        $orders     = $request->get( 'orders' );
        $receive    = $request->get( 'receive' );
        $task       = [];
        $sender_list_id = false;

        if ( isset( $receive ) && $receive == 1 ) {
            $task = pm_get_task( $task_id );
            $sender_list_id = $task ? $task['data']['task_list']['data']['id'] : false;
            $Boardable = Boardable::where( 'board_type', 'task_list' )
                ->where( 'boardable_id', $task_id )
                ->first();
            
            if ( $Boardable ) {
                $Boardable->board_id = $list_id;
                $Boardable->update();
            } 

            $task = pm_get_task( $task_id );
        }
        
        foreach ( $orders as $order ) {
            $index   = empty( $order['index'] ) ? 0 : intval( $order['index'] );
            $task_id = empty( $order['id'] ) ? '' : intval( $order['id'] );

            $Boardable = Boardable::where( 'board_id', $list_id )
                    ->where( 'board_type', 'task_list' )
                    ->where( 'boardable_id', $task_id )
                    ->where( 'boardable_type', 'task' )
                    ->first();

            if ( $Boardable ) {
                $Boardable->order = $index;
                $Boardable->save();
            }
        }
    }



    public function activities( Request $request, $id ) {

        $current_page = $request->get( 'activityPage' );
        $per_page = 10;

        $activities = Activity::where('resource_id', $id)
            ->where( 'resource_type', 'task' )
            ->orderBy( 'created_at', 'DESC' )
            ->paginate( $per_page );

        return $this->respondWithPaginator($activities, new ActivityTransformer);
    }
}


