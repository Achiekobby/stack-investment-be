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
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contribution_cycle_id');
            $table->foreign('contribution_cycle_id')->on('contribution_cycles')->references('id')->cascadeOnDelete();
            $table->foreignId("user_id");
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('amount');
            $table->string('payment_status')->default("unpaid");
            $table->string('payment_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
