<?php

namespace Tests\Feature\Clients;

use App\Models\Client;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_clients(): void
    {
        $this->get(route('clients.index'))->assertRedirect(route('login'));
    }

    public function test_agent_can_create_and_view_a_client(): void
    {
        $agent = User::factory()->create();

        $response = $this->actingAs($agent)->post(route('clients.store'), [
            'first_name' => '  Awa ',
            'last_name' => ' Koné  ',
            'email' => ' AWA.KONE@EXAMPLE.COM ',
            'phone' => ' +225 07 01 02 03 04 ',
            'address' => 'Cocody Angré',
            'city' => ' Abidjan ',
        ]);

        $client = Client::query()->sole();

        $response->assertRedirect(route('clients.show', $client));
        $this->assertDatabaseHas('clients', [
            'first_name' => 'Awa',
            'last_name' => 'Koné',
            'email' => 'awa.kone@example.com',
            'phone' => '+225 07 01 02 03 04',
            'city' => 'Abidjan',
        ]);
        $this->actingAs($agent)
            ->get(route('clients.show', $client))
            ->assertOk()
            ->assertSee('Awa Koné');
    }

    public function test_agent_can_update_a_client_without_triggering_its_own_unique_values(): void
    {
        $agent = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($agent)->put(route('clients.update', $client), [
            'first_name' => 'Mariam',
            'last_name' => 'Traoré',
            'email' => $client->email,
            'phone' => $client->phone,
            'address' => 'Marcory Résidentiel',
            'city' => 'Abidjan',
        ]);

        $response->assertRedirect(route('clients.show', $client));
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'first_name' => 'Mariam',
            'last_name' => 'Traoré',
        ]);
    }

    public function test_agent_can_delete_a_client_without_history(): void
    {
        $agent = User::factory()->create();
        $client = Client::factory()->create();

        $this->actingAs($agent)
            ->delete(route('clients.destroy', $client))
            ->assertRedirect(route('clients.index'));

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }

    public function test_client_with_history_cannot_be_deleted(): void
    {
        $agent = User::factory()->create();
        $client = Client::factory()->create();
        Reservation::factory()->for($client)->create();

        $this->actingAs($agent)
            ->from(route('clients.show', $client))
            ->delete(route('clients.destroy', $client))
            ->assertRedirect(route('clients.show', $client))
            ->assertSessionHasErrors('client');

        $this->assertDatabaseHas('clients', ['id' => $client->id]);
    }

    public function test_email_and_phone_must_be_unique(): void
    {
        $agent = User::factory()->create();
        $existingClient = Client::factory()->create();

        $this->actingAs($agent)->post(route('clients.store'), [
            'first_name' => 'Autre',
            'last_name' => 'Client',
            'email' => $existingClient->email,
            'phone' => $existingClient->phone,
            'city' => 'Abidjan',
        ])->assertSessionHasErrors(['email', 'phone']);
    }
}
