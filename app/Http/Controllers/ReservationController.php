<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatus;
use App\Http\Requests\Reservations\IndexReservationRequest;
use App\Http\Requests\Reservations\StoreReservationRequest;
use App\Http\Requests\Reservations\UpdateReservationRequest;
use App\Models\Client;
use App\Models\Reservation;
use App\Queries\Reservations\ReservationIndexQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function __construct(
        private readonly ReservationIndexQuery $indexQuery,
    ) {}

    public function index(IndexReservationRequest $request): View
    {
        return view('reservations.index', [
            'reservations' => $this->indexQuery->handle($request->validated()),
            'filters' => $request->validated(),
        ]);
    }

    public function create(Request $request): View
    {
        Gate::authorize('create', Reservation::class);

        return view('reservations.create', $this->formData(
            selectedClientId: $this->existingClientId($request),
        ));
    }

    public function store(StoreReservationRequest $request): RedirectResponse
    {
        $reservation = Reservation::query()->create($request->validated());

        return to_route('reservations.show', $reservation)
            ->with('success', 'La réservation a été créée.');
    }

    public function show(Reservation $reservation): View
    {
        Gate::authorize('view', $reservation);

        return view('reservations.show', [
            'reservation' => $reservation->load([
                'client',
                'customerCalls' => fn ($query) => $query->with('agent')->latest('started_at'),
            ]),
        ]);
    }

    public function edit(Reservation $reservation): View
    {
        Gate::authorize('update', $reservation);

        return view('reservations.edit', $this->formData($reservation));
    }

    public function update(
        UpdateReservationRequest $request,
        Reservation $reservation,
    ): RedirectResponse {
        $reservation->update($request->validated());

        return to_route('reservations.show', $reservation)
            ->with('success', 'La réservation a été mise à jour.');
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        Gate::authorize('delete', $reservation);
        $reservation->delete();

        return to_route('reservations.index')
            ->with('success', 'La réservation a été supprimée.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(
        ?Reservation $reservation = null,
        ?int $selectedClientId = null,
    ): array {
        return [
            'reservation' => $reservation,
            'selectedClientId' => $selectedClientId,
            'clients' => Client::query()->orderBy('last_name')->orderBy('first_name')->get(),
            'statusOptions' => array_map(
                fn (ReservationStatus $status): array => [
                    'value' => $status->value,
                    'label' => $status->label(),
                ],
                ReservationStatus::cases(),
            ),
            'defaultStatus' => ReservationStatus::Pending->value,
        ];
    }

    private function existingClientId(Request $request): ?int
    {
        return Client::query()
            ->whereKey($request->integer('client_id'))
            ->value('id');
    }
}
