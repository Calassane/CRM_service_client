<?php

namespace Tests\Feature\Models;

use App\Models\Client;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_name_is_exposed_as_a_model_attribute(): void
    {
        $client = Client::factory()->make([
            'first_name' => 'Awa',
            'last_name' => 'Koné',
        ]);

        $this->assertSame('Awa Koné', $client->full_name);
    }

    public function test_client_has_many_reservations(): void
    {
        $client = Client::factory()->create();
        $reservations = Reservation::factory()->count(2)->for($client)->create();

        $this->assertCount(2, $client->reservations);
        $this->assertTrue($client->reservations->contains($reservations->first()));
    }
}
