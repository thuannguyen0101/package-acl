<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('tenant_id')->index();
            $table->date('date')->comment("ngày làm việc")->index();
            $table->time('check_in', $precision = 1)->nullable()->comment("thời gian vào");
            $table->time('check_out', $precision = 1)->nullable()->comment("thời gian ra");
            $table->double('work', 8, 2)->default(0)->nullable()->comment("tính công 1, 1/2");
            $table->integer('late')->nullable()->comment("thời gian đi muộn tính bằng phút");
            $table->integer('early')->nullable()->comment("thời gian về sớm tính bằng phút");
            $table->integer('overtime')->default(0)->nullable()->comment('thời gian làm thêm giờ');
            $table->tinyInteger('work_shift')->default(0)->nullable()->comment("ca làm việc");
            $table->tinyInteger('attendance_status')->nullable()->comment("Trạng thái ngày công");
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->text('note')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('attendances');
    }
}
