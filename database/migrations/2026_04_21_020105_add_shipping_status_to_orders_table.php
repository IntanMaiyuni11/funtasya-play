<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        // Kolom status pengiriman
        $table->enum('shipping_status', ['dikemas', 'dikirim', 'transit', 'selesai'])
              ->default('dikemas')
              ->after('status'); // diletakkan setelah kolom status payment

        // Kolom pendukung lainnya
        $table->string('tracking_number')->nullable()->after('shipping_status');
        $table->timestamp('shipped_at')->nullable();
        $table->timestamp('completed_at')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
