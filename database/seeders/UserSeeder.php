<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ==============================
        // Администратор
        // ==============================
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'              => 'Администратор',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $adminRole = Role::where('title', 'admin')->first();
        if ($adminRole) {
            $admin->roles()->sync([$adminRole->id]);   // ← было syncWithoutDetaching
        }

        // ==============================
        // Модератор
        // ==============================
        $moderator = User::updateOrCreate(
            ['email' => 'moderator@example.com'],
            [
                'name'              => 'Модератор',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $moderatorRole = Role::where('title', 'moderator')->first();
        if ($moderatorRole) {
            $moderator->roles()->sync([$moderatorRole->id]);   // ← было syncWithoutDetaching
        }

        // ==============================
        // Обычные пользователи: user1..user10
        // ==============================
        $userRole = Role::where('title', 'user')->first();

        for ($i = 1; $i <= 10; $i++) {
            $user = User::updateOrCreate(
                ['email' => "user{$i}@example.com"],
                [
                    'name'              => "Пользователь {$i}",
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            if ($userRole) {
                $user->roles()->syncWithoutDetaching([$userRole->id]);
            }
        }
    }
}
