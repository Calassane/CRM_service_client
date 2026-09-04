<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->numerify('+225 0# ## ## ## ##'),
            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement([
                'Abidjan',
                'Anyama',
                'Bingerville',
                'Grand-Bassam',
            ]),
        ];
    }
}
