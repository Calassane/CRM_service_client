<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="eyebrow">Gestion des locations</p>
                <h1 class="page-title">Réservations</h1>
                <p class="page-subtitle">Suivez les véhicules réservés et leurs périodes de location.</p>
            </div>
            <a href="{{ route('reservations.create') }}" class="btn-primary self-start sm:self-auto">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg>
                Créer une réservation
            </a>
        </div>
    </x-slot>

    <div class="page-container space-y-6">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('reservations.index') }}" class="panel flex flex-col gap-3 p-4 sm:flex-row">
            <div class="relative flex-1">
                <label for="search" class="sr-only">Rechercher une réservation</label>
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="m16 16 4 4"/></svg>
                <input id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" placeholder="Référence, véhicule, client ou téléphone…" class="form-control pl-10">
            </div>
            <button type="submit" class="btn-primary">Rechercher</button>
            <a href="{{ route('reservations.index') }}" class="btn-secondary">Réinitialiser</a>
        </form>

        <div class="table-shell">
            <div class="panel-header">
                <div>
                    <h2 class="panel-title">Toutes les réservations</h2>
                    <p class="panel-description">{{ $reservations->total() }} réservation(s) trouvée(s)</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Client</th>
                            <th>Véhicule</th>
                            <th>Période</th>
                            <th>Statut</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservations as $reservation)
                            <tr>
                                <td><span class="font-extrabold text-brand-700">{{ $reservation->reference }}</span></td>
                                <td>
                                    <p class="font-bold text-slate-900">{{ $reservation->client->full_name }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">{{ $reservation->client->phone }}</p>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2 font-semibold text-slate-700">
                                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m4 15 2-5h12l2 5m-15 0h14v5H5v-5Zm2 0v2m10-2v2"/></svg></span>
                                        {{ $reservation->vehicle }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap">
                                    <p class="font-semibold text-slate-700">{{ $reservation->start_date->format('d/m/Y') }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">au {{ $reservation->end_date->format('d/m/Y') }}</p>
                                </td>
                                <td><span class="status-badge bg-brand-50 text-brand-700"><span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>{{ $reservation->status->label() }}</span></td>
                                <td class="whitespace-nowrap text-right">
                                    <a href="{{ route('reservations.show', $reservation) }}" class="btn-ghost text-brand-600">Voir</a>
                                    <a href="{{ route('reservations.edit', $reservation) }}" class="btn-ghost">Modifier</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6"><div class="empty-state"><p class="font-bold text-slate-700">Aucune réservation trouvée.</p><p class="mt-1 text-sm text-slate-400">Essayez une autre recherche.</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($reservations->hasPages())
                <div class="border-t border-slate-100 px-5 py-4">{{ $reservations->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
