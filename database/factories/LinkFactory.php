<?php

namespace Database\Factories;

use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    use TenantAwareFactory;

    protected $model = Link::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'url' => $this->faker->url(),
            'content' => $this->faker->optional(0.7)->paragraph(3),
            'description' => $this->faker->paragraph(2),
            'votes' => $this->faker->numberBetween(0, 50),
            'karma' => $this->faker->numberBetween(0, 100),
            'clicks' => $this->faker->numberBetween(0, 200),
            'nsfw' => $this->faker->boolean(10),
            'status' => $this->faker->randomElement(['pending', 'published', 'discard']),
            'user_id' => User::factory(),
            'promoted_at' => $this->faker->optional(0.6)->dateTimeThisMonth(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'promoted_at' => null,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'promoted_at' => $this->faker->dateTimeThisMonth(),
        ]);
    }

    public function discarded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'discard',
            'promoted_at' => null,
        ]);
    }
}
