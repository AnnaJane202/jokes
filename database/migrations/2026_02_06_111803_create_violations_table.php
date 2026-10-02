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
        Schema::create('violations', function (Blueprint $table) {
            $table->id();

            // Кто нарушил
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Кто наказал (админ/модератор)
            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Тип нарушения
            $table->string('type')->index(); // spam, abuse, copyright, etc.

            // Причина (текст для пользователя)
            $table->text('reason');

            // Детали (для внутреннего использования)
            $table->json('details')->nullable();

            // Наказание
            $table->enum('penalty_type', ['warning', 'temp_ban', 'permanent_ban', 'content_removal'])
                ->default('warning');

            // Длительность наказания (в днях, для temp_ban)
            $table->integer('duration_days')->nullable();

            // Статус
            $table->enum('status', ['active', 'expired', 'appealed', 'revoked', 'pardoned'])
                ->default('active');

            // Срок действия
            $table->timestamp('active_until')->nullable();

            // Апелляция
            $table->text('appeal_reason')->nullable();
            $table->timestamp('appealed_at')->nullable();
            $table->foreignId('appeal_decided_by')->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->timestamp('appeal_decided_at')->nullable();
            $table->text('appeal_decision_reason')->nullable();

            // Автоматическое снятие при истечении срока
            $table->boolean('auto_revoked')->default(false);





            $table->timestamps();

            // Индексы для быстрого поиска
            $table->index(['user_id', 'status']);
            $table->index(['type', 'penalty_type']);
            $table->index('active_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};
