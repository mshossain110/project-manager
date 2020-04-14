<?php

namespace App\Observers;

use App\Milestone;
use App\Activity;
use Auth;

class MilestoneObserver
{
    use ObserverTrait;
    /**
     * Handle the discussion_ board "created" event.
     *
     * @param  \App\Milestone  $milestone
     * @return void
     */
    public function created(Milestone $milestone)
    {
        $meta = [
            'milestone_title' => $milestone->title,
        ];

        $milestone->created_by = Auth::id();
        $milestone->save();


        $this->log_activity( $milestone, 'create_milestone', 'create', $meta );
    }

    /**
     * Handle the discussion_ board "updated" event.
     *
     * @param  \App\Milestone  $milestone
     * @return void
     */
    public function updated(Milestone $milestone)
    {
        $this->call_attribute_methods( $milestone );

        $milestone->updated_by = Auth::id();
        $milestone->save();
    }

    public function deleting( Milestone $milestone ) {

        $meta = [
            'deleted_milestone_title' => $milestone->title,
        ];

        $this->log_activity( $milestone, 'delete_milestone', 'delete', $meta );
    }

    /**
     * Handle the discussion_ board "deleted" event.
     *
     * @param  \App\Milestone  $milestone
     * @return void
     */
    public function deleted(Milestone $milestone)
    {
        //
    }

    /**
     * Handle the discussion_ board "restored" event.
     *
     * @param  \App\Milestone  $milestone
     * @return void
     */
    public function restored(Milestone $milestone)
    {
        //
    }

    /**
     * Handle the discussion_ board "force deleted" event.
     *
     * @param  \App\Milestone  $milestone
     * @return void
     */
    public function forceDeleted(Milestone $milestone)
    {
        //
    }

    public function title( Milestone $item, $old_value ) {
        $meta = [
            'milestone_title_old' => $old_value,
            'milestone_title_new' => $item->title,
        ];
        $this->log_activity( $item, 'update_milestone_title', 'update', $meta );
    }

    public function description( Milestone $item, $old_value ) {
        $meta = [
            'milestone_title' => $item->title,
        ];

        $this->log_activity( $item, 'update_milestone_description', 'update', $meta );
    }

    public function order( Milestone $item, $old_value ) {
        $meta = [
            'milestone_title'     => $item->title,
            'milestone_order_old' => $old_value,
            'milestone_order_new' => $item->order,
        ];

        $this->log_activity( $item, 'update_milestone_order', 'update', $meta );
    }

    private function log_activity( Milestone $item, $action, $action_type, $meta = null ) {
        Activity::create([
            'actor_id'      => Auth::id(),
            'action'        => $action,
            'action_type'   => $action_type,
            'resource_id'   => $item->id,
            'resource_type' => 'milestone',
            'meta'          => $meta,
            'project_id'    => $item->project_id,
        ]);
    }
}
