<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProvinceAndCityColumnsToAddressesTable extends Migration
{
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            // Cek apakah kolom sudah ada, jika belum tambahkan
            if (!Schema::hasColumn('addresses', 'province')) {
                $table->string('province')->nullable()->after('full_address');
            }
            if (!Schema::hasColumn('addresses', 'city')) {
                $table->string('city')->nullable()->after('province');
            }
            if (!Schema::hasColumn('addresses', 'district')) {
                $table->string('district')->nullable()->after('city');
            }
        });
    }

    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['province', 'city', 'district']);
        });
    }
}