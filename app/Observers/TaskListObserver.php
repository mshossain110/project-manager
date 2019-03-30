<?php

namespace App\Observers;

use App\Task_List;
use App\Activity;
use Auth;

class TaskListObserver
{
    use ObserverTrait;
    /**
     * Handle the task_ list "created" event.
     *
     * @param  \App\Task_List  $taskList
     * @return void
     */
    public function created(Task_List $taskList)
    {
        $meta = [
            'task_list_title' => $taskList->title,
        ];

        $this->log_activity( $taskList, 'create_task_list', 'create', $meta );
    }

    /**
     * Handle the task_ list "updated" event.
     *
     * @param  \App\Task_List  $taskList
     * @return void
     */
    public function updated(Task_List $taskList)
    {
        $this->call_attribute_methods( $taskList );
    }
    public function deleting(Task_List $taskList ) {
        $meta = [
            'deleted_task_list_title' => $taskList->title,
        ];

        $this->log_activity( $taskList, 'delete_task_list', 'delete', $meta );
    }
    /**
     * Handle the task_ list "deleted" event.
     *
     * @param  \App\Task_List  $taskList
     * @return void
     */
    public function deleted(Task_List $taskList)
    {
        //
    }

    /**
     * Handle the task_ list "restored" event.
     *
     * @param  \App\Task_List  $taskList
     * @return void
     */
    public function restored(Task_List $taskList)
    {
        //
    }

    /**
     * Handle the task_ list "force deleted" event.
     *
     * @param  \App\Task_List  $taskList
     * @return void
     */
    public function forceDeleted(Task_List $taskList)
    {
        //
    }


    public function title( Task_list $item, $old_value ) {
        $meta = [
            'task_list_title_old' => $old_value,
            'task_list_title_new' => $item->title,
        ];
        $this->log_activity( $item, 'update_task_list_title', 'update', $meta );
    }

    public function description( Task_list $item, $old_value ) {
        $meta = [
            'task_list_title' => $item->title,
        ];

        $this->log_activity( $item, 'update_task_list_description', 'update', $meta );
    }

    public function order( Task_list $item, $old_value ) {
        $meta = [
            'task_list_title'     => $item->title,
            'task_list_order_old' => $old_value,
            'task_list_order_new' => $item->order,
        ];

        $this->log_activity( $item, 'update_task_list_order', 'update', $meta );
    }

    public function status( Task_list $item, $old_value ) {
        $meta = [
            'task_list_title'     => $item->title,
            'task_list_status_old' => $old_value,
            'task_list_status_new' => $item->status,
        ];
        
        if ( $item->status == 'archived' ) {
            $action = 'archived_task_list';
        } else {
            $action = 'restore_task_list';
        }

        $this->log_activity( $item, $action, 'update', $meta );
    }

    private function log_activity( Task_list $item, $action, $action_type, $meta = null ) {
        Activity::create([
            'actor_id'      => Auth::id(),
            'action'        => $action,
            'action_type'   => $action_type,
            'resource_id'   => $item->id,
            'resource_type' => 'task_list',
            'meta'          => $meta,
            'project_id'    => $item->project_id,
        ]);
    }
}
