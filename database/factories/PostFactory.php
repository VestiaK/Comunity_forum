<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Post>
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
        $categoryIds = Category::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();
        return [
            'name' => $this->faker->sentence(3, false),
            'slug' => $this->faker->unique()->slug(),
            'user_id' => $userIds ? $this->faker->randomElement($userIds) : User::factory(),
            'category_id' => $categoryIds ? $this->faker->randomElement($categoryIds) : Category::factory(),
            'body' => $this->faker->text(),
        ];
    }
}
