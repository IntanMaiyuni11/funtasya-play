<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->text('short_description')->nullable()->after('description');
        $table->string('age_range')->nullable()->after('short_description'); // Contoh: 4-7 Tahun
        $table->integer('weight')->default(100)->after('stock');
        $table->json('gallery')->nullable()->after('image'); // Untuk foto tambahan
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
