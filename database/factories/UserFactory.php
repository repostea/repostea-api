<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    use TenantAwareFactory;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password
            'remember_token' => Str::random(10),
            'karma' => 6.0,
            'level' => 0,
            'admin' => false,
            'moderator' => false,
            'verified' => false,
            'lang' => 'es',
            'last_active_at' => now(),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'admin' => true,
        ]);
    }

    public function moderator(): static
    {
        return $this->state(fn (array $attributes) => [
            'moderator' => true,
        ]);
    }

    public function withKarma(float $karma): static
    {
        return $this->state(fn (array $attributes) => [
            'karma' => $karma,
        ]);
    }
}
