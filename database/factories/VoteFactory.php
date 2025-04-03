<?php

namespace Database\Factories;

use App\Models\Link;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoteFactory extends Factory
{
    use TenantAwareFactory;

    protected $model = Vote::class;

    public function definition(): array
    {
        $initialKarma = config('app.repostea.initial_karma');
        $user = User::factory()->create([
            'karma' => $this->faker->randomFloat(1, $initialKarma, $initialKarma + 10),
        ]);

        $value = $this->faker->randomElement([1, -1]);

        return [
            'user_id' => $user->id,
            'link_id' => Link::factory(),
            'value' => $value,
            'karma_value' => $value * ($user->karma / $initialKarma),
        ];
    }

    public function positive(): static
    {
        $initialKarma = config('app.repostea.initial_karma');

        return $this->state(function (array $attributes) use ($initialKarma) {
            $user = isset($attributes['user_id'])
                ? User::find($attributes['user_id'])
                : User::factory()->create([
                    'karma' => $initialKarma,
                    'tenant_id' => $attributes['tenant_id'] ?? null,
                ]);

            return [
                'value' => 1,
                'karma_value' => $user->karma / $initialKarma,
                'user_id' => $user->id,
            ];
        });
    }

    public function negative(): static
    {
        $initialKarma = config('app.repostea.initial_karma');
        $minKarmaNegative = config('app.repostea.min_karma_for_negative_vote');

        return $this->state(function (array $attributes) use ($initialKarma, $minKarmaNegative) {
            $user = isset($attributes['user_id'])
                ? User::find($attributes['user_id'])
                : User::factory()->create([
                    'karma' => $this->faker->randomFloat(1, $minKarmaNegative, $minKarmaNegative + 5),
                    'tenant_id' => $attributes['tenant_id'] ?? null,
                ]);

            return [
                'value' => -1,
                'karma_value' => -1 * ($user->karma / $initialKarma),
                'user_id' => $user->id,
            ];
        });
    }
}
