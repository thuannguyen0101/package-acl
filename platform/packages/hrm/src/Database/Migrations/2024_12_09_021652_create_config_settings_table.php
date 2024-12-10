<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_settings', function (Blueprint $table) {
            $precision = 1;
            $table->id();
            $table->unsignedBigInteger('tenant_id');

            $table->time('shift_start_time', $precision)->nullable()->comment('setting attendance');
            $table->time('break_start_time', $precision)->nullable()->comment('setting attendance');
            $table->time('break_end_time', $precision)->nullable()->comment('setting attendance');
            $table->time('shift_end_time', $precision)->nullable()->comment('setting attendance');
            $table->integer('full_time_minimum_hours')->nullable()->comment('setting attendance');
            $table->text('exclude_weekends')->nullable()->comment('setting attendance');
            $table->text('half_day_weekends')->nullable()->comment('setting attendance');

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_settings');
    }
}
