<?php

namespace App\Observers;

use App\Task;
use App\Activity;
use Auth;
class TaskObserver
{
    use ObserverTrait;
    /**
     * Handle the task "created" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function created(Task $task)
    {
        $meta = [
            'task_title' => $task->title,
        ];

        $this->log_activity( $task, 'create_task', 'create', $meta );
    }

    /**
     * Handle the task "updated" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function updated(Task $task)
    {
        $this->call_attribute_methods( $task );
    }

    public function deleting( Task $task ) {
        $meta = [
            'deleted_task_title' => $task->title,
        ];

        $this->log_activity( $task, 'delete_task', 'delete', $meta );
    }
    /**
     * Handle the task "deleted" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        //
    }

    /**
     * Handle the task "restored" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function restored(Task $task)
    {
        //
    }

    /**
     * Handle the task "force deleted" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function forceDeleted(Task $task)
    {
        //
    }

    public function title( Task $item, $old_value ) {
        $meta = [
            'task_title_old' => $old_value,
            'task_title_new' => $item->title,
        ];

        do_action( 'pm_task_title_update', $item->title, $old_value, $item );

        $this->log_activity( $item, 'update_task_title', 'update', $meta );
    }

    public function description( Task $item, $old_value ) {
        $meta = [
            'task_title' => $item->title,
        ];

        do_action( 'pm_task_description_update', $item->title, $old_value, $item );

        $this->log_activity( $item, 'update_task_description', 'update', $meta );
    }

    public function estimation( Task $item, $old_value ) {
        $meta = [
            'task_title'          => $item->title,
            'task_estimation_old' => $old_value,
            'task_estimation_new' => $item->estimation,
        ];

        $this->log_activity( $item, 'update_task_estimation', 'update', $meta );
    }

    public function start_at( Task $item, $old_value ) {
        $meta = [
            'task_title'        => $item->title,
            'task_start_at_old' => $old_value,
            'task_start_at_new' => $item->start_at instanceof Carbon ? $item->start_at->toDateTimeString() : null,
        ];

        $this->log_activity( $item, 'update_task_start_at_date', 'update', $meta );
    }

    public function due_date( Task $item, $old_value ) {
        $meta = [
            'task_title'        => $item->title,
            'task_due_date_old' => $old_value,
            'task_due_date_new' => $item->due_date instanceof Carbon ? $item->due_date->toDateTimeString() : null,
        ];

        do_action( 'pm_task_due_date_update', $meta['task_due_date_new'], $old_value, $item );

        $this->log_activity( $item, 'update_task_due_date', 'update', $meta );
    }

    public function complexity( Task $item, $old_value ) {
        $meta = [
            'task_title'          => $item->title,
            'task_complexity_old' => $old_value,
            'task_complexity_new' => $item->complexity,
        ];

        $this->log_activity( $item, 'update_task_complexity', 'update', $meta );
    }

    public function priority( Task $item, $old_value ) {
        $meta = [
            'task_title'        => $item->title,
            'task_priority_old' => $old_value,
            'task_priority_new' => $item->priority,
        ];

        $this->log_activity( $item, 'update_task_priority', 'update', $meta );
    }

    public function payable( Task $item, $old_value ) {
        $meta = [
            'task_title'       => $item->title,
            'task_payable_old' => $old_value,
            'task_payable_new' => $item->payable,
        ];

        $this->log_activity( $item, 'update_task_payable_status', 'update', $meta );
    }

    public function recurrent( Task $item, $old_value ) {
        
        $recurrent = [
            0 => 'none recurrent',
            1 => 'weekly recurrent',
            2 => 'Monthly recurrent',
            3 => 'Annually recurrent',
            9 => 'never recurrent'
        ];

        $meta = [
            'task_title'         => $item->title,
            'task_recurrent_old' => $recurrent[ intval( $old_value) ],
            'task_recurrent_new' => $recurrent[ intval( $item->recurrent ) ],
        ];
        

        $this->log_activity( $item, 'update_task_recurrent', 'update', $meta );
    }

    public function status( Task $item, $old_value ) {
        $meta = [
            'task_title'      => $item->title,
            'task_status_old' => Task::$status[$old_value],
            'task_status_new' => $item->status,
        ];

        do_action( 'pm_update_task_status', $meta['task_status_new'], $meta['task_status_old'], $item );

        $this->log_activity( $item, 'update_task_status', 'update', $meta );
    }

    private function log_activity( Task $item, $action, $action_type, $meta = null ) {
        Activity::create([
            'actor_id'      => Auth::id(),
            'action'        => $action,
            'action_type'   => $action_type,
            'resource_id'   => $item->id,
            'resource_type' => 'task',
            'meta'          => $meta,
            'project_id'    => $item->project_id,
        ]);
    }
}
