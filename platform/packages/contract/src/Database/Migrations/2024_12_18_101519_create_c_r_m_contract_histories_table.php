<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCRMContractHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_contract_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('contract_id')->index();
            $table->unsignedInteger('transaction_id')->nullable()->index();
            $table->unsignedInteger('action');
            $table->text('data');
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
        Schema::dropIfExists('crm_contract_histories');
    }
}
