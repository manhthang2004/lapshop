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
        Schema::create('bill', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('id_user'); 
            $table->unsignedBigInteger('id_status'); 
            $table->string('name_user');
            $table->string('tel_user');
            $table->string('address_user');
            $table->date('date');
            $table->double('total');
            $table->string('payment_name'); 
            $table->string('voucher')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill');
    }
};
