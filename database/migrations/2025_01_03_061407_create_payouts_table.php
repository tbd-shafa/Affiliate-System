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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_user_id')->constrained('users'); // References users table
            $table->decimal('amount', 10, 2); // Amount paid out
            $table->string('payment_by')->nullable(); // Payment method (e.g.,cash on delivery , PayPal, Bank Transfer)
            $table->text('remarks')->nullable(); // Optional notes
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
