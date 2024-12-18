<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCRMContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tenant_id')->index(); // ID của tenant
            $table->unsignedInteger('customer_id')->index(); // Tên khách hàng
            $table->string('contract_name', 255); // Tên hợp đồng
            $table->smallInteger('status'); // Trạng thái hợp đồng
            $table->date('start_date'); // Ngày bắt đầu hợp đồng
            $table->date('end_date'); // Ngày kết thúc hợp đồng
            $table->integer('payment'); // Tiền thanh toán
            $table->integer('discount_total')->nullable(); // Số tiền giảm
            $table->text('payment_notes')->nullable(); // Thông tin thanh toán mở rộng
            $table->unsignedInteger('created_by'); // Người tạo bởi
            $table->unsignedInteger('updated_by'); // Người cập nhật bởi
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
        Schema::dropIfExists('crm_contracts');
    }
}
