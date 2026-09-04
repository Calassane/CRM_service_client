<?php

namespace App\Queries\CustomerCalls;

use App\Models\CustomerCall;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerCallIndexQuery
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, CustomerCall>
     */
    public function handle(array $filters): LengthAwarePaginator
    {
        return CustomerCall::query()
            ->with(['client', 'reservation', 'agent', 'tags'])
            ->when(
                $filters['agent_id'] ?? null,
                fn ($query, $agentId) => $query->where('agent_id', $agentId),
            )
            ->when(
                $filters['status'] ?? null,
                fn ($query, $status) => $query->where('status', $status),
            )
            ->when(
                $filters['reason'] ?? null,
                fn ($query, $reason) => $query->where('reason', $reason),
            )
            ->when(
                $filters['date_from'] ?? null,
                fn ($query, $dateFrom) => $query->whereDate('started_at', '>=', $dateFrom),
            )
            ->when(
                $filters['date_to'] ?? null,
                fn ($query, $dateTo) => $query->whereDate('started_at', '<=', $dateTo),
            )
            ->latest('started_at')
            ->paginate(15)
            ->withQueryString();
    }
}
