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
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->string('guest_name');
            $table->string('guest_phone');
            $table->string('guest_id_proof_type')->nullable();
            $table->string('guest_id_proof_number')->nullable();
            $table->string('guest_id_proof_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->dropColumn(['guest_name', 'guest_phone', 'guest_id_proof_type', 'guest_id_proof_number', 'guest_id_proof_image']);
        });
    }
};
