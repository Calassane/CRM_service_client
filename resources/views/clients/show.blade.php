<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div><h2 class="text-xl font-semibold leading-tight text-gray-800">{{ $client->full_name }}</h2><p class="mt-1 text-sm text-gray-500">Fiche client</p></div>
            <div class="flex gap-3">
                <a href="{{ route('clients.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700">Retour</a>
                <a href="{{ route('clients.edit', $client) }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Modifier</a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))<div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>@endif
            @error('client')<div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $message }}</div>@enderror

            <div class="rounded-lg bg-white p-6 shadow-sm">
                <dl class="grid gap-5 sm:grid-cols-2">
                    <div><dt class="text-sm font-medium text-gray-500">Téléphone</dt><dd class="mt-1 text-gray-900">{{ $client->phone }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Email</dt><dd class="mt-1 text-gray-900">{{ $client->email ?: 'Non renseigné' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Ville</dt><dd class="mt-1 text-gray-900">{{ $client->city }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Adresse</dt><dd class="mt-1 text-gray-900">{{ $client->address ?: 'Non renseignée' }}</dd></div>
                </dl>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between"><h3 class="font-semibold text-gray-900">Réservations</h3><a href="{{ route('reservations.create', ['client_id' => $client->id]) }}" class="text-sm font-medium text-indigo-600">Ajouter</a></div>
                    <div class="mt-4 divide-y divide-gray-100">
                        @forelse ($client->reservations as $reservation)
                            <a href="{{ route('reservations.show', $reservation) }}" class="flex justify-between py-3 text-sm"><span class="font-medium text-gray-900">{{ $reservation->reference }} · {{ $reservation->vehicle }}</span><span class="text-gray-500">{{ $reservation->status->label() }}</span></a>
                        @empty
                            <p class="py-4 text-sm text-gray-500">Aucune réservation.</p>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between"><h3 class="font-semibold text-gray-900">Appels</h3><a href="{{ route('customer-calls.create', ['client_id' => $client->id]) }}" class="text-sm font-medium text-indigo-600">Enregistrer</a></div>
                    <div class="mt-4 divide-y divide-gray-100">
                        @forelse ($client->customerCalls as $customerCall)
                            <a href="{{ route('customer-calls.show', $customerCall) }}" class="flex justify-between py-3 text-sm"><span class="font-medium text-gray-900">{{ $customerCall->reason->label() }} · {{ $customerCall->agent->name }}</span><span class="text-gray-500">{{ $customerCall->started_at->format('d/m/Y') }}</span></a>
                        @empty
                            <p class="py-4 text-sm text-gray-500">Aucun appel.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('clients.destroy', $client) }}" onsubmit="return confirm('Supprimer définitivement ce client ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-500">Supprimer ce client</button>
                </form>
                <p class="mt-2 text-xs text-gray-500">Un client possédant un historique ne peut pas être supprimé.</p>
            </div>
        </div>
    </div>
</x-app-layout>
