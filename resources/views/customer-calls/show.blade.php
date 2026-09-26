<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="page-title">Détail de l’appel</h2>
                <p class="page-subtitle">{{ $customerCall->client->full_name }} · {{ $customerCall->started_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('customer-calls.index') }}" class="btn-secondary">Retour</a>
                @can('update', $customerCall)
                    <a href="{{ route('customer-calls.edit', $customerCall) }}" class="btn-primary">Modifier</a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="page-container">
        <div class="mx-auto max-w-5xl space-y-6">
            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <div class="panel p-5 sm:p-7">
                <dl class="grid gap-6 sm:grid-cols-2">
                    <div><dt class="text-sm font-medium text-slate-500">Client</dt><dd class="mt-1 text-slate-900">{{ $customerCall->client->full_name }} — {{ $customerCall->client->phone }}</dd></div>
                    <div><dt class="text-sm font-medium text-slate-500">Agent</dt><dd class="mt-1 text-slate-900">{{ $customerCall->agent->name }}</dd></div>
                    <div><dt class="text-sm font-medium text-slate-500">Sens</dt><dd class="mt-1 text-slate-900">{{ $customerCall->direction->label() }}</dd></div>
                    <div><dt class="text-sm font-medium text-slate-500">Motif</dt><dd class="mt-1 text-slate-900">{{ $customerCall->reason->label() }}</dd></div>
                    <div><dt class="text-sm font-medium text-slate-500">Statut</dt><dd class="mt-1 text-slate-900">{{ $customerCall->status->label() }}</dd></div>
                    <div><dt class="text-sm font-medium text-slate-500">Durée</dt><dd class="mt-1 text-slate-900">{{ $customerCall->duration_seconds }} secondes</dd></div>
                    <div><dt class="text-sm font-medium text-slate-500">Date et heure</dt><dd class="mt-1 text-slate-900">{{ $customerCall->started_at->format('d/m/Y H:i') }}</dd></div>
                    <div><dt class="text-sm font-medium text-slate-500">Réservation</dt><dd class="mt-1 text-slate-900">{{ $customerCall->reservation?->reference ?? 'Aucune' }}</dd></div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-slate-500">Étiquettes</dt>
                        <dd class="mt-2 flex flex-wrap gap-2">
                            @forelse ($customerCall->tags as $tag)
                                <span class="status-badge bg-brand-50 text-brand-700">{{ $tag->name }}</span>
                            @empty
                                <span class="text-sm text-slate-500">Aucune étiquette</span>
                            @endforelse
                        </dd>
                    </div>
                    <div class="sm:col-span-2"><dt class="text-sm font-medium text-slate-500">Notes</dt><dd class="mt-2 whitespace-pre-line text-slate-900">{{ $customerCall->notes ?: 'Aucune note.' }}</dd></div>
                </dl>

                @can('delete', $customerCall)
                    <div class="mt-8 border-t border-slate-100 pt-6">
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
