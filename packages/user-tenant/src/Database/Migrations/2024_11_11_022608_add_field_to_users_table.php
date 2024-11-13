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
        $columns = ['username', 'tenant_id', 'phone', 'status', 'address', 'gender', 'birthday', 'avatar', 'created_by', 'updated_by'];

        $existingColumns = Schema::getColumnListing('users');

        $missingColumns = array_diff($columns, $existingColumns);

        if (!empty($missingColumns)) {
            Schema::table('users', function (Blueprint $table) use ($missingColumns) {
                $table->string('name')->nullable()->change();

                if (in_array('username', $missingColumns)) {
                    $table->string('username')->unique()->after('name');
                }

                if (in_array('tenant_id', $missingColumns)) {
                    $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                }

                if (in_array('phone', $missingColumns)) {
                    $table->string('phone')->unique()->nullable()->after('password');
                }

                if (in_array('status', $missingColumns)) {
                    $table->tinyInteger('status')->nullable()->after('phone');
                }

                if (in_array('address', $missingColumns)) {
                    $table->string('address')->nullable()->after('status');
                }

                if (in_array('gender', $missingColumns)) {
                    $table->tinyInteger('gender')->nullable()->after('address');
                }

                if (in_array('birthday', $missingColumns)) {
                    $table->date('birthday')->nullable()->after('gender');
                }

                if (in_array('avatar', $missingColumns)) {
                    $table->string('avatar')->nullable()->after('birthday');
                }

                if (in_array('created_by', $missingColumns)) {
                    $table->unsignedBigInteger('created_by')->nullable()->after('avatar');
                }

                if (in_array('updated_by', $missingColumns)) {
                    $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                }
            });
        }
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
