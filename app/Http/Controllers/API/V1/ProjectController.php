<?php

namespace App\Http\Controllers\Api\V1;


use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Transformers\UserTransformer;
use App\Transformers\ProjectTransformer;
use App\Http\Requests\UserRequest;
use League\Fractal\Resource\Item;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Project;
use App\User;
use App\ProjectRoleUser;
use App\Category;
use App\Meta;
use App\Task_List;
use Auth;

class ProjectController extends ApiController {


	public function index( Request $request ) { 
		$per_page = $request->get( 'per_page' );
		$status   = $request->get( 'status' );
		$category = $request->get( 'category' );

		$per_page = 15;
 
		$projects = $this->fetch_projects( $category, $status );

		$projects = $projects->orderBy(  'created_at', 'DESC' );

		if ( -1 === intval( $per_page ) ) {
			$per_page = $projects->get()->count();
		}
		
		$projects = $projects->paginate( $per_page );
		
		return $this->respondWithPaginator($projects, new ProjectTransformer);
        
    }

    private function projects_meta( $category ) {
        $user_id = \Auth::id();
        $eloquent_sql     = $this->fetch_projects_by_category( $category );
        $total_projects   = $eloquent_sql->count();
        $eloquent_sql     = $this->fetch_projects_by_category( $category );
        $total_incomplete = $eloquent_sql->where( 'status', Project::INCOMPLETE )->count();
        $eloquent_sql     = $this->fetch_projects_by_category( $category );
        $total_complete   = $eloquent_sql->where( 'status', Project::COMPLETE )->count();
        $eloquent_sql     = $this->fetch_projects_by_category( $category );
        $total_pending    = $eloquent_sql->where( 'status', Project::PENDING )->count();
        $eloquent_sql     = $this->fetch_projects_by_category( $category );
        $total_archived   = $eloquent_sql->where( 'status', Project::ARCHIVED )->count();
        $eloquent_sql     = $this->fetch_projects_by_category( $category );
        $favourite 		  = $eloquent_sql->whereHas( 'meta', function ( $query ) use( $user_id ) {
                        $query->where('meta_key', '=', 'favourite_project')
                            ->where('entity_id', '=', $user_id)
                            ->whereNotNull( 'meta_value' );
                    } )->count();
        $user_id          = \Auth::id();

        $meta  = [
            'total_projects'   => $total_projects,
            'total_incomplete' => $total_incomplete,
            'total_complete'   => $total_complete,
            'total_pending'    => $total_pending,
            'total_archived'   => $total_archived,
            'total_favourite'   => $favourite,
        ];

        return $meta;
    }

    private function fetch_projects( $category, $status ) {
		$projects = $this->fetch_projects_by_category( $category );
		$user_id = Auth::id();
		
		// if ($status == 'favourite' ) {
		// 	$projects = $projects->whereHas( 'meta', function ( $query ) use( $user_id ) {
		// 		$query->where('meta_key', '=', 'favourite_project')
		// 			->where('entity_id', '=', $user_id)
		// 			->whereNotNull( 'meta_value' );
		// 	} );
		// }

    	if ( in_array( $status, Project::$status ) ) {
			$status   = array_search( $status, Project::$status );
			$projects = $projects->where( 'status', $status );
		}
		

		// $projects = $projects->leftJoin('meta', function ( $join ) use( $user_id ) {
		// 	$join->on('projects.id', '=', 'meta.project_id' )
		// 	->where('meta_key', '=', 'favourite_project')->where('entity_id', '=', $user_id);
		// })
		// ->selectRaw('projects.*' )
		// ->groupBy('projects.id' )
		// ->orderBy('meta.meta_value', 'DESC');

		return $projects;
    }

    private function fetch_projects_by_category( $category = null ) {
    	$user_id = Auth::id();

		if ( $category ) {
    		$category = Category::where( 'categorible_type', 'project' )
	    		->where( 'id', $category )
	    		->first();
	    	
	    	if ( $category ) {
	    		$projects = $category->projects()->with('assignees');
	    	} else {
	    		$projects = Project::with('assignees');
	    	}
    		
    	} else {
    		$projects = Project::with('assignees');
    	}
    	// if ( !pm_has_manage_capability( $user_id ) ){
    		// $projects = $projects->whereHas('assignees', function( $q ) use ( $user_id ) {
    		// 			$q->where('user_id', $user_id );
    		// 		});
    	// }
    	return $projects;
    }

