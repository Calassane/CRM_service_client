<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="eyebrow">Vue d’ensemble</p>
                <h1 class="page-title">Tableau de bord</h1>
                <p class="page-subtitle">Suivez la performance du service client et les tendances d’activité.</p>
            </div>

            <form method="GET" action="{{ route('dashboard') }}" class="flex items-end gap-2">
                <div>
                    <label for="period" class="form-label text-xs">Période analysée</label>
                    <select id="period" name="period" class="form-select min-w-44">
                        @foreach ($periodOptions as $option)
                            <option value="{{ $option['value'] }}" @selected($selectedPeriod === $option['value'])>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-secondary">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12a8 8 0 1 1-2.34-5.66M20 4v6h-6"/></svg>
                    Actualiser
                </button>
            </form>
        </div>
    </x-slot>

    <div class="page-container space-y-6">
        <section class="relative overflow-hidden rounded-3xl bg-slate-950 px-6 py-7 text-white shadow-lift sm:px-8">
            <div class="absolute -right-10 -top-20 h-64 w-64 rounded-full border border-brand-400/20"></div>
            <div class="absolute right-16 top-10 h-40 w-40 rounded-full bg-brand-500/10 blur-2xl"></div>
            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.18em] text-brand-300">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_0_4px_rgba(52,211,153,.12)]"></span>
                        Centre de pilotage actif
                    </div>
                    <h2 class="mt-3 text-2xl font-extrabold tracking-tight">L’activité client, en un coup d’œil.</h2>
                    <p class="mt-2 text-sm text-slate-400">{{ $periodLabel }}</p>
                </div>
                <a href="{{ route('customer-calls.index') }}" class="inline-flex items-center gap-2 self-start rounded-xl bg-white px-4 py-2.5 text-sm font-extrabold text-slate-950 transition hover:bg-brand-50 sm:self-auto">
                    Explorer les appels
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6"/></svg>
                </a>
            </div>
        </section>

        <section aria-label="Indicateurs clés" class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-4">
            <article class="panel group p-5 transition hover:-translate-y-0.5 hover:border-brand-200">
                <div class="flex items-start justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 3.5h3l1.5 4-2.25 1.5A15.5 15.5 0 0 0 15 14.25l1.5-2.25 4 1.5v3A3.5 3.5 0 0 1 17 20C9.82 20 4 14.18 4 7a3.5 3.5 0 0 1 3.5-3.5Z"/></svg>
                    </div>
                    <span class="status-badge bg-brand-50 text-brand-700">Période</span>
                </div>
                <p class="mt-5 text-3xl font-extrabold tracking-tight text-slate-950">{{ $metrics['total_calls'] }}</p>
                <p class="mt-1 text-sm font-semibold text-slate-500">Appels enregistrés</p>
            </article>

            <article class="panel group p-5 transition hover:-translate-y-0.5 hover:border-cyan-200">
                <div class="flex items-start justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 5.5h14a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-11a2 2 0 0 1 2-2Zm2-3v5m10-5v5M3 10h18"/></svg>
                    </div>
                    <span class="status-badge bg-cyan-50 text-cyan-700">7 jours</span>
                </div>
                <p class="mt-5 text-3xl font-extrabold tracking-tight text-slate-950">{{ $metrics['weekly_calls'] }}</p>
                <p class="mt-1 text-sm font-semibold text-slate-500">Appels cette semaine</p>
            </article>

            <article class="panel group p-5 transition hover:-translate-y-0.5 hover:border-violet-200">
                <div class="flex items-start justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-50 text-violet-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="1.8"/><path stroke-linecap="round" stroke-width="1.8" d="M12 7v5l3 2"/></svg>
                    </div>
                    <span class="status-badge bg-violet-50 text-violet-700">Moyenne</span>
                </div>
                <p class="mt-5 text-3xl font-extrabold tracking-tight text-slate-950">{{ $metrics['average_duration'] }}</p>
                <p class="mt-1 text-sm font-semibold text-slate-500">Durée de traitement</p>
            </article>

            <article class="panel group p-5 transition hover:-translate-y-0.5 hover:border-emerald-200">
                <div class="flex items-start justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m5 12 4 4L19 6"/></svg>
                    </div>
                    <span class="status-badge bg-emerald-50 text-emerald-700">Qualité</span>
                </div>
                <p class="mt-5 text-3xl font-extrabold tracking-tight text-slate-950">{{ $metrics['resolution_rate'] }}<span class="ml-1 text-lg text-slate-400">%</span></p>
                <p class="mt-1 text-sm font-semibold text-slate-500">Taux de résolution</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-3" aria-label="Volumes d’appels">
            <article class="panel xl:col-span-2">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Évolution quotidienne</h3>
                        <p class="panel-description">Nombre d’appels enregistrés chaque jour.</p>
                    </div>
                    <span class="status-badge"><span class="h-2 w-2 rounded-full bg-brand-500"></span>Volume d’appels</span>
                </div>
                <div class="h-80 p-5 sm:p-6">
                    <canvas id="daily-calls-chart" aria-label="Graphique du volume quotidien des appels" role="img"></canvas>
                </div>
            </article>

            <article class="panel">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Volume hebdomadaire</h3>
                        <p class="panel-description">Répartition par semaine.</p>
                    </div>
                </div>
                <div class="h-80 p-5 sm:p-6">
                    <canvas id="weekly-calls-chart" aria-label="Graphique du volume hebdomadaire des appels" role="img"></canvas>
                </div>
            </article>
        </section>

        <section class="grid gap-6 lg:grid-cols-2" aria-label="Répartition des appels">
            <article class="panel">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Motifs des appels</h3>
                        <p class="panel-description">Les sujets qui mobilisent le service client.</p>
                    </div>
                </div>
                <div class="h-80 p-5 sm:p-6">
                    <canvas id="call-reasons-chart" aria-label="Graphique des appels par motif" role="img"></canvas>
                </div>
            </article>

            <article class="panel">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Statuts des appels</h3>
                        <p class="panel-description">État de traitement des demandes.</p>
                    </div>
                </div>
                <div class="h-80 p-5 sm:p-6">
                    <canvas id="call-statuses-chart" aria-label="Graphique des appels par statut" role="img"></canvas>
                </div>
            </article>
        </section>

        <section class="table-shell" aria-labelledby="agent-ranking-title">
            <div class="panel-header">
                <div>
                    <h3 id="agent-ranking-title" class="panel-title">Activité par agent</h3>
                    <p class="panel-description">Classement par nombre d’appels sur la période sélectionnée.</p>
                </div>
                <span class="status-badge">{{ count($agentRanking) }} agent(s)</span>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Rang</th>
                            <th>Agent</th>
                            <th class="text-right">Appels</th>
                            <th class="text-right">Durée moyenne</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($agentRanking as $index => $agent)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <span class="{{ $index < 3 ? 'bg-brand-50 text-brand-700' : 'bg-slate-100 text-slate-500' }} inline-flex h-8 w-8 items-center justify-center rounded-lg text-xs font-extrabold">#{{ $index + 1 }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-900 text-xs font-extrabold text-white">{{ mb_strtoupper(mb_substr($agent['name'], 0, 1)) }}</div>
                                        <span class="font-bold text-slate-900">{{ $agent['name'] }}</span>
                                    </div>
                                </td>
                                <td class="text-right font-bold text-slate-900">{{ $agent['calls'] }}</td>
                                <td class="text-right">{{ $agent['average_duration'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="1.8" d="M4 19V9m5 10V5m5 14v-7m5 7V3"/></svg>
                                        </div>
                                        <p class="mt-3 font-bold text-slate-700">Aucune activité sur cette période.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script id="dashboard-chart-data" type="application/json">@json($charts)</script>

    @push('scripts')
        @vite('resources/js/dashboard.js')
    @endpush
</x-app-layout>
