<?php

namespace Tests\Feature\Models;

use App\Enums\ReservationStatus;
use App\Models\Client;
use App\Models\Reservation;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_belongs_to_a_client(): void
    {
        $client = Client::factory()->create();
        $reservation = Reservation::factory()->for($client)->create();

        $this->assertTrue($reservation->client->is($client));
    }

    public function test_reservation_attributes_are_cast_to_domain_types(): void
    {
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Confirmed,
        ]);

        $this->assertSame(ReservationStatus::Confirmed, $reservation->status);
        $this->assertInstanceOf(CarbonImmutable::class, $reservation->start_date);
        $this->assertInstanceOf(CarbonImmutable::class, $reservation->end_date);
        $this->assertTrue($reservation->end_date->isAfter($reservation->start_date));
    }
}
