<?php

namespace App\Http\Requests\CustomerCalls;

use App\Models\CustomerCall;

class UpdateCustomerCallRequest extends CustomerCallRequest
{
    public function authorize(): bool
    {
        $customerCall = $this->route('customerCall');

        return $customerCall instanceof CustomerCall
            && ($this->user()?->can('update', $customerCall) ?? false);
    }
}
