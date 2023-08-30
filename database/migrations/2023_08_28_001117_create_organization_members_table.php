<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organization_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id');
            $table->string('role');
            $table->string('name')->nullable();
            $table->string("email")->nullable();
            $table->string("phone_number");
            $table->string("order_number")->nullable();
            $table->string("amount_to_be_taken")->nullable();
            $table->string("number_of_payments")->nullable();
            $table->string("status")->default("active");
            $table->string("received_benefit")->default("false");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_members');
    }
};
