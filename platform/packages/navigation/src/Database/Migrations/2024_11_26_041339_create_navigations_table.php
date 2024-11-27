<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->integer('root_id')->default(0);
            $table->integer('parent_id')->default(0);
            $table->text('url');
            $table->string('type', 50)->nullable()->comment('Loại danh mục: default, news');
            $table->string('icon', 50)->nullable();
            $table->text('view_data')->nullable();
            $table->tinyInteger('label')->default(0);
            $table->tinyInteger('layout')->default(0);
            $table->tinyInteger('sort')->default(0);
            $table->tinyInteger('is_auth')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->json('meta')->nullable();
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
        Schema::dropIfExists('navigations');
    }
}
