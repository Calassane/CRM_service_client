<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div><h2 class="text-xl font-semibold leading-tight text-gray-800">{{ $reservation->reference }}</h2><p class="mt-1 text-sm text-gray-500">Détail de la réservation</p></div>
            <div class="flex gap-3"><a href="{{ route('reservations.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700">Retour</a><a href="{{ route('reservations.edit', $reservation) }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Modifier</a></div>
        </div>
    </x-slot>

    <div class="py-10"><div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
        @if (session('success'))<div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>@endif
        <div class="rounded-lg bg-white p-6 shadow-sm">
            <dl class="grid gap-6 sm:grid-cols-2">
                <div><dt class="text-sm font-medium text-gray-500">Client</dt><dd class="mt-1"><a href="{{ route('clients.show', $reservation->client) }}" class="font-medium text-indigo-600">{{ $reservation->client->full_name }}</a></dd></div>
                <div><dt class="text-sm font-medium text-gray-500">Véhicule</dt><dd class="mt-1 text-gray-900">{{ $reservation->vehicle }}</dd></div>
                <div><dt class="text-sm font-medium text-gray-500">Début</dt><dd class="mt-1 text-gray-900">{{ $reservation->start_date->format('d/m/Y') }}</dd></div>
                <div><dt class="text-sm font-medium text-gray-500">Fin</dt><dd class="mt-1 text-gray-900">{{ $reservation->end_date->format('d/m/Y') }}</dd></div>
                <div><dt class="text-sm font-medium text-gray-500">Statut</dt><dd class="mt-1 text-gray-900">{{ $reservation->status->label() }}</dd></div>
                <div><dt class="text-sm font-medium text-gray-500">Appels liés</dt><dd class="mt-1 text-gray-900">{{ $reservation->customerCalls->count() }}</dd></div>
            </dl>
        </div>

        <section class="rounded-lg bg-white p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900">Historique des appels</h3>
            <div class="mt-4 divide-y divide-gray-100">
                @forelse ($reservation->customerCalls as $customerCall)
                    <a href="{{ route('customer-calls.show', $customerCall) }}" class="flex justify-between py-3 text-sm"><span class="font-medium text-gray-900">{{ $customerCall->reason->label() }} · {{ $customerCall->agent->name }}</span><span class="text-gray-500">{{ $customerCall->started_at->format('d/m/Y H:i') }}</span></a>
                @empty
                    <p class="py-4 text-sm text-gray-500">Aucun appel lié.</p>
                @endforelse
            </div>
        </section>

        <div class="rounded-lg bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('reservations.destroy', $reservation) }}" onsubmit="return confirm('Supprimer définitivement cette réservation ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-500">Supprimer cette réservation</button>
            </form>
            <p class="mt-2 text-xs text-gray-500">Les appels existants seront conservés et détachés de cette réservation.</p>
        </div>
    </div></div>
</x-app-layout>
