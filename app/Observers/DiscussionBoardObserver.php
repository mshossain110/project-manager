<?php

namespace App\Observers;

use App\Discussion_Board;
use App\Activity;
use Auth;

class DiscussionBoardObserver
{
    use ObserverTrait;
    /**
     * Handle the discussion_ board "created" event.
     *
     * @param  \App\Discussion_Board  $discussionBoard
     * @return void
     */
    public function created(Discussion_Board $discussionBoard)
    {
        $meta = [
            'discussion_board_title' => $discussionBoard->title,
        ];

        $this->log_activity( $discussionBoard, 'create_discussion_board', 'create', $meta );
    }

    /**
     * Handle the discussion_ board "updated" event.
     *
     * @param  \App\Discussion_Board  $discussionBoard
     * @return void
     */
    public function updated(Discussion_Board $discussionBoard)
    {
        $this->call_attribute_methods( $discussionBoard );
    }

    public function deleting( Discussion_Board $discussionBoard ) {

        $meta = [
            'deleted_discussion_board_title' => $discussionBoard->title,
        ];

        $this->log_activity( $discussionBoard, 'delete_discussion_board', 'delete', $meta );
    }

    /**
     * Handle the discussion_ board "deleted" event.
     *
     * @param  \App\Discussion_Board  $discussionBoard
     * @return void
     */
    public function deleted(Discussion_Board $discussionBoard)
    {
        //
    }

    /**
     * Handle the discussion_ board "restored" event.
     *
     * @param  \App\Discussion_Board  $discussionBoard
     * @return void
     */
    public function restored(Discussion_Board $discussionBoard)
    {
        //
    }

    /**
     * Handle the discussion_ board "force deleted" event.
     *
     * @param  \App\Discussion_Board  $discussionBoard
     * @return void
     */
    public function forceDeleted(Discussion_Board $discussionBoard)
    {
        //
    }

    public function title( Discussion_Board $item, $old_value ) {
        $meta = [
            'discussion_board_title_old' => $old_value,
            'discussion_board_title_new' => $item->title,
        ];
        $this->log_activity( $item, 'update_discussion_board_title', 'update', $meta );
    }

    public function description( Discussion_Board $item, $old_value ) {
        $meta = [
            'discussion_board_title' => $item->title,
        ];

        $this->log_activity( $item, 'update_discussion_board_description', 'update', $meta );
    }

    public function order( Discussion_Board $item, $old_value ) {
        $meta = [
            'discussion_board_title'     => $item->title,
            'discussion_board_order_old' => $old_value,
            'discussion_board_order_new' => $item->order,
        ];

        $this->log_activity( $item, 'update_discussion_board_order', 'update', $meta );
    }

    private function log_activity( Discussion_Board $item, $action, $action_type, $meta = null ) {
        Activity::create([
            'actor_id'      => Auth::id(),
            'action'        => $action,
            'action_type'   => $action_type,
            'resource_id'   => $item->id,
            'resource_type' => 'discussion_board',
            'meta'          => $meta,
            'project_id'    => $item->project_id,
        ]);
    }
}
