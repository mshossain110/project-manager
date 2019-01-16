<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments( 'id' );

            $table->string( 'title' );
            $table->text( 'description' )->nullable();
            $table->tinyInteger( 'status' )->default(0)
                ->comment('0: incomplete; 1: complete; 2: pending; 3: archived');
            $table->float( 'budget' )->nullable();
            $table->float( 'pay_rate' )->nullable();
            $table->timestamp( 'est_completion_date' )->nullable();
            $table->string( 'color_code' )->nullable();
            $table->tinyInteger( 'order' )->nullable();
            $table->tinyInteger( 'private' )->nullable();
            $table->string( 'projectable_type' )->nullable();
            $table->timestamp( 'completed_at' )->nullable();
            $table->unsignedInteger( 'created_by' )->nullable();
            $table->unsignedInteger( 'updated_by' )->nullable();

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
        Schema::dropIfExists('projects');
    }
}
