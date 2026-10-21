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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_status', ['pending', 'preparing', 'prepared', 'ready_to_serve', 'serving', 'out_for_delivery', 'ready_for_pickup', 'completed', 'cancelled'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_status', ['pending', 'preparing', 'prepared', 'completed', 'cancelled'])->change();
        });
    }
};
