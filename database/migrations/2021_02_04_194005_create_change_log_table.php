<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_log', function (Blueprint $table) {
            $table->integer('uid', true);
            $table->integer('user_fk');
            $table->string('activity', 256)->comment('description of the change');
            $table->string('url', 256)->comment('URL of affected page');
            $table->binary('data')->nullable()->comment('details of the changed data');
            $table->timestamp('created_at')->useCurrent()->comment('time of change');
            $table->timestamp('updated_at')->nullable();
            $table->foreign('user_fk')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('change_log');
    }
}
