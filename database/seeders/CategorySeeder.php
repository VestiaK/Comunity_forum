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
        Category::updateOrCreate([
            'slug' => 'laravel',
        ], [
            'name' => 'Laravel',
            'color' => 'red',
            'description' => 'Laravel adalah framework PHP yang digunakan untuk membangun aplikasi web.',
        ]);

        Category::updateOrCreate([
            'slug' => 'Web-Programming',
        ], [
            'name' => 'Web Programming',
            'color' => 'blue',
            'description' => 'Web Programming adalah proses pengembangan aplikasi web.',
        ]);

        Category::updateOrCreate([
            'slug' => 'Mobile-Programming',
        ], [
            'name' => 'Mobile Programming',
            'color' => 'green',
            'description' => 'Mobile Programming adalah proses pengembangan aplikasi mobile.',
        ]);
    }
}
