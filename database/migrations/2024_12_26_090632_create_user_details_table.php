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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id')->constrained(); // Link to users table
            $table->text('address')->nullable(); // Address
            $table->string('acc_name')->nullable(); // Account Holder's Name
            $table->string('acc_no', 34)->nullable();
            $table->string('bank_name')->nullable(); // Bank Name
            $table->text('branch_address')->nullable(); // Branch Address
            $table->string('phone_number')->nullable(); // Phone Number
            $table->decimal('percentage_value', 5, 2)->nullable();  //percentage setting vale
            $table->enum('status', ['pending', 'approved', 'rejected','Deleted','just_created'])->default('just_created');
            $table->string('affiliate_code')->nullable();
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
