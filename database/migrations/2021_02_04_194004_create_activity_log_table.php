<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->integer('uid', true);
            $table->integer('employee_fk');
            $table->timestamp('time_logged')->nullable();
            $table->enum('activity', ['ENTRY', 'EXIT']);
            $table->timestamp('created_at')->useCurrent();
            $table->boolean('notification_read')->default(0);
            $table->enum('record_type', ['SCANNER', 'MANUAL'])->default('SCANNER');
            $table->timestamp('updated')->nullable();
            $table->timestamp('original_value')->nullable();
            $table->text('update_reason')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->foreign('employee_fk')->references('uid')->on('employees');
            $table->index(['time_logged', 'employee_fk'], 'idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_log');
    }
}
