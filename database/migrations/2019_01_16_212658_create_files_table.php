<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements( 'id' );

            $table->integer( 'fileable_id' )->nullable();
            $table->string( 'fileable_type' )->nullable();
            $table->string( 'type' )->default('file');
            $table->bigInteger( 'attachment_id' )->nullable();
            $table->integer( 'parent' )->default(0);
            $table->unsignedInteger( 'project_id' )->nullable();
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
        Schema::dropIfExists('files');
    }
}
