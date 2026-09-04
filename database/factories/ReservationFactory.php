<?php

namespace Database\Factories;

use App\Enums\ReservationStatus;
use App\Models\Client;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-3 months', '+2 months');
        $endDate = (clone $startDate)->modify(sprintf('+%d days', fake()->numberBetween(2, 14)));

        return [
            'client_id' => Client::factory(),
            'reference' => 'BR-'.fake()->unique()->numerify('######'),
            'vehicle' => fake()->randomElement([
                'Hyundai Tucson',
                'Kia Sportage',
                'Mitsubishi Pajero',
                'Toyota Corolla',
                'Toyota Land Cruiser Prado',
                'Toyota RAV4',
                'Toyota Hiace',
            ]),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'status' => fake()->randomElement(ReservationStatus::cases()),
        ];
    }
}
