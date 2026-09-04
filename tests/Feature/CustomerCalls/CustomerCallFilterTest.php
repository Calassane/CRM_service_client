<?php

namespace Tests\Feature\CustomerCalls;

use App\Enums\CallReason;
use App\Enums\CallStatus;
use App\Models\Client;
use App\Models\CustomerCall;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCallFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_calls_can_be_filtered_by_agent_status_reason_and_period(): void
    {
        $selectedAgent = User::factory()->create();
        $otherAgent = User::factory()->create();
        $matchingClient = Client::factory()->create(['first_name' => 'Client', 'last_name' => 'Cible']);
        $wrongAgentClient = Client::factory()->create(['first_name' => 'Mauvais', 'last_name' => 'Agent']);
        $wrongStatusClient = Client::factory()->create(['first_name' => 'Mauvais', 'last_name' => 'Statut']);
        $wrongReasonClient = Client::factory()->create(['first_name' => 'Mauvais', 'last_name' => 'Motif']);
        $wrongDateClient = Client::factory()->create(['first_name' => 'Mauvaise', 'last_name' => 'Date']);

        $commonAttributes = [
            'status' => CallStatus::Pending,
            'reason' => CallReason::Payment,
            'started_at' => '2026-08-15 10:00:00',
        ];

        CustomerCall::factory()->for($matchingClient)->for($selectedAgent, 'agent')->create($commonAttributes);
        CustomerCall::factory()->for($wrongAgentClient)->for($otherAgent, 'agent')->create($commonAttributes);
        CustomerCall::factory()->for($wrongStatusClient)->for($selectedAgent, 'agent')->create([
            ...$commonAttributes,
            'status' => CallStatus::Resolved,
        ]);
        CustomerCall::factory()->for($wrongReasonClient)->for($selectedAgent, 'agent')->create([
            ...$commonAttributes,
            'reason' => CallReason::Complaint,
        ]);
        CustomerCall::factory()->for($wrongDateClient)->for($selectedAgent, 'agent')->create([
            ...$commonAttributes,
            'started_at' => '2026-07-10 10:00:00',
        ]);

        $response = $this->actingAs($selectedAgent)->get(route('customer-calls.index', [
            'agent_id' => $selectedAgent->id,
            'status' => CallStatus::Pending->value,
            'reason' => CallReason::Payment->value,
            'date_from' => '2026-08-01',
            'date_to' => '2026-08-31',
        ]));

        $response->assertOk()
            ->assertSee('Client Cible')
            ->assertDontSee('Mauvais Agent')
            ->assertDontSee('Mauvais Statut')
            ->assertDontSee('Mauvais Motif')
            ->assertDontSee('Mauvaise Date');
    }
}
