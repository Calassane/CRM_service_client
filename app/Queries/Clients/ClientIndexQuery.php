<?php

namespace App\Queries\Clients;

use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ClientIndexQuery
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Client>
     */
    public function handle(array $filters): LengthAwarePaginator
    {
        $terms = preg_split('/\s+/', trim((string) ($filters['search'] ?? '')), flags: PREG_SPLIT_NO_EMPTY) ?: [];

        return Client::query()
            ->withCount(['reservations', 'customerCalls'])
            ->when($terms, function (Builder $query) use ($terms): void {
                foreach ($terms as $term) {
                    $query->where(function (Builder $query) use ($term): void {
                        $query
                            ->where('first_name', 'like', "%{$term}%")
                            ->orWhere('last_name', 'like', "%{$term}%")
                            ->orWhere('email', 'like', "%{$term}%")
                            ->orWhere('phone', 'like', "%{$term}%");
                    });
                }
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(15)
            ->withQueryString();
    }
}
