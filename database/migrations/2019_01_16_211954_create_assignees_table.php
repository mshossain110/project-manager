<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssigneesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignees', function (Blueprint $table) {
            $table->increments( 'id' );

            $table->unsignedInteger( 'task_id' );
            $table->unsignedInteger( 'assigned_to' );
            $table->tinyInteger( 'status' )->default(0)->comment('0: Not started; 1: Working; 2: Accomplished');

            $table->unsignedInteger( 'created_by' )->nullable();
            $table->unsignedInteger( 'updated_by' )->nullable();

            $table->timestamp( 'assigned_at' )->nullable();
            $table->timestamp( 'stated_at' )->nullable();
            $table->timestamp( 'completed_at' )->nullable();
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
        Schema::dropIfExists('assignees');
    }
}
