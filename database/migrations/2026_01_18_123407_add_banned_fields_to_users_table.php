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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_banned')->default(false);
            $table->text('ban_reason')->nullable();
            $table->timestamp('banned_at')->nullable();
            $table->timestamp('banned_until')->nullable();

            $table->foreignId('banned_by')->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->index('is_banned');
            $table->index('banned_until');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_banned',
                'ban_reason',
                'banned_at',
                'banned_until',
                'banned_by'
            ]);
        });
    }
};
