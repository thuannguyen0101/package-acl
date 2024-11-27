<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryMultiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_multi', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->unsignedBigInteger('root_id')->default(0);
            $table->unsignedBigInteger('parent_id')->default(0);
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
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('category_multi');
    }
}
