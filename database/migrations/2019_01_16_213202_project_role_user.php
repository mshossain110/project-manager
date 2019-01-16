<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProjectRoleUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_role_user', function (Blueprint $table) {
            $table->unsignedInteger( 'user_id' );
            $table->unsignedInteger( 'role_id' );
            $table->unsignedInteger( 'project_id' )->nullable();
            $table->unsignedInteger( 'assigned_by' )->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_role_user');
    }
}
