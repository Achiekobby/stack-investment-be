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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->uuid("unique_id");
            $table->foreignId("user_id");
            $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete();
            $table->string("title");
            $table->longText("description")->nullable();
            $table->string("amount_requested");
            $table->string("amount_received")->default(0);
            $table->string("number_of_donors")->default(0);
            $table->string("project_status")->default("inactive");
            $table->string("approval")->default("pending");
            $table->datetime("approved_on")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
