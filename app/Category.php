<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'title',
        'description',
        'categorible_type',
        'created_by',
        'updated_by',
    ];

    public function projects() {
        return $this->belongsToMany( 'App\Project', 'category_projects', 'category_id', 'project_id' );
    }
}
