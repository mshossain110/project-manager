<?php

namespace App\Observers;

use App\Project;
use App\Activity;
use Auth;


class ProjectObserver
{
    use ObserverTrait;
    /**
     * Handle the project "created" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function created(Project $project)
    {
        Activity::create([
            'actor_id'      => Auth::id(),
            'action'        => 'create_project',
            'action_type'   => 'create',
            'resource_id'   => $project->id,
            'resource_type' => 'project',
            'meta'          => [
                'project_title' => $project->title,
            ],
            'project_id'    => $project->id,
        ]);
    }

    /**
     * Handle the project "updated" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function updated(Project $project)
    {
        $this->call_attribute_methods( $project );
    }

    /**
     * Handle the project "deleted" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function deleted(Project $project)
    {
        //
    }

    /**
     * Handle the project "restored" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function restored(Project $project)
    {
        //
    }

    /**
     * Handle the project "force deleted" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function forceDeleted(Project $project)
    {
        //
    }

    protected function title( Project $item, $old_value ) {
        Activity::create([
            'actor_id'      => Auth::id(),
            'action'        => 'update_project_title',
            'action_type'   => 'update',
            'resource_id'   => $item->id,
            'resource_type' => 'project',
            'meta'          => [
                'project_title_old' => $old_value,
                'project_title_new' => $item->title
            ],
            'project_id'    => $item->id,
        ]);
    }

    protected function description( Project $item, $old_value ) {
        Activity::create([
            'actor_id'      => Auth::id(),
            'action'        => 'update_project_description',
            'action_type'   => 'update',
            'resource_id'   => $item->id,
            'resource_type' => 'project',
            'meta'          => [
                'project_title' => $item->title,
            ],
            'project_id'    => $item->id,
        ]);
    }

    protected function status( Project $item, $old_value ) {
        Activity::create([
            'actor_id'      => Auth::id(),
            'action'        => 'update_project_status',
            'action_type'   => 'update',
            'resource_id'   => $item->id,
            'resource_type' => 'project',
            'meta'          => [
                'project_title'      => $item->title,
                'project_status_old' => Project::$status[$old_value],
                'project_status_new' => $item->status
            ],
            'project_id'    => $item->id,
        ]);
    }

    protected function budget( Project $item, $old_value ) {
        Activity::create([
            'actor_id'      => Auth::id(),
            'action'        => 'update_project_budget',
            'action_type'   => 'update',
            'resource_id'   => $item->id,
            'resource_type' => 'project',
            'meta'          => [
                'project_title'      => $item->title,
                'project_budget_old' => $old_value,
                'project_budget_new' => $item->budget
            ],
            'project_id'    => $item->id,
        ]);
    }

    protected function pay_rate( Project $item, $old_value ) {
        Activity::create([
            'actor_id'      => Auth::id(),
            'action'        => 'update_project_pay_rate',
            'action_type'   => 'update',
            'resource_id'   => $item->id,
            'resource_type' => 'project',
            'meta'          => [
                'project_title'        => $item->title,
                'project_pay_rate_old' => $old_value,
                'project_pay_rate_new' => $item->pay_rate
            ],
            'project_id'    => $item->id,
        ]);
    }

    protected function est_completion_date( Project $item, $old_value ) {
        Activity::create([
            'actor_id'      => Auth::id(),
            'action'        => 'update_project_est_completion_date',
            'action_type'   => 'update',
            'resource_id'   => $item->id,
            'resource_type' => 'project',
            'meta'          => [
                'project_title'                   => $item->title,
                'project_est_completion_date_old' => $old_value,
                'project_est_completion_date_new' => $item->est_completion_date instanceof Carbon
                    ? $item->est_completion_date->toDateTimeString() : null,
            ],
            'project_id'    => $item->id,
        ]);
    }

    protected function color_code( Project $item, $old_value ) {
        Activity::create([
            'actor_id'      => Auth::id(),
            'action'        => 'update_project_color_code',
            'action_type'   => 'update',
            'resource_id'   => $item->id,
            'resource_type' => 'project',
            'meta'          => [
                'project_title'          => $item->title,
                'project_color_code_old' => $old_value,
                'project_color_code_new' => $item->color_code
            ],
            'project_id'    => $item->id,
        ]);
    }
}
