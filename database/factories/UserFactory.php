<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'activated' => true,
            'password' => '$2y$10$jOpAfauzI2Y/LGvFYczNBO8/CEnAmV.bW0Bo.SsMcY6vYn.N4vQ8W', // password
            'remember_token' => Str::random(10),
        ];
    }
}
