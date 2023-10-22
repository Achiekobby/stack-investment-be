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
        Schema::create('payout_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id");
            $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete();
            $table->string("method")->default("mobile_money");
            $table->string("account_name");
            $table->string("momo_number");
            $table->string("momo_provider");
            $table->string("recipient_code");
            $table->string("currency")->default("GHS");
            $table->string("status")->default("active");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_methods');
    }
};
