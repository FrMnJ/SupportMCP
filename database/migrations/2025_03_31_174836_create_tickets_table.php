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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string("issue");
            $table->foreignId("client_id")->constrained("clients")->onDelete("cascade");
            $table->foreignId("assigned_to")->nullable()->constrained("users")->onDelete("set null");
            $table->string("description");
            $table->enum("status", ["open", "in_progress", "resolved", "closed", "pending"])->default("open");
            $table->enum("priority", ["low", "medium", "high"])->default("medium");
            $table->enum("category", ["bug", "feature_request", "question", "other"])->default("other");
            $table->timestamp("assigned_at")->nullable();
            $table->timestamp("resolved_at")->nullable();
            $table->text("comments")->nullable();
            $table->integer("score")->default(0);
            $table->text("resolution")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
