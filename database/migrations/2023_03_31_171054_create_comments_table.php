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
            $table->increments('id')->unsigned();
            $table->integer('user_id');
            $table->integer('post_id')->unsigned()->nullable(true)->index();
            $table->integer('par_comm')->unsigned()->nullable(true)->index();
            $table->index(['user_id', 'post_id']);
            $table->longText('text');
            $table->timestamps();
        });
        Schema::table('comments', function(Blueprint $table) {
            $table->foreign('post_id')
            ->references('id')->on('posts')
            ->onDelete('cascade');
            $table->foreign('par_comm')
            ->references('id')
            ->on('comments')
            ->onDelete('cascade');
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