	public function show( Request $request ) {
		$id 	  = $request->get('id');
		$user_id  = \Auth::id();
		$project  =  Project::findOrFail( $id );

		

		$resource = new Item( $project, new ProjectTransformer );
		$list_view = pm_get_meta( $user_id, $id, 'list_view', 'list_view_type' );
		$resource->setMeta([
			'list_view_type' => $list_view ? $list_view->toArray() : null
		]);

        return $this->respondWithArray( $resource );
	}

	public function store( Request $request ) {
		// Extraction of no empty inputs and create project
		$data    = $request->only( [
			'title',
			'description',
			'status',
			'budget',
			'pay_rate',
			'est_completion_date',
			'color_code',
			'order',
			'projectable_type',
		]);
		$project = Project::create( $data );

		// Establishing relationships
		$category_ids = $request->get( 'categories' );
		if ( $category_ids ) {
			$project->categories()->sync( $category_ids );
		}

		$assignees = $request->get( 'assignees' );
		$assignees[] = [
			'user_id' => Auth::id(),
			'role_id' => 1, // 1 for manager
		];
		//craeate list inbox when create project
		// $this->create_list_inbox($project->id);
		
		if ( is_array( $assignees ) ) {
			$this->assign_users( $project, $assignees );
		}
		return $this->respondWithItem($project, new ProjectTransformer);
		
		
	}

	public function update( Request $request ) {
		// Extract non empty inputs and update project
		$data    = $request->only( [
			'title',
			'description',
			'status',
			'budget',
			'pay_rate',
			'est_completion_date',
			'color_code',
			'order',
			'projectable_type',
		]);

		$project = Project::find( $data['id'] );

		$project->update_model( $data );

		// Establishing relationships
		$category_ids = $request->get( 'categories' );
		if ( $category_ids ) {
			$project->categories()->sync( $category_ids );
		}

		$assignees = $request->get( 'assignees' );

		if ( is_array( $assignees ) ) {
			$project->assignees()->detach();
			$this->assign_users( $project, $assignees );
		}
        return $this->respondWithItem($project, new ProjectTransformer);
	}

	public function destroy( Request $request ) {
		$id = $request->get('id');

		// Find the requested resource
		$project =  Project::find( $id );

		// Delete related resourcess
		$project->categories()->detach();

		$tasks = $project->tasks;

        foreach ( $tasks as $task ) {
            $task->files()->delete();
            $task->assignees()->delete();
            $task->metas()->delete();
        }
		$project->tasks()->delete();

		$task_lists = $project->task_lists;
		foreach ( $task_lists as $task_list ) {
			$task_list->boardables()->delete();	        
	        $task_list->metas()->delete();
	        $task_list->files()->delete();
		}
		$project->task_lists()->delete();

		$project->discussion_boards()->delete();
		$project->milestones()->delete();
		$project->comments()->delete();
		$project->assignees()->detach();

		$project->settings()->delete();
		$project->activities()->delete();
		$project->meta()->delete();

		// Delete the main resource
		$project->delete();
		return $this->respondWithArray([
			'message' => pm_get_text('success_messages.project_deleted')
		]);
	}

	private function assign_users( Project $project, $assignees = [] ) {
		$assignees = is_array( $assignees ) ? $assignees : []; 
		
		foreach ( $assignees as $assignee ) {
			ProjectRoleUser::firstOrCreate([
				'user_id'    => $assignee['user_id'],
				'role_id'    => $assignee['role_id'],
				'project_id' => $project->id,
			]);
		}
	}

	public function favourite_project (Request $request) {
        $project_id = $request->get( 'id' );
        $favourite  = $request->get( 'favourite' );
        $user_id    = \Auth::id();


        if ($favourite == 'true') {
            $lastFavourite = Meta::where([
				'entity_id'  => $user_id,
				'entity_type' => 'project',
				'meta_key'		=> 'favourite_project'
			])->max('meta_value');
			$lastFavourite = intval($lastFavourite ) + 1;
			// TODO: Hree you have to manage favaorite project

        }

		return $this->respondWithArray( [ 'message' =>   "The project has been marked as favourite" ] );

        
	}
	
	function create_list_inbox($project_id) {

		$meta = Meta::firstOrCreate([
			'entity_id'	=> $project_id,
			'entity_type' => 'task_list',
			'meta_key' => 'list-inbox',
			'project_id' => $project_id,
		]);

		if ( empty( $meta->meta_value ) ) {

			$list = Task_List::create([
				'title' => __('Inbox', 'wedevs-project-manager'),
				'description' => __('This is a system default task list. Any task without an assigned tasklist will appear here.', 'wedevs-project-manager'),
				'order' => 999999,
				'project_id' => $project_id,
			]);

			$meta->meta_value = $list->id;
			$meta->save();

		}
	}


}
