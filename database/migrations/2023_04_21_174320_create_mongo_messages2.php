<?php

use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMongoMessages2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('mongo_msgs_out', function (Blueprint $table) {
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
        #Schema::dropIfExists('mongo_msgs_out');
    }
}
