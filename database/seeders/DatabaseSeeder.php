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
        Category::create([
            'name' => 'Artificial Intelligence & Machine Learning',
            'slug' => 'Artificial-Intelligence-Machine-Learning',
            'color' => 'pink',
            'description' => 'Mobile Programming adalah proses pengembangan aplikasi mobile.',
        ]);
        Category::create([
            'name' => 'Data Science & Big Data',
            'slug' => 'Data-Science-Big-Data',
            'color' => 'purple',
            'description' => 'Mobile Programming adalah proses pengembangan aplikasi mobile.',
        ]);
        Category::create([
            'name' => 'Database & Cloud Computing',
            'slug' => 'Database-Cloud-Computing',
            'color' => 'gray',
            'description' => 'Mobile Programming adalah proses pengembangan aplikasi mobile.',
        ]);
        Category::create([
            'name' => 'Cybersecurity & Ethical Hacking',
            'slug' => 'Cybersecurity-Ethical-Hacking',
            'color' => 'orange',
            'description' => 'Mobile Programming adalah proses pengembangan aplikasi mobile.',
        ]);
        
        post::factory(100)->recycle([
            
            User::factory(5)->create(),
            Comment::factory(10)->create(),
        ])->create();

        // Buat user moderator
        $moderator = User::factory()->create([
            'name' => 'Moderator',
            'username' => 'moderator',
            'email' => 'leszi102@gmail.com',
            'role' => 'moderator',
            'password' => bcrypt('password'),
        ]);


        $this->call([
            CategorySeeder::class,
            AdminSeeder::class, // tambahkan ini
        ]);
    }
}
