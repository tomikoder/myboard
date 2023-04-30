<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_super_user');
            $table->boolean('is_baned');
            $table->integer('num_posts');
            $table->date('last_viwed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_super_user');
            $table->dropColumn('is_baned');
            $table->dropColumn('num_posts');
            $table->dropColumn('last_viwed');
        });
    }
}
