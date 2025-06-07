<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Category;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoryIds = \App\Models\Category::pluck('id')->toArray();
        $userIds = \App\Models\User::pluck('id')->toArray();
        return [
            'name' => $this->faker->sentence(3, false),
            'slug' => $this->faker->unique()->slug(),
            'user_id' => $userIds ? $this->faker->randomElement($userIds) : \App\Models\User::factory(),
            'category_id' => $categoryIds ? $this->faker->randomElement($categoryIds) : \App\Models\Category::factory(),
            'body' => $this->faker->text(),
        ];
    }
}
