<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div><h2 class="text-xl font-semibold leading-tight text-gray-800">Réservations</h2><p class="mt-1 text-sm text-gray-500">Suivez les locations des clients.</p></div>
            <a href="{{ route('reservations.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Créer une réservation</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))<div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>@endif

            <form method="GET" action="{{ route('reservations.index') }}" class="flex gap-3 rounded-lg bg-white p-5 shadow-sm">
                <div class="flex-1">
                    <label for="search" class="sr-only">Rechercher une réservation</label>
                    <input id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" placeholder="Référence, véhicule, client ou téléphone" class="block w-full rounded-md border-gray-300 text-sm shadow-sm">
                </div>
                <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">Rechercher</button>
                <a href="{{ route('reservations.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Réinitialiser</a>
            </form>

            <div class="overflow-hidden rounded-lg bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50"><tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Référence</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Client</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Véhicule</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Période</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Statut</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                        </tr></thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($reservations as $reservation)
                                <tr>
                                    <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ $reservation->reference }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $reservation->client->full_name }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $reservation->vehicle }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600">{{ $reservation->start_date->format('d/m/Y') }} – {{ $reservation->end_date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $reservation->status->label() }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-right text-sm"><a href="{{ route('reservations.show', $reservation) }}" class="font-medium text-indigo-600">Voir</a><a href="{{ route('reservations.edit', $reservation) }}" class="ml-3 font-medium text-gray-700">Modifier</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500">Aucune réservation trouvée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($reservations->hasPages())<div class="border-t border-gray-100 px-4 py-4">{{ $reservations->links() }}</div>@endif
            </div>
        </div>
    </div>
</x-app-layout>
