<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boardables', function (Blueprint $table) {
            $table->increments( 'id' );

            $table->unsignedInteger( 'board_id' );
            $table->string('board_type');
            $table->unsignedInteger( 'boardable_id' );
            $table->string( 'boardable_type' );
            $table->integer( 'order' )->default(0);

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
        Schema::dropIfExists('boardables');
    }
}
