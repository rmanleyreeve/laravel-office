<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->integer('uid', true);
            $table->integer('employee_fk');
            $table->date('absence_date');
            $table->enum('absence_type', ['HOLIDAY', 'SICKNESS'])->default('HOLIDAY');
            $table->enum('duration', ['FULL_DAY', 'HALF_DAY_AM', 'HALF_DAY_PM'])->default('FULL_DAY');
            $table->text('notes')->nullable();
            $table->boolean('deleted')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->foreign('employee_fk')->references('uid')->on('employees');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absences');
    }
}
