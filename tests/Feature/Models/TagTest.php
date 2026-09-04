<?php

namespace Tests\Feature\Models;

use App\Models\CustomerCall;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function test_tag_can_be_shared_by_multiple_customer_calls(): void
    {
        $tag = Tag::factory()->create();
        $customerCalls = CustomerCall::factory()->count(2)->create();

        $tag->customerCalls()->attach($customerCalls);

        $this->assertCount(2, $tag->customerCalls);
        $this->assertTrue($customerCalls->every(
            fn (CustomerCall $customerCall): bool => $customerCall->tags()->whereKey($tag->getKey())->exists(),
        ));
    }
}
