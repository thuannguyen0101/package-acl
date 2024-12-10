<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('attendance_id')->nullable();
            $table->unsignedBigInteger('rule_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('created_id');
            $table->unsignedBigInteger('updated_id');
            $table->tinyInteger('fine_type');
            $table->integer('amount');
            $table->integer('status');
            $table->integer('note');
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
        Schema::dropIfExists('fines');
    }
}
