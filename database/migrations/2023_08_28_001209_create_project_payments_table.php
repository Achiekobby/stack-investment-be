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
        Schema::create('project_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid("unique_id");
            $table->foreignId("project_id");
            $table->foreign("project_id")->references("id")->on("projects")->cascadeOnDelete();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->string("first_name");
            $table->string("last_name");
            $table->string("email");
            $table->string("amount_donated");
            $table->string("donation_id");
            $table->string("donation_reference")->nullable();
            $table->string("medium_of_payment")->nullable();
            $table->string("payment_status")->default("unpaid");
            $table->datetime("paid_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_payments');
    }
};
