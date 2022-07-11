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
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('1234567'), // password
            'remember_token' => Str::random(10),
        ];
        /*return [
            'full_name' => 'Keyvan Rasouli',
            'email' => 'k1.rasouli@gmail.com',
            'password' => Hash::make('1234567'), // password
            'remember_token' => Str::random(10),
        ];*/
    }
}
