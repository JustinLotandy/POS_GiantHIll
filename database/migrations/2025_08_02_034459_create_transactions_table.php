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
        Schema::create('transactions', function (Blueprint $table) {
            $table->char('id_Transaction', 36)->primary(); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->char('id_PaymentMethod', 36);
            $table->decimal('total', 10, 2);
            $table->decimal('paid', 10, 2);
            $table->decimal('change', 10, 2);
            $table->timestamps();

            $table->foreign('id_PaymentMethod')->references('id_PaymentMethod')->on('payment_methods')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
