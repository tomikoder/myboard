<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Jenssegers\Mongodb\Schema\Blueprint;

class NotifyMongo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('c_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('type');
            $table->int('receiver');
            $table->json('data');
            $table->boolean('readed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        #Schema::connection('mongodb')->dropIfExists('c_notifications');
    }
}
