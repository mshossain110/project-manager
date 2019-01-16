<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments( 'id' );
            $table->unsignedInteger( 'actor_id' );
            $table->string( 'action' );
            $table->string( 'action_type' );
            $table->unsignedInteger( 'resource_id' )->nullable();
            $table->string( 'resource_type' )->nullable();
            $table->text( 'meta' )->nullable();
            $table->unsignedInteger( 'project_id' );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
