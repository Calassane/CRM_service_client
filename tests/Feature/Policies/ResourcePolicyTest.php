<?php

namespace Tests\Feature\Policies;

use App\Models\Client;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class ResourcePolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_agents_can_manage_clients_and_reservations(): void
    {
        $agent = User::factory()->create();
        $client = Client::factory()->create();
        $reservation = Reservation::factory()->create();

        foreach (['view', 'update', 'delete'] as $ability) {
            $this->assertTrue(Gate::forUser($agent)->allows($ability, $client));
            $this->assertTrue(Gate::forUser($agent)->allows($ability, $reservation));
        }

        $this->assertTrue(Gate::forUser($agent)->allows('create', Client::class));
        $this->assertTrue(Gate::forUser($agent)->allows('create', Reservation::class));
    }
}
