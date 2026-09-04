<?php

namespace App\Actions\CustomerCalls;

use App\Models\CustomerCall;

class SyncCustomerCallTags
{
    /**
     * @param  list<int>  $tagIds
     */
    public function handle(CustomerCall $customerCall, array $tagIds): void
    {
        $customerCall->tags()->sync($tagIds);
    }
}
