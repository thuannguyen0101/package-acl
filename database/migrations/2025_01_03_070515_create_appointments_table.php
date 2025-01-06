<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id')->notNullable();
            $table->string('customer_id', 255)->notNullable();
            $table->string('name', 255)->nullable();
            $table->integer('phone')->nullable();
            $table->integer('product_id')->nullable();
            $table->dateTime('date_at')->notNullable();
            $table->text('description')->nullable();
            $table->integer('status')->default(1); // 1: Mới, 2: đã xác nhận, 3: đã hủy
            $table->integer('assigned_id');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('appointments');
    }
}
