<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('user_id', true)->comment('primary key');
            $table->string('username', 50)->comment('Login identifier');
            $table->string('password', 128)->comment('uses Bcrypt salted hash');
            $table->string('fullname', 128)->comment('User full name');
            $table->string('user_email', 256);
            $table->boolean('administrator')->default(0)->comment('User is admin with all permissions');
            $table->boolean('active')->default(1);
            $table->text('user_notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('password_reset_token', 128)->nullable()->comment('Used for forgotten password function');
            $table->boolean('deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
