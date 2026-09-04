<?php

namespace Tests\Feature\Reservations;

use App\Enums\ReservationStatus;
use App\Models\Client;
use App\Models\CustomerCall;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_reservations(): void
    {
        $this->get(route('reservations.index'))->assertRedirect(route('login'));
    }

    public function test_agent_can_create_and_view_a_reservation(): void
    {
        $agent = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($agent)->post(route('reservations.store'), [
            'client_id' => $client->id,
            'reference' => ' br-test-001 ',
            'vehicle' => ' Toyota RAV4 ',
            'start_date' => '2026-09-10',
            'end_date' => '2026-09-15',
            'status' => ReservationStatus::Confirmed->value,
        ]);

        $reservation = Reservation::query()->sole();

        $response->assertRedirect(route('reservations.show', $reservation));
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'reference' => 'BR-TEST-001',
            'vehicle' => 'Toyota RAV4',
            'client_id' => $client->id,
        ]);
        $this->actingAs($agent)
            ->get(route('reservations.show', $reservation))
            ->assertOk()
            ->assertSee('BR-TEST-001')
            ->assertSee($client->full_name);
    }

    public function test_agent_can_update_a_reservation(): void
    {
        $agent = User::factory()->create();
        $reservation = Reservation::factory()->create();

        $response = $this->actingAs($agent)->put(route('reservations.update', $reservation), [
            'client_id' => $reservation->client_id,
            'reference' => $reservation->reference,
            'vehicle' => 'Toyota Land Cruiser Prado',
            'start_date' => '2026-09-12',
            'end_date' => '2026-09-20',
            'status' => ReservationStatus::Ongoing->value,
        ]);

        $response->assertRedirect(route('reservations.show', $reservation));
        $reservation->refresh();
        $this->assertSame('Toyota Land Cruiser Prado', $reservation->vehicle);
        $this->assertSame(ReservationStatus::Ongoing, $reservation->status);
    }

    public function test_end_date_cannot_precede_start_date(): void
    {
        $agent = User::factory()->create();
        $client = Client::factory()->create();

        $this->actingAs($agent)->post(route('reservations.store'), [
            'client_id' => $client->id,
            'reference' => 'BR-TEST-002',
            'vehicle' => 'Toyota Corolla',
            'start_date' => '2026-09-20',
            'end_date' => '2026-09-10',
            'status' => ReservationStatus::Pending->value,
        ])->assertSessionHasErrors('end_date');
    }

    public function test_deleting_reservation_keeps_calls_and_removes_the_link(): void
    {
        $agent = User::factory()->create();
        $reservation = Reservation::factory()->create();
        $customerCall = CustomerCall::factory()
            ->linkedToReservation($reservation)
            ->create();

        $this->actingAs($agent)
            ->delete(route('reservations.destroy', $reservation))
            ->assertRedirect(route('reservations.index'));

        $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
        $this->assertDatabaseHas('customer_calls', [
            'id' => $customerCall->id,
            'reservation_id' => null,
        ]);
    }

    public function test_reservations_can_be_searched(): void
    {
        $agent = User::factory()->create();
        $matching = Reservation::factory()->create(['reference' => 'BR-CIBLE-01']);
        Reservation::factory()->create(['reference' => 'BR-AUTRE-02']);

        $this->actingAs($agent)
            ->get(route('reservations.index', ['search' => 'CIBLE']))
            ->assertOk()
            ->assertSee($matching->reference)
            ->assertDontSee('BR-AUTRE-02');
    }
}
