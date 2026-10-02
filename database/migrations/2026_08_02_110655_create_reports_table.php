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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            // Кто пожаловался
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');

            // На кого пожаловались
            $table->foreignId('reported_user_id')->constrained('users')->onDelete('cascade');

            // На что пожаловались (полиморфная связь)
            $table->morphs('reportable');

            // Тип жалобы
            $table->string('type')->index();

            // Причина жалобы
            $table->text('reason');

            // Дополнительные детали
            $table->json('details')->nullable();

            // Статус
            $table->string('status')->default('pending')->index();

            // Кто обрабатывал
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');

            // Решение модератора
            $table->string('resolution_type')->nullable();
            $table->foreignId('violation_id')->nullable()->constrained('violations')->onDelete('set null');

            // Комментарий модератора
            $table->text('moderator_comment')->nullable();

            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Индексы
            $table->index(['status', 'created_at']);
            $table->index(['reporter_id', 'status']);
            $table->index(['reported_user_id', 'status']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
