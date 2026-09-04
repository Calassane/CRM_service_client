<?php

namespace App\Http\Controllers;

use App\Actions\CustomerCalls\SyncCustomerCallTags;
use App\Enums\CallDirection;
use App\Enums\CallReason;
use App\Enums\CallStatus;
use App\Http\Requests\CustomerCalls\IndexCustomerCallRequest;
use App\Http\Requests\CustomerCalls\StoreCustomerCallRequest;
use App\Http\Requests\CustomerCalls\UpdateCustomerCallRequest;
use App\Models\Client;
use App\Models\CustomerCall;
use App\Models\Reservation;
use App\Models\Tag;
use App\Models\User;
use App\Queries\CustomerCalls\CustomerCallIndexQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CustomerCallController extends Controller
{
    public function __construct(
        private readonly CustomerCallIndexQuery $indexQuery,
        private readonly SyncCustomerCallTags $syncCustomerCallTags,
    ) {}

    public function index(IndexCustomerCallRequest $request): View
    {
        return view('customer-calls.index', [
            'customerCalls' => $this->indexQuery->handle($request->validated()),
            'filters' => $request->validated(),
            'agents' => User::query()->orderBy('name')->get(['id', 'name']),
            'statusOptions' => $this->statusOptions(),
            'reasonOptions' => $this->reasonOptions(),
        ]);
    }

    public function create(Request $request): View
    {
        Gate::authorize('create', CustomerCall::class);

        return view('customer-calls.create', $this->formData(
            selectedClientId: $this->existingClientId($request),
        ));
    }

    public function store(StoreCustomerCallRequest $request): RedirectResponse
    {
        $customerCall = DB::transaction(function () use ($request): CustomerCall {
            $customerCall = CustomerCall::query()->create([
                ...$request->callAttributes(),
                'agent_id' => $request->user()->getAuthIdentifier(),
            ]);

            $this->syncCustomerCallTags->handle($customerCall, $request->tagIds());

            return $customerCall;
        });

        return to_route('customer-calls.show', $customerCall)
            ->with('success', 'L’appel a été enregistré.');
    }

    public function show(CustomerCall $customerCall): View
    {
        Gate::authorize('view', $customerCall);

        return view('customer-calls.show', [
            'customerCall' => $customerCall->load(['client', 'reservation', 'agent', 'tags']),
        ]);
    }

    public function edit(CustomerCall $customerCall): View
    {
        Gate::authorize('update', $customerCall);

        return view('customer-calls.edit', $this->formData($customerCall->load('tags')));
    }

    public function update(
        UpdateCustomerCallRequest $request,
        CustomerCall $customerCall,
    ): RedirectResponse {
        DB::transaction(function () use ($request, $customerCall): void {
            $customerCall->update($request->callAttributes());
            $this->syncCustomerCallTags->handle($customerCall, $request->tagIds());
        });

        return to_route('customer-calls.show', $customerCall)
            ->with('success', 'L’appel a été mis à jour.');
    }

    public function destroy(CustomerCall $customerCall): RedirectResponse
    {
        Gate::authorize('delete', $customerCall);

        $customerCall->delete();

        return to_route('customer-calls.index')
            ->with('success', 'L’appel a été supprimé.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(
        ?CustomerCall $customerCall = null,
        ?int $selectedClientId = null,
    ): array {
        return [
            'customerCall' => $customerCall,
            'selectedClientId' => $selectedClientId,
            'selectedTagIds' => $customerCall?->tags->modelKeys() ?? [],
            'clients' => Client::query()->orderBy('last_name')->orderBy('first_name')->get(),
            'reservations' => Reservation::query()
                ->with('client')
                ->latest('start_date')
                ->get(),
            'tags' => Tag::query()->orderBy('name')->get(),
            'directionOptions' => array_map(
                fn (CallDirection $direction): array => [
                    'value' => $direction->value,
                    'label' => $direction->label(),
                ],
                CallDirection::cases(),
            ),
            'reasonOptions' => $this->reasonOptions(),
            'statusOptions' => $this->statusOptions(),
            'defaultStartedAt' => now()->format('Y-m-d\TH:i'),
            'defaultDirection' => CallDirection::Inbound->value,
            'defaultReason' => CallReason::Reservation->value,
            'defaultStatus' => CallStatus::Pending->value,
        ];
    }

    /**
     * @return list<array{value: string, label: string}>
     */
    private function reasonOptions(): array
    {
        return array_map(
            fn (CallReason $reason): array => [
                'value' => $reason->value,
                'label' => $reason->label(),
            ],
            CallReason::cases(),
        );
    }

    /**
     * @return list<array{value: string, label: string}>
     */
    private function statusOptions(): array
    {
        return array_map(
            fn (CallStatus $status): array => [
                'value' => $status->value,
                'label' => $status->label(),
            ],
            CallStatus::cases(),
        );
    }

    private function existingClientId(Request $request): ?int
    {
        return Client::query()
            ->whereKey($request->integer('client_id'))
            ->value('id');
    }
}
