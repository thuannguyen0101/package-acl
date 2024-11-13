<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('citizen_id', 50)->nullable();
            $table->string('name');
            $table->string('full_name');
            $table->string('business_phone', 12)->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('address')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->date('birthday')->nullable();
            $table->integer('size')->nullable();
            $table->text('description')->nullable();
            $table->longText('meta_attribute')->nullable();
            $table->tinyInteger('status');
            $table->date('start_at')->nullable();
            $table->date('expiry_at')->nullable();
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
        Schema::dropIfExists('tenants');
    }
}
