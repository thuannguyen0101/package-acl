<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   //shift_start_time
        Schema::create('config_attendances', function (Blueprint $table) {
            $precision = 1;
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->time('shift_start_time', $precision);
            $table->time('break_start_time', $precision);
            $table->time('break_end_time', $precision);
            $table->time('shift_end_time', $precision);
            $table->integer('full_time_minimum_hours');
            $table->text('exclude_weekends');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_attendances');
    }
}
