<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments( 'id' );

            $table->text( 'content' );
            $table->string( 'mentioned_users' )->nullable();
            $table->unsignedInteger( 'commentable_id' );
            $table->string( 'commentable_type' );

            $table->unsignedInteger( 'project_id' );
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
        Schema::dropIfExists('comments');
    }
}
