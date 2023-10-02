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
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id");
            $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete();
            $table->string("user_uuid")->nullable();
            $table->string("project_uuid");
            $table->string("project_name");
            $table->string("amount_to_be_withdrawn");
            $table->string("approval_status")->default("pending");
            $table->string("payout_status")->default("pending");
            $table->longText("reason_for_rejection")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
    }
};
