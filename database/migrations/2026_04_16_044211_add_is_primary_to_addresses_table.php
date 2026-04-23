<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $blueprint) {
            // Menambahkan kolom is_primary setelah kolom full_address
            $blueprint->boolean('is_primary')->default(false)->after('full_address');
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $blueprint) {
            $blueprint->dropColumn('is_primary');
        });
    }
};