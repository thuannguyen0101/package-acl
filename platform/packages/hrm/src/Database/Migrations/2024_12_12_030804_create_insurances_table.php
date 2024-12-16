<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsurancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('tenant_id')->index();
            $table->string('number_insurance');
            $table->date('start_insurance');
            $table->date('date_closing')->nullable();
            $table->date('sent_date')->nullable();
            $table->date('return_date')->nullable();
            $table->string('treatment_location')->nullable();
            $table->smallInteger('status');
            $table->text('note')->nullable();

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
        Schema::dropIfExists('insurances');
    }
}
