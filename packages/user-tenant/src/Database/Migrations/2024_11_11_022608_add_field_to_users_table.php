<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('username')->unique()->after('name');
            $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
            $table->string('phone')->unique()->nullable()->after('password');
            $table->tinyInteger('status')->nullable()->after('phone');
            $table->string('address')->nullable()->after('status');
            $table->smallInteger('gender')->nullable()->after('address');
            $table->date('birthday')->nullable()->after('gender');
            $table->string('avatar')->nullable()->after('birthday');
            $table->unsignedBigInteger('created_by')->nullable()->after('avatar');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
