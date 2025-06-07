<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\post;
use App\Models\Comment;
use Database\Seeders\CommentSeeder;
use Database\Seeders\CategorySeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

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
        post::factory(100)->recycle([
            
            User::factory(5)->create(),
            Comment::factory(10)->create(),
        ])->create();
    }
}
