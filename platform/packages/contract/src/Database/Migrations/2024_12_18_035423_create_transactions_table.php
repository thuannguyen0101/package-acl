<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tenant_id'); // ID của tenant
            $table->unsignedInteger('contract_id'); // ID hợp đồng
            $table->unsignedInteger('customer_id'); // ID khách hàng
            $table->integer('amount'); // Số tiền thanh toán chưa khấu trừ
            $table->text('deductions')->nullable(); // Các khoản khấu trừ
            $table->integer('total_amount'); // Số tiền cần thanh toán
            $table->smallInteger('status');
            $table->unsignedBigInteger('created_by'); // Người tạo bởi
            $table->unsignedBigInteger('updated_by')->nullable(); // Người cập nhật bởi
            $table->timestamps(); // created_at và updated_at
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
        Schema::dropIfExists('transactions');
    }
}
