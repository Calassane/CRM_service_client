<?php

namespace App\Http\Controllers;

use App\Actions\Clients\DeleteClient;
use App\Http\Requests\Clients\IndexClientRequest;
use App\Http\Requests\Clients\StoreClientRequest;
use App\Http\Requests\Clients\UpdateClientRequest;
use App\Models\Client;
use App\Queries\Clients\ClientIndexQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function __construct(
        private readonly ClientIndexQuery $indexQuery,
        private readonly DeleteClient $deleteClient,
    ) {}

    public function index(IndexClientRequest $request): View
    {
        return view('clients.index', [
            'clients' => $this->indexQuery->handle($request->validated()),
            'filters' => $request->validated(),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Client::class);

        return view('clients.create', ['client' => null]);
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $client = Client::query()->create($request->validated());

        return to_route('clients.show', $client)
            ->with('success', 'Le client a été ajouté.');
    }

    public function show(Client $client): View
    {
        Gate::authorize('view', $client);

        $client->load([
            'reservations' => fn ($query) => $query->latest('start_date'),
            'customerCalls' => fn ($query) => $query->with('agent')->latest('started_at'),
        ]);

        return view('clients.show', ['client' => $client]);
    }

    public function edit(Client $client): View
    {
        Gate::authorize('update', $client);

        return view('clients.edit', ['client' => $client]);
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->validated());

        return to_route('clients.show', $client)
            ->with('success', 'Le client a été mis à jour.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        Gate::authorize('delete', $client);
        $this->deleteClient->handle($client);

        return to_route('clients.index')
            ->with('success', 'Le client a été supprimé.');
    }
}
