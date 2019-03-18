<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements( 'id' );

            $table->string( 'title' );
            $table->text( 'description' )->nullable();
            $table->integer( 'estimation' )->nullable()->default( 0 );
            $table->timestamp( 'start_at' )->nullable();
            $table->timestamp( 'due_date' )->nullable();
            $table->tinyInteger( 'complexity' )->nullable();
            $table->tinyInteger( 'priority' )->default(1)->comment( '1: High; 2: Medium; 3: Low' );
            $table->boolean( 'payable' )->default( 0 )->comment( '0: Not payable; 1: Payable');
            $table->boolean( 'recurrent' )->default( 0 )->comment( '0: Not recurrent task; 1: Recurrent task');
            $table->tinyInteger( 'status' )->default( 0 )->comment( '0: Incomplete; 1: Complete; 2: Pending');
            $table->tinyInteger( 'private', )->default( 0 )->comment( '0: public; 1: private;');

            $table->unsignedInteger( 'project_id' );
            $table->unsignedInteger( 'parent_id' )->default( 0 );

            $table->unsignedInteger( 'completed_by' )->nullable();
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
        Schema::dropIfExists('tasks');
    }
}
