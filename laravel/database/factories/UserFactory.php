<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password_hash' => Hash::make('password'), // default password
            'phone_number' => $this->faker->phoneNumber(),
            'role' => $this->faker->randomElement(['user', 'admin', 'manager']),
            'two_factor_secret' => null,
            'is_verified' => $this->faker->boolean(80),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
