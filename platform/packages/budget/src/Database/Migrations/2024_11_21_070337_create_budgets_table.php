<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('area_id')->default(0);
            $table->unsignedBigInteger('area_source_id')->default(0);
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('expense_category_id');
            $table->unsignedBigInteger('account_money_id');
            $table->bigInteger('money');
            $table->text('meta_file');
            $table->longText('meta_content');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
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
        Schema::dropIfExists('budgets');
    }
}
