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
        Schema::create('affiliate_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('address');
            $table->string('acc_name'); // Account Holder's Name
            $table->string('acc_no');   // Account Number
            $table->string('bank_name'); // Bank Name
            $table->string('branch_address')->nullable(); // Optional Branch Address
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('affiliate_link')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_users');
    }
};
