<?php

namespace App\Queries\Reservations;

use App\Models\Reservation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ReservationIndexQuery
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Reservation>
     */
    public function handle(array $filters): LengthAwarePaginator
    {
        $search = trim((string) ($filters['search'] ?? ''));

        return Reservation::query()
            ->with('client')
            ->withCount('customerCalls')
            ->when($search, function (Builder $query, string $search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query
                        ->where('reference', 'like', "%{$search}%")
                        ->orWhere('vehicle', 'like', "%{$search}%")
                        ->orWhereHas('client', function (Builder $query) use ($search): void {
                            $query
                                ->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('start_date')
            ->paginate(15)
            ->withQueryString();
    }
}
