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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reporter_id'); // who submits the report
            $table->unsignedBigInteger('reported_id'); // who is being reported
            $table->enum('reason', [
                                    'Inappropriate content',
                                    'Spam',
                                    'Fraudulent listing',
                                    'Copyright infringement',
                                    'Others',
                                ]);                 // reason for report
            $table->text('description');                    // reason for report
            $table->text('admin_feedback')->nullable(); // feedback by admin
            $table->enum('status', ['pending','reviewed'])->default('pending');
            $table->timestamps();

            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reported_id')->references('id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
