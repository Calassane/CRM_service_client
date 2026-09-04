<?php

namespace App\Http\Requests\CustomerCalls;

use App\Models\CustomerCall;

class StoreCustomerCallRequest extends CustomerCallRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', CustomerCall::class) ?? false;
    }
}
