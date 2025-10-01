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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('courier_name');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->timestamp('order_time');
            $table->string('amount');
            $table->enum('status', ['success', 'readying', 'ready', 'delivered', 'canceled', 'rejected'])->default('readying');
            $table->string('location');
            $table->json('details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
