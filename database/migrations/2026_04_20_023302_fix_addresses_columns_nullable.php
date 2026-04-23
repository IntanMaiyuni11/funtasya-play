<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixAddressesColumnsNullable extends Migration
{
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            // Ubah kolom menjadi nullable
            $table->string('city')->nullable()->change();
            $table->string('postal_code')->nullable()->change();
            
            // Hapus kolom is_default jika tidak dipakai
            if (Schema::hasColumn('addresses', 'is_default')) {
                $table->dropColumn('is_default');
            }
        });
    }

    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('city')->nullable(false)->change();
            $table->string('postal_code')->nullable(false)->change();
            $table->boolean('is_default')->default(0);
        });
    }
}