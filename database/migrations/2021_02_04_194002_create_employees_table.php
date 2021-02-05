<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->integer('uid', true);
            $table->string('firstname', 100);
            $table->string('surname', 100);
            $table->string('role', 128);
            $table->string('initials', 5);
            $table->string('email', 100)->nullable();
            $table->text('notes')->nullable();
            $table->binary('image')->nullable();
            $table->string('rfid_code', 64);
            $table->boolean('active')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->boolean('deleted')->default(0);
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
