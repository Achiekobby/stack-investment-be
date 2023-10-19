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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('organization_id');
            $table->foreign("organization_id")->references('id')->on('organizations')->cascadeOnDelete();
            $table->string("user_name");
            $table->string("email");
            $table->string("user_phone");
            $table->string("link")->nullable();
            $table->string("status")->default("pending");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
