<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Вовочка',
            'Армия',
            'Штирлиц',
            'Муж и жена',
            'Программисты',
            'Животные',
        ];

        foreach ($categories as $title) {
            Category::updateOrCreate(
                ['slug' => Str::slug($title)],
                ['title' => $title]
            );
        }
    }
}
