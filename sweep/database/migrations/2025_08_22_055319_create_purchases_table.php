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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');


            $table->string("card_number")->nullable();
            $table->string("expiry_date")->nullable();
            $table->string("cvv")->nullable();
            $table->string("cardholder_name")->nullable();


            $table->enum('mobile_banking', [
                                    'bkash',
                                    'rocket',
                                    'nagad',
                                    'upay'
                                ])->nullable();  
            $table->string("payment_mobile")->nullable();
            



            $table->string("email");
            $table->string("first_name");
            $table->string("last_name");
            $table->string("address");
            $table->string("mobile");
            $table->string("city");
            $table->enum('division', [
                                    'Dhaka',
                                    'Chattogram',
                                    'Rajshahi',
                                    'Khulna',
                                    'Sylhet',
                                    'Barishal',
                                    'Rangpur',
                                    'Mymensingh'
                                ]);  
            $table->string("post_code");
                                


            $table->integer('price');
            $table->integer('delivery');
            $table->integer('service');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
