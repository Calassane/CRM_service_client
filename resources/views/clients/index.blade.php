<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="eyebrow">Relation client</p>
                <h1 class="page-title">Clients</h1>
                <p class="page-subtitle">Centralisez les coordonnées et l’historique de chaque client.</p>
            </div>
            <a href="{{ route('clients.create') }}" class="btn-primary self-start sm:self-auto">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg>
                Ajouter un client
            </a>
        </div>
    </x-slot>

    <div class="page-container">
        <div class="space-y-6">
            @if (session('success'))
                <div class="alert-success">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="m5 12 4 4L19 6"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @error('client')
                <div class="alert-danger">{{ $message }}</div>
            @enderror

            <form method="GET" action="{{ route('clients.index') }}" class="panel flex flex-col gap-3 p-4 sm:flex-row">
                <div class="relative flex-1">
                    <label for="search" class="sr-only">Rechercher un client</label>
                    <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="m16 16 4 4"/></svg>
                    <input id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nom, prénom, e-mail ou téléphone…" class="form-control pl-10">
                </div>
                <button type="submit" class="btn-primary">Rechercher</button>
                <a href="{{ route('clients.index') }}" class="btn-secondary">Réinitialiser</a>
            </form>

            <div class="table-shell">
                <div class="panel-header">
                    <div>
                        <h2 class="panel-title">Répertoire clients</h2>
                        <p class="panel-description">{{ $clients->total() }} client(s) trouvé(s)</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Contact</th>
                                <th>Ville</th>
                                <th>Activité</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($clients as $client)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-xs font-extrabold text-brand-700">{{ mb_strtoupper(mb_substr($client->first_name, 0, 1).mb_substr($client->last_name, 0, 1)) }}</div>
                                            <div>
                                                <p class="font-bold text-slate-900">{{ $client->full_name }}</p>
                                                <p class="mt-0.5 text-xs text-slate-400">Client #{{ $client->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="font-semibold text-slate-700">{{ $client->phone }}</p>
                                        <p class="mt-0.5 text-xs text-slate-400">{{ $client->email ?: 'Aucun e-mail' }}</p>
                                    </td>
                                    <td><span class="status-badge">{{ $client->city }}</span></td>
                                    <td>
                                        <p class="font-semibold text-slate-700">{{ $client->reservations_count }} réservation(s)</p>
                                        <p class="mt-0.5 text-xs text-slate-400">{{ $client->customer_calls_count }} appel(s)</p>
                                    </td>
                                    <td class="whitespace-nowrap text-right">
                                        <a href="{{ route('clients.show', $client) }}" class="btn-ghost text-brand-600">Voir</a>
                                        <a href="{{ route('clients.edit', $client) }}" class="btn-ghost">Modifier</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="10" cy="8" r="4" stroke-width="1.8"/><path stroke-linecap="round" stroke-width="1.8" d="M3 21a7 7 0 0 1 14 0m1-8 3 3m0-3-3 3"/></svg></div>
                                            <p class="mt-3 font-bold text-slate-700">Aucun client trouvé.</p>
                                            <p class="mt-1 text-sm text-slate-400">Modifiez votre recherche ou ajoutez un client.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($clients->hasPages())
                    <div class="border-t border-slate-100 px-5 py-4">{{ $clients->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
