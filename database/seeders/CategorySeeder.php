<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Laravel',
            'slug' => 'laravel',
            'color' => 'red',
            'description' => 'Laravel adalah framework PHP yang digunakan untuk membangun aplikasi web.',
        ]);

        Category::create([
            'name' => 'Web Programming',
            'slug' => 'Web-Programming',
            'color' => 'blue',
            'description' => 'Web Programming adalah proses pengembangan aplikasi web.',
        ]);

        Category::create([
            'name' => 'Mobile Programming',
            'slug' => 'Mobile-Programming',
            'color' => 'green',
            'description' => 'Mobile Programming adalah proses pengembangan aplikasi mobile.',
        ]);
    }
}
