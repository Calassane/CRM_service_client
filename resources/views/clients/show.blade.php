<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div><h2 class="page-title">{{ $client->full_name }}</h2><p class="page-subtitle">Fiche client</p></div>
            <div class="flex gap-3">
                <a href="{{ route('clients.index') }}" class="btn-secondary">Retour</a>
                <a href="{{ route('clients.edit', $client) }}" class="btn-primary">Modifier</a>
            </div>
        </div>
    </x-slot>

    <div class="page-container">
        <div class="mx-auto max-w-6xl space-y-6">
            @if (session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
            @error('client')<div class="alert-danger">{{ $message }}</div>@enderror

            <div class="panel p-5 sm:p-7">
                <dl class="grid gap-5 sm:grid-cols-2">
                    <div><dt class="text-sm font-medium text-slate-500">Téléphone</dt><dd class="mt-1 text-slate-900">{{ $client->phone }}</dd></div>
                    <div><dt class="text-sm font-medium text-slate-500">Email</dt><dd class="mt-1 text-slate-900">{{ $client->email ?: 'Non renseigné' }}</dd></div>
                    <div><dt class="text-sm font-medium text-slate-500">Ville</dt><dd class="mt-1 text-slate-900">{{ $client->city }}</dd></div>
                    <div><dt class="text-sm font-medium text-slate-500">Adresse</dt><dd class="mt-1 text-slate-900">{{ $client->address ?: 'Non renseignée' }}</dd></div>
                </dl>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <section class="panel p-5 sm:p-7">
                    <div class="flex items-center justify-between"><h3 class="font-bold text-slate-900">Réservations</h3><a href="{{ route('reservations.create', ['client_id' => $client->id]) }}" class="text-sm font-bold text-brand-600 hover:text-brand-800">Ajouter</a></div>
                    <div class="mt-4 divide-y divide-slate-100">
                        @forelse ($client->reservations as $reservation)
                            <a href="{{ route('reservations.show', $reservation) }}" class="flex justify-between py-3 text-sm"><span class="font-medium text-slate-900">{{ $reservation->reference }} · {{ $reservation->vehicle }}</span><span class="text-slate-500">{{ $reservation->status->label() }}</span></a>
                        @empty
                            <p class="py-4 text-sm text-slate-500">Aucune réservation.</p>
                        @endforelse
                    </div>
                </section>

                <section class="panel p-5 sm:p-7">
                    <div class="flex items-center justify-between"><h3 class="font-bold text-slate-900">Appels</h3><a href="{{ route('customer-calls.create', ['client_id' => $client->id]) }}" class="text-sm font-bold text-brand-600 hover:text-brand-800">Enregistrer</a></div>
                    <div class="mt-4 divide-y divide-slate-100">
                        @forelse ($client->customerCalls as $customerCall)
                            <a href="{{ route('customer-calls.show', $customerCall) }}" class="flex justify-between py-3 text-sm"><span class="font-medium text-slate-900">{{ $customerCall->reason->label() }} · {{ $customerCall->agent->name }}</span><span class="text-slate-500">{{ $customerCall->started_at->format('d/m/Y') }}</span></a>
                        @empty
                            <p class="py-4 text-sm text-slate-500">Aucun appel.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="panel p-5 sm:p-7">
                <form method="POST" action="{{ route('clients.destroy', $client) }}" onsubmit="return confirm('Supprimer définitivement ce client ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-500">Supprimer ce client</button>
                </form>
                <p class="mt-2 text-xs text-slate-500">Un client possédant un historique ne peut pas être supprimé.</p>
            </div>
        </div>
    </div>
</x-app-layout>
