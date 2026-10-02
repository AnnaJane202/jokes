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
        Schema::create('appeal_evidence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('violation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->string('filename');
            $table->string('original_name');
            $table->string('path');
            $table->string('url');
            $table->integer('size');
            $table->string('mime_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appeal_evidence');
    }
};
