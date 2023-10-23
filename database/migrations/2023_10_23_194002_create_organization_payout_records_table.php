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
        Schema::create('organization_payout_records', function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid");
            $table->unsignedBigInteger("admin_id");
            $table->unsignedBigInteger("withdrawal_request_id")->nullable();
            $table->unsignedBigInteger("recipient_id")->nullable();
            $table->string("recipient_code")->nullable();
            $table->string("transfer_code")->nullable();
            $table->string("amount_payable")->nullable();
            $table->string("status")->default("initialized");
            $table->datetime("initialized_at")->nullable();
            $table->datetime("finalized_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_payout_records');
    }
};
