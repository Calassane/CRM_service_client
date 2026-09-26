<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div><h2 class="page-title">{{ $reservation->reference }}</h2><p class="page-subtitle">Détail de la réservation</p></div>
            <div class="flex gap-3"><a href="{{ route('reservations.index') }}" class="btn-secondary">Retour</a><a href="{{ route('reservations.edit', $reservation) }}" class="btn-primary">Modifier</a></div>
        </div>
    </x-slot>

    <div class="page-container"><div class="mx-auto max-w-5xl space-y-6">
        @if (session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
        <div class="panel p-5 sm:p-7">
            <dl class="grid gap-6 sm:grid-cols-2">
                <div><dt class="text-sm font-medium text-slate-500">Client</dt><dd class="mt-1"><a href="{{ route('clients.show', $reservation->client) }}" class="font-bold text-brand-600 hover:text-brand-800">{{ $reservation->client->full_name }}</a></dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Véhicule</dt><dd class="mt-1 text-slate-900">{{ $reservation->vehicle }}</dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Début</dt><dd class="mt-1 text-slate-900">{{ $reservation->start_date->format('d/m/Y') }}</dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Fin</dt><dd class="mt-1 text-slate-900">{{ $reservation->end_date->format('d/m/Y') }}</dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Statut</dt><dd class="mt-1 text-slate-900">{{ $reservation->status->label() }}</dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Appels liés</dt><dd class="mt-1 text-slate-900">{{ $reservation->customerCalls->count() }}</dd></div>
            </dl>
        </div>

        <section class="panel p-5 sm:p-7">
            <h3 class="font-bold text-slate-900">Historique des appels</h3>
            <div class="mt-4 divide-y divide-slate-100">
                @forelse ($reservation->customerCalls as $customerCall)
                    <a href="{{ route('customer-calls.show', $customerCall) }}" class="flex justify-between py-3 text-sm"><span class="font-medium text-slate-900">{{ $customerCall->reason->label() }} · {{ $customerCall->agent->name }}</span><span class="text-slate-500">{{ $customerCall->started_at->format('d/m/Y H:i') }}</span></a>
                @empty
                    <p class="py-4 text-sm text-slate-500">Aucun appel lié.</p>
                @endforelse
            </div>
        </section>

        <div class="panel p-5 sm:p-7">
            <form method="POST" action="{{ route('reservations.destroy', $reservation) }}" onsubmit="return confirm('Supprimer définitivement cette réservation ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-500">Supprimer cette réservation</button>
            </form>
            <p class="mt-2 text-xs text-slate-500">Les appels existants seront conservés et détachés de cette réservation.</p>
        </div>
    </div></div>
</x-app-layout>
