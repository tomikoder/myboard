<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Jenssegers\Mongodb\Schema\Blueprint;

class CreateMongoMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('mongo_msgs_in', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->uuid('link')->unique();
            $table->int('receiver');
            $table->json('body');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        #Schema::connection('mongodb')->dropIfExists('mongo_msgs_in');
    }
}
