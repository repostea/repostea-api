<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    use TenantAwareFactory;

    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraph(2),
            'votes' => $this->faker->numberBetween(0, 20),
            'karma' => $this->faker->numberBetween(0, 30),
            'user_id' => User::factory(),
            'link_id' => Link::factory(),
            'parent_id' => null,
        ];
    }

    public function reply(): static
    {
        return $this->state(function (array $attributes) {
            if (! isset($attributes['parent_id'])) {
                $parent = Comment::factory()->create([
                    'link_id' => $attributes['link_id'] ?? Link::factory(),
                    'tenant_id' => $attributes['tenant_id'] ?? null,
                ]);

                return [
                    'parent_id' => $parent->id,
                    'link_id' => $parent->link_id,
                ];
            }

            return [];
        });
    }
}
