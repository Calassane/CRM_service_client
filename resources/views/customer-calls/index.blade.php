<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="eyebrow">Support & suivi</p>
                <h1 class="page-title">Appels clients</h1>
                <p class="page-subtitle">Consultez, qualifiez et filtrez toutes les interactions téléphoniques.</p>
            </div>
            <a href="{{ route('customer-calls.create') }}" class="btn-primary self-start sm:self-auto">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg>
                Nouvel appel
            </a>
        </div>
    </x-slot>

    <div class="page-container space-y-6">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('customer-calls.index') }}" class="panel">
            <div class="panel-header">
                <div>
                    <h2 class="panel-title">Filtres avancés</h2>
                    <p class="panel-description">Affinez la liste par agent, statut, motif ou période.</p>
                </div>
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="1.8" d="M4 6h16M7 12h10m-7 6h4"/></svg>
            </div>
            <div class="grid gap-4 p-5 sm:grid-cols-2 lg:grid-cols-5 sm:p-6">
                <div>
                    <label for="agent_id" class="form-label">Agent</label>
                    <select id="agent_id" name="agent_id" class="form-select">
                        <option value="">Tous</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}" @selected(($filters['agent_id'] ?? null) == $agent->id)>{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="form-label">Statut</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">Tous</option>
                        @foreach ($statusOptions as $option)
                            <option value="{{ $option['value'] }}" @selected(($filters['status'] ?? null) === $option['value'])>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="reason" class="form-label">Motif</label>
                    <select id="reason" name="reason" class="form-select">
                        <option value="">Tous</option>
                        @foreach ($reasonOptions as $option)
                            <option value="{{ $option['value'] }}" @selected(($filters['reason'] ?? null) === $option['value'])>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date_from" class="form-label">Du</label>
                    <input id="date_from" name="date_from" type="date" value="{{ $filters['date_from'] ?? '' }}" class="form-control">
                </div>
                <div>
                    <label for="date_to" class="form-label">Au</label>
                    <input id="date_to" name="date_to" type="date" value="{{ $filters['date_to'] ?? '' }}" class="form-control">
                </div>
            </div>
            <div class="flex flex-wrap gap-3 border-t border-slate-100 px-5 py-4 sm:px-6">
                <button type="submit" class="btn-primary">Appliquer les filtres</button>
                <a href="{{ route('customer-calls.index') }}" class="btn-secondary">Réinitialiser</a>
            </div>
        </form>

        <div class="table-shell">
            <div class="panel-header">
                <div>
                    <h2 class="panel-title">Journal des appels</h2>
                    <p class="panel-description">{{ $customerCalls->total() }} interaction(s) trouvée(s)</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Appel</th>
                            <th>Statut</th>
                            <th>Agent</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customerCalls as $customerCall)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <p class="font-bold text-slate-800">{{ $customerCall->started_at->format('d/m/Y') }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">{{ $customerCall->started_at->format('H:i') }}</p>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-xs font-extrabold text-brand-700">{{ mb_strtoupper(mb_substr($customerCall->client->first_name, 0, 1).mb_substr($customerCall->client->last_name, 0, 1)) }}</div>
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $customerCall->client->full_name }}</p>
                                            <p class="mt-0.5 text-xs text-slate-400">{{ $customerCall->client->phone }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="font-semibold text-slate-700">{{ $customerCall->direction->label() }} · {{ $customerCall->reason->label() }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">{{ $customerCall->duration_seconds }} secondes</p>
                                </td>
                                <td><span class="status-badge bg-brand-50 text-brand-700"><span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>{{ $customerCall->status->label() }}</span></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-900 text-[0.65rem] font-extrabold text-white">{{ mb_strtoupper(mb_substr($customerCall->agent->name, 0, 1)) }}</span>
                                        <span class="font-semibold text-slate-700">{{ $customerCall->agent->name }}</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap text-right">
                                    <a href="{{ route('customer-calls.show', $customerCall) }}" class="btn-ghost text-brand-600">Voir</a>
                                    @can('update', $customerCall)
                                        <a href="{{ route('customer-calls.edit', $customerCall) }}" class="btn-ghost">Modifier</a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <p class="font-bold text-slate-700">Aucun appel ne correspond aux filtres.</p>
                                        <p class="mt-1 text-sm text-slate-400">Essayez d’élargir vos critères de recherche.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($customerCalls->hasPages())
                <div class="border-t border-slate-100 px-5 py-4">{{ $customerCalls->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
