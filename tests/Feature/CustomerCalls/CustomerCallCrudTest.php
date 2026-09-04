<?php

namespace Tests\Feature\CustomerCalls;

use App\Enums\CallDirection;
use App\Enums\CallReason;
use App\Enums\CallStatus;
use App\Models\Client;
use App\Models\CustomerCall;
use App\Models\Reservation;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCallCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_customer_calls(): void
    {
        $this->get(route('customer-calls.index'))
            ->assertRedirect(route('login'));
    }

    public function test_agent_can_open_the_create_form(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('customer-calls.create'))
            ->assertOk()
            ->assertSee('Enregistrer un appel');
    }

    public function test_agent_can_create_a_customer_call_with_tags(): void
    {
        $agent = User::factory()->create();
        $client = Client::factory()->create();
        $reservation = Reservation::factory()->for($client)->create();
        $tags = Tag::factory()->count(2)->create();

        $response = $this->actingAs($agent)->post(route('customer-calls.store'), [
            ...$this->validPayload($client, $reservation),
            'tag_ids' => $tags->modelKeys(),
        ]);

        $customerCall = CustomerCall::query()->sole();

        $response->assertRedirect(route('customer-calls.show', $customerCall));
        $this->assertSame($agent->id, $customerCall->agent_id);
        $this->assertSame($client->id, $customerCall->client_id);
        $this->assertSame($reservation->id, $customerCall->reservation_id);
        $this->assertEqualsCanonicalizing($tags->modelKeys(), $customerCall->tags()->allRelatedIds()->all());
    }

    public function test_reservation_must_belong_to_the_selected_client(): void
    {
        $agent = User::factory()->create();
        $selectedClient = Client::factory()->create();
        $otherReservation = Reservation::factory()->create();

        $this->actingAs($agent)
            ->post(route('customer-calls.store'), $this->validPayload($selectedClient, $otherReservation))
            ->assertSessionHasErrors('reservation_id');

        $this->assertDatabaseCount('customer_calls', 0);
    }

    public function test_owner_can_update_call_and_replace_tags(): void
    {
        $agent = User::factory()->create();
        $client = Client::factory()->create();
        $customerCall = CustomerCall::factory()->for($client)->for($agent, 'agent')->create();
        $oldTag = Tag::factory()->create();
        $newTags = Tag::factory()->count(2)->create();
        $customerCall->tags()->attach($oldTag);

        $response = $this->actingAs($agent)->put(
            route('customer-calls.update', $customerCall),
            [
                ...$this->validPayload($client),
                'status' => CallStatus::Resolved->value,
                'notes' => 'Le dossier est maintenant résolu.',
                'tag_ids' => $newTags->modelKeys(),
            ],
        );

        $response->assertRedirect(route('customer-calls.show', $customerCall));
        $customerCall->refresh();
        $this->assertSame(CallStatus::Resolved, $customerCall->status);
        $this->assertSame('Le dossier est maintenant résolu.', $customerCall->notes);
        $this->assertEqualsCanonicalizing($newTags->modelKeys(), $customerCall->tags()->allRelatedIds()->all());
        $this->assertFalse($customerCall->tags()->whereKey($oldTag->id)->exists());
    }

    public function test_another_agent_cannot_update_or_delete_the_call(): void
    {
        $owner = User::factory()->create();
        $otherAgent = User::factory()->create();
        $customerCall = CustomerCall::factory()->for($owner, 'agent')->create();

        $this->actingAs($otherAgent)
            ->put(route('customer-calls.update', $customerCall), $this->validPayload($customerCall->client))
            ->assertForbidden();

        $this->actingAs($otherAgent)
            ->delete(route('customer-calls.destroy', $customerCall))
            ->assertForbidden();

        $this->assertDatabaseHas('customer_calls', ['id' => $customerCall->id]);
    }

    public function test_owner_can_delete_the_call_and_its_tag_links(): void
    {
        $agent = User::factory()->create();
        $customerCall = CustomerCall::factory()->for($agent, 'agent')->create();
        $tag = Tag::factory()->create();
        $customerCall->tags()->attach($tag);

        $this->actingAs($agent)
            ->delete(route('customer-calls.destroy', $customerCall))
            ->assertRedirect(route('customer-calls.index'));

        $this->assertDatabaseMissing('customer_calls', ['id' => $customerCall->id]);
        $this->assertDatabaseMissing('customer_call_tag', ['customer_call_id' => $customerCall->id]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validPayload(Client $client, ?Reservation $reservation = null): array
    {
        return [
            'client_id' => $client->id,
            'reservation_id' => $reservation?->id,
            'direction' => CallDirection::Inbound->value,
            'reason' => CallReason::Reservation->value,
            'started_at' => now()->subHour()->format('Y-m-d H:i:s'),
            'duration_seconds' => 240,
            'status' => CallStatus::Pending->value,
            'notes' => 'Le client souhaite confirmer sa réservation.',
            'tag_ids' => [],
        ];
    }
}
