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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->enum('category', [
                                    'Electronics',
                                    'Fashion',
                                    'Home',
                                    'Beauty',
                                    'Sports',
                                    'Books',
                                    'Toys',
                                    'Automotive',
                                    'Grocery',
                                    'Health',
                                    'Jewelry & Watches',
                                    'Music & Instruments',
                                    'Furniture',
                                    'Others',
                                ]);

            $table->boolean('is_used');
            $table->string('usage_duration')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('images')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
