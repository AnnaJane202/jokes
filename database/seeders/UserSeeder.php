<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Админ
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Администратор',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $adminRole = Role::where('title', 'admin')->first();
        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        // Модератор
        $moderator = User::updateOrCreate(
            ['email' => 'moderator@example.com'],
            [
                'name' => 'Модератор',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $moderatorRole = Role::where('title', 'moderator')->first();
        if ($moderatorRole) {
            $moderator->roles()->syncWithoutDetaching([$moderatorRole->id]);
        }

        // Обычные пользователи
        User::factory()->count(10)->create()->each(function ($user) {
            $role = Role::where('title', 'user')->first();
            if ($role) {
                $user->roles()->attach($role->id);
            }
        });
    }
}
