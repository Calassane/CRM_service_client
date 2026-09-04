<?php

namespace Tests\Feature\Models;

use App\Enums\CallDirection;
use App\Enums\CallReason;
use App\Enums\CallStatus;
use App\Models\Client;
use App\Models\CustomerCall;
use App\Models\Reservation;
use App\Models\Tag;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCallTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_call_has_the_expected_relations(): void
    {
        $client = Client::factory()->create();
        $reservation = Reservation::factory()->for($client)->create();
        $agent = User::factory()->create();
        $tag = Tag::factory()->create();

        $customerCall = CustomerCall::factory()
            ->linkedToReservation($reservation)
            ->for($agent, 'agent')
            ->create();
        $customerCall->tags()->attach($tag);

        $this->assertTrue($customerCall->client->is($client));
        $this->assertTrue($customerCall->reservation->is($reservation));
        $this->assertTrue($customerCall->agent->is($agent));
        $this->assertTrue($customerCall->tags->contains($tag));
        $this->assertTrue($client->customerCalls()->whereKey($customerCall->getKey())->exists());
        $this->assertTrue($reservation->customerCalls()->whereKey($customerCall->getKey())->exists());
        $this->assertTrue($agent->handledCalls()->whereKey($customerCall->getKey())->exists());
    }

    public function test_customer_call_attributes_are_cast_to_domain_types(): void
    {
        $customerCall = CustomerCall::factory()->create([
            'direction' => CallDirection::Outbound,
            'reason' => CallReason::Payment,
            'status' => CallStatus::Pending,
            'duration_seconds' => '180',
        ]);

        $this->assertSame(CallDirection::Outbound, $customerCall->direction);
        $this->assertSame(CallReason::Payment, $customerCall->reason);
        $this->assertSame(CallStatus::Pending, $customerCall->status);
        $this->assertSame(180, $customerCall->duration_seconds);
        $this->assertInstanceOf(CarbonImmutable::class, $customerCall->started_at);
    }

    public function test_reservation_is_optional(): void
    {
        $customerCall = CustomerCall::factory()->create([
            'reservation_id' => null,
        ]);

        $this->assertNull($customerCall->reservation);
    }
}
