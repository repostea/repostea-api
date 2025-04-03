<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TenantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'name' => $this->faker->company(),
            'api_key' => Str::random(32),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
