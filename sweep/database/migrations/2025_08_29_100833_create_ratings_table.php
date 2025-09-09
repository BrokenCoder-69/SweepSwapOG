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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rater_id');   // who gives rating
            $table->unsignedBigInteger('rated_id');   // who receives rating
            $table->tinyInteger('rating')->check('rating >= 1 and rating <= 5'); // 1 to 5
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('rater_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('rated_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
