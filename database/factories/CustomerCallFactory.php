<?php

namespace Database\Factories;

use App\Enums\CallDirection;
use App\Enums\CallReason;
use App\Enums\CallStatus;
use App\Models\Client;
use App\Models\CustomerCall;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerCall>
 */
class CustomerCallFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'reservation_id' => null,
            'agent_id' => User::factory(),
            'direction' => fake()->randomElement([
                CallDirection::Inbound,
                CallDirection::Inbound,
                CallDirection::Outbound,
            ]),
            'reason' => fake()->randomElement([
                CallReason::Reservation,
                CallReason::Reservation,
                CallReason::Complaint,
                CallReason::TechnicalSupport,
                CallReason::Payment,
                CallReason::Other,
            ]),
            'started_at' => fake()->dateTimeBetween('-8 weeks', 'now'),
            'duration_seconds' => fake()->numberBetween(45, 1200),
            'status' => fake()->randomElement([
                CallStatus::Resolved,
                CallStatus::Resolved,
                CallStatus::Resolved,
                CallStatus::Pending,
                CallStatus::Escalated,
            ]),
            'notes' => fake()->randomElement([
                'Le client demande une confirmation de disponibilité du véhicule.',
                'Le client souhaite modifier les horaires de prise en charge.',
                'Le paiement mobile money doit être vérifié par le service financier.',
                'Une relance est prévue avec le client.',
                'La demande a été traitée pendant l’appel.',
                null,
            ]),
        ];
    }

    public function linkedToReservation(Reservation $reservation): static
    {
        return $this->state(fn (): array => [
            'client_id' => $reservation->client_id,
            'reservation_id' => $reservation->id,
        ]);
    }
}
