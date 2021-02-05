<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinkUserPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_user_permission', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_fk');
            $table->integer('permission_fk');
            $table->timestamp('created')->useCurrent();
            $table->foreign('user_fk')->references('user_id')->on('users');
            $table->foreign('permission_fk')->references('id')->on('user_permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link_user_permission');
    }
}
