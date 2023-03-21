<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id'   => \App\Models\Category::factory()->create()->id,
            'user_id'       => \App\Models\User::factory()->create()->id,
            'slug'          => $this->faker->slug,
            'title'         => $this->faker->text(191),
            'content'       => $this->faker->text(1024),
        ];
    }
}
