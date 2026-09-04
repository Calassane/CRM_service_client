<?php

namespace Tests\Feature\Clients;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_clients_can_be_searched_with_multiple_terms(): void
    {
        $agent = User::factory()->create();
        Client::factory()->create([
            'first_name' => 'Awa',
            'last_name' => 'Koné',
            'email' => 'awa.kone@example.com',
        ]);
        Client::factory()->create([
            'first_name' => 'Awa',
            'last_name' => 'Yao',
            'email' => 'awa.yao@example.com',
        ]);
        Client::factory()->create([
            'first_name' => 'Mariam',
            'last_name' => 'Koné',
            'email' => 'mariam.kone@example.com',
        ]);

        $this->actingAs($agent)
            ->get(route('clients.index', ['search' => 'Awa Koné']))
            ->assertOk()
            ->assertSee('awa.kone@example.com')
            ->assertDontSee('awa.yao@example.com')
            ->assertDontSee('mariam.kone@example.com');
    }
}
