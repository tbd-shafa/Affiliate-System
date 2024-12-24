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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128)->nullable();
            $table->text('description')->nullable();
            $table->double('price')->default(0)->index();
            $table->integer('coin')->default(0)->index();
            $table->string('stripe_id', 128)->nullable()->index();
            $table->string('stripe_price_id', 128)->nullable()->index();
            $table->enum('billing_interval', ['month', 'year', 'once'])->nullable()->default('month')->index();
            $table->integer('ordering')->default(0)->index();
            $table->enum('is_popular', ['yes', 'no'])->default('no')->index();
            $table->enum('display_status', ['enable', 'disable'])->default('enable')->index();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
