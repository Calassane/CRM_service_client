<?php

namespace Tests\Feature\Policies;

use App\Models\CustomerCall;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class CustomerCallPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_agents_can_view_calls_and_create_them(): void
    {
        $agent = User::factory()->create();
        $customerCall = CustomerCall::factory()->create();

        $this->assertTrue(Gate::forUser($agent)->allows('viewAny', CustomerCall::class));
        $this->assertTrue(Gate::forUser($agent)->allows('view', $customerCall));
        $this->assertTrue(Gate::forUser($agent)->allows('create', CustomerCall::class));
    }

    public function test_only_the_owner_can_update_or_delete_a_call(): void
    {
        $owner = User::factory()->create();
        $otherAgent = User::factory()->create();
        $customerCall = CustomerCall::factory()->for($owner, 'agent')->create();

        $this->assertTrue(Gate::forUser($owner)->allows('update', $customerCall));
        $this->assertTrue(Gate::forUser($owner)->allows('delete', $customerCall));
        $this->assertFalse(Gate::forUser($otherAgent)->allows('update', $customerCall));
        $this->assertFalse(Gate::forUser($otherAgent)->allows('delete', $customerCall));
    }
}
