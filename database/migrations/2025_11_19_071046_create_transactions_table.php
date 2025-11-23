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
            $table->id('id_transaction');
            $table->string('transaction_code')->unique();
            $table->dateTime('date');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('payment_method'); // cash, debit, credit, e-wallet
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id_user')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->nullable()->references('id_customer')->on('customers')->onDelete('cascade');
            $table->timestamps();
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
