<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Détail de l’appel</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $customerCall->client->full_name }} · {{ $customerCall->started_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('customer-calls.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Retour</a>
                @can('update', $customerCall)
                    <a href="{{ route('customer-calls.edit', $customerCall) }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Modifier</a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
            @endif

            <div class="rounded-lg bg-white p-6 shadow-sm">
                <dl class="grid gap-6 sm:grid-cols-2">
                    <div><dt class="text-sm font-medium text-gray-500">Client</dt><dd class="mt-1 text-gray-900">{{ $customerCall->client->full_name }} — {{ $customerCall->client->phone }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Agent</dt><dd class="mt-1 text-gray-900">{{ $customerCall->agent->name }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Sens</dt><dd class="mt-1 text-gray-900">{{ $customerCall->direction->label() }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Motif</dt><dd class="mt-1 text-gray-900">{{ $customerCall->reason->label() }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Statut</dt><dd class="mt-1 text-gray-900">{{ $customerCall->status->label() }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Durée</dt><dd class="mt-1 text-gray-900">{{ $customerCall->duration_seconds }} secondes</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Date et heure</dt><dd class="mt-1 text-gray-900">{{ $customerCall->started_at->format('d/m/Y H:i') }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Réservation</dt><dd class="mt-1 text-gray-900">{{ $customerCall->reservation?->reference ?? 'Aucune' }}</dd></div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Étiquettes</dt>
                        <dd class="mt-2 flex flex-wrap gap-2">
                            @forelse ($customerCall->tags as $tag)
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-700">{{ $tag->name }}</span>
                            @empty
                                <span class="text-sm text-gray-500">Aucune étiquette</span>
                            @endforelse
                        </dd>
                    </div>
                    <div class="sm:col-span-2"><dt class="text-sm font-medium text-gray-500">Notes</dt><dd class="mt-2 whitespace-pre-line text-gray-900">{{ $customerCall->notes ?: 'Aucune note.' }}</dd></div>
                </dl>

                @can('delete', $customerCall)
                    <div class="mt-8 border-t border-gray-100 pt-6">
                        <form method="POST" action="{{ route('customer-calls.destroy', $customerCall) }}" onsubmit="return confirm('Supprimer définitivement cet appel ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-500">Supprimer cet appel</button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
