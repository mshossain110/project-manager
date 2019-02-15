<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;
use Carbon\Carbon;
use Auth;

class Project extends Model
{
    use Common;
    
	const INCOMPLETE = 0;
	const COMPLETE   = 1;
    const PENDING    = 2;
    const ARCHIVED   = 3;

    // Allowed project statuses and their keys
    public static $status = [
        0 => 'incomplete',
        1 => 'complete',
        2 => 'pending',
        3 => 'archived'
    ];


    protected $fillable = [
		'title',
		'description',
		'status',
		'budget',
		'pay_rate',
		'est_completion_date',
		'color_code',
		'order',
        'projectable_type',
        'completed_at',
		'created_by',
		'updated_by',
    ];

    protected $dates = [
    	'est_completion_date'
    ];

    public function scopeSearch( $query, $search ) { 
        $query->where('title',  'LIKE', '%'.$search.'%' )->orWhere( 'description', 'LIKE', '%'.$search.'%');
    }

    public function categories() {
        return $this->belongsToMany( 'App\Category',  'category_project', 'project_id', 'category_id' );
    }

    /**
     *  we join pm_roles table with pm_role_user 
     */
    public function assignees() {
        $role_id = Role::where('status', 1)->get(['id'])->toArray()->unique()->pluck('id');
        return $this->belongsToMany( 'App\User', 'project_role_user', 'project_id', 'user_id' )
            ->whereIn( 'role_id', $role_id)
            ->withPivot( 'project_id', 'role_id' );
    }

    public function task_lists() {
        return $this->hasMany( 'App\Task_List', 'project_id' );
    }

    public function tasks() {
        return $this->hasMany( 'App\Task', 'project_id' );
    }

    public function discussion_boards() {
        return $this->hasMany( 'App\Discussion_Board', 'project_id' );
    }

    public function milestones() {
        return $this->hasMany( 'App\Milestone', 'project_id' );
    }

    public function files() {
        return $this->hasMany( 'App\File', 'project_id' );
    }

    public function comments() {
        return $this->hasMany( 'App\Comment', 'project_id' );
    }

    public function activities() {
        return $this->hasMany( 'App\Activity', 'project_id' );
    }

    public function settings() {
        return $this->hasMany( 'App\Settings', 'project_id' );
    }

    public function meta() {
        return $this->hasMany( 'App\Meta', 'project_id' );
    }

    public function managers() {
        $role_id = Role::where('slug', 'manager')->first()->id;
        return $this->assignees()->where('role_id', $role_id);
    }

    public function co_workers() {
        $role_id = Role::where('slug', 'co_worker')->first()->id;
        return $this->assignees()->where('role_id', $role_id);
    }

    public function getFavouriteAttribute(  ) {
        $user_id = Auth::user()->id;
        $favourite = $this->meta()->where('meta_key', '=', 'favourite_project' )
                ->where( 'entity_type', 'project' )
                ->where( 'entity_id', '=', $user_id )->first();
        

        return empty($favourite) ? null: $favourite;
    }

 

    public function getStatusAttribute( $value ) {
        $value = (int) $value;

        if (array_key_exists($value, self::$status)) {
            return self::$status[(int)$value];
        }

        return self::$status[0];
    }

    public function setStatusAttribute( $value ) {
        $value = strtolower( $value );
        $key   = array_search( $value, self::$status );

        if ( array_key_exists( $value, self::$status ) ) {
            $this->attributes['status'] = $value;
        } else {
            $this->attributes['status'] = $key;
        }
    }

    public function setEstCompletionDateAttribute( $date ) {
        $this->attributes['est_completion_date'] = Carbon::parse( $date );
    }
}
