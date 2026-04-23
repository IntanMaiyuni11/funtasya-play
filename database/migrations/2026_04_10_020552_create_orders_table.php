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
       Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->string('order_code')->unique(); // Contoh: FTS-001
    $table->foreignId('user_id')->constrained();
    $table->foreignId('address_id')->constrained(); // Relasi ke alamat
    $table->integer('total_price');
    $table->enum('status', ['process', 'complete', 'cancelled'])->default('process'); // Sesuai desain Admin
    $table->string('payment_method'); // VA / QRIS
    $table->string('payment_proof')->nullable(); // Untuk upload bukti bayar
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
