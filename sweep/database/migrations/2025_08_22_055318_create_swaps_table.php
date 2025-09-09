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
        Schema::create('swaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('proposee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('proposer_product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('proposee_product_id')->constrained('products')->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swaps');
    }
};
