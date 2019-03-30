<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Observers\CommentdObserver;

class Comment extends Model
{
    protected $fillable = [
        'content',
        'mentioned_users',
        'commentable_id',
        'commentable_type',
        'project_id',
        'created_by',
        'updated_by',
    ];

        /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        Comment::observe(CommentdObserver::class);
    }

    public function replies() {
        return $this->hasMany( $this, 'commentable_id' )->where( 'commentable_type', 'comment' );
    }

    public function files() {
        return $this->hasMany( 'App\File', 'fileable_id' )->where( 'fileable_type', 'comment' );
    }

    public static function parent_comment( $comment_id ) {
        $comment = self::find( $comment_id );

        if ( $comment && $comment->commentable_type == 'comment' ) {
            $comment = self::parent_comment( $comment->commentable_id );
        }

        return $comment;
    }

    public function discussion_board() {
        return $this->belongsTo('App\Discussion_Board', 'commentable_id');
    }

    public function task_list() {
        return $this->belongsTo( 'App\Task_List', 'commentable_id');
    }

    public function task() {
        return $this->belongsTo( 'App\Task', 'commentable_id');
    }

    public function setMentionedUsersAttribute( $value ) {
        $this->attributes['mentioned_users'] = serialize( $value );
    }

    public function getMentionedUsersAttribute( $value ) {
        return unserialize( $value );
    }
}
