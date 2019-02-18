<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Category;
use App\Transformers\CategoryTransformer;
use League\Fractal;
use League\Fractal\Resource\Item as Item;
use League\Fractal\Resource\Collection as Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use WeDevs\PM\Category\Transformers\Category_Transformer;
// use Illuminate\Database\Capsule\Manager as DB;
use \WeDevs\ORM\Eloquent\Facades\DB;
use Illuminate\Pagination\Paginator;

class CategoryController extends ApiController {


    public function index( Request $request ) {
        $type = $request->get( 'type' );

        if ( $type ) {
            $categories = Category::where('categorible_type', $type)->get();
        } else {
            $categories = Category::get();
        }

        return $this->respondWithCollection($categories, new CategoryTransformer);
        
    }

    public function show( Request $request ) {
        $id = $request->get( 'id' );

        $category = Category::findOrFail( $id );
        return $this->respondWithItem($category, new CategoryTransformer);
    }

    public function store( Request $request ) {
        $data = [
            'title' => $request->get( 'title' ),
            'description' => $request->get( 'description' ),
            'categorible_type' => $request->get( 'categorible_type' )
        ];

        $category = Category::create( $data );
        return $this->respondWithItem($category, new CategoryTransformer);
    }

    public function update( Request $request, $id ) {

        $data = [
            'title' => $request->get( 'title' ),
            'description' => $request->get( 'description' ),
            'categorible_type' => $request->get( 'categorible_type' )
        ];

        $category = Category::findOrFail( $id );

        $category->update( $data );
        return $this->respondWithItem($category, new CategoryTransformer);  
    }

    public function destroy( $id ) {
        $category = Category::findOrFail( $id );

        $category->projects()->detach();
        $category->delete();


        return $this->respondWithMessage("Category delete Successfully");
    }

    public function bulk_destroy( Request $request ) {
        $category_ids = $request->get( 'category_ids' );
        
        if ( is_array( $category_ids ) ) {
            DB::table( 'category_project' )->whereIn( 'category_id', $category_ids )->delete();
            Category::whereIn( 'id', $category_ids )->delete();
        }

        return $this->respondWithMessage("Category delete Successfully");
    }
}