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
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->integer('rating'); // 1-5
        $table->text('comment');
        $table->string('user_name_display'); // Nama yang muncul di testi (misal: "Ibu Dina")
        $table->string('user_avatar')->nullable();
        $table->string('card_color')->default('#A64B9A'); // Warna background card
        $table->boolean('is_featured')->default(false); // Muncul di home atau tidak
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
