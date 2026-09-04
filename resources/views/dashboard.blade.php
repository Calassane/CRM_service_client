<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Tableau de bord</h2>
                <p class="mt-1 text-sm text-gray-500">Vue analytique de l’activité du service client.</p>
            </div>

            <form method="GET" action="{{ route('dashboard') }}" class="flex items-end gap-2">
                <div>
                    <label for="period" class="block text-xs font-medium uppercase tracking-wide text-gray-500">Période</label>
                    <select id="period" name="period" class="mt-1 rounded-md border-gray-300 py-2 pl-3 pr-9 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach ($periodOptions as $option)
                            <option value="{{ $option['value'] }}" @selected($selectedPeriod === $option['value'])>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-700">Actualiser</button>
            </form>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">{{ $periodLabel }}</p>
                <a href="{{ route('customer-calls.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">Voir tous les appels</a>
            </div>

            <section aria-label="Indicateurs clés" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-sm font-medium text-gray-500">Appels sur la période</p>
                    <p class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $metrics['total_calls'] }}</p>
                </article>
                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-sm font-medium text-gray-500">Appels cette semaine</p>
                    <p class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $metrics['weekly_calls'] }}</p>
                </article>
                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-sm font-medium text-gray-500">Durée moyenne</p>
                    <p class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $metrics['average_duration'] }}</p>
                </article>
                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-sm font-medium text-gray-500">Taux de résolution</p>
                    <p class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $metrics['resolution_rate'] }} %</p>
                </article>
            </section>

            <section class="grid gap-6 xl:grid-cols-3" aria-label="Volumes d’appels">
                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100 xl:col-span-2">
                    <div class="mb-5">
                        <h3 class="font-semibold text-gray-900">Évolution quotidienne</h3>
                        <p class="mt-1 text-sm text-gray-500">Nombre d’appels enregistrés chaque jour.</p>
                    </div>
                    <div class="h-80">
                        <canvas id="daily-calls-chart" aria-label="Graphique du volume quotidien des appels" role="img"></canvas>
                    </div>
                </article>

                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <div class="mb-5">
                        <h3 class="font-semibold text-gray-900">Volume hebdomadaire</h3>
                        <p class="mt-1 text-sm text-gray-500">Regroupement des appels par semaine.</p>
                    </div>
                    <div class="h-80">
                        <canvas id="weekly-calls-chart" aria-label="Graphique du volume hebdomadaire des appels" role="img"></canvas>
                    </div>
                </article>
            </section>

            <section class="grid gap-6 lg:grid-cols-2" aria-label="Répartition des appels">
                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <div class="mb-5">
                        <h3 class="font-semibold text-gray-900">Motifs des appels</h3>
                        <p class="mt-1 text-sm text-gray-500">Répartition selon la nature de la demande.</p>
                    </div>
                    <div class="h-80">
                        <canvas id="call-reasons-chart" aria-label="Graphique des appels par motif" role="img"></canvas>
                    </div>
                </article>

                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <div class="mb-5">
                        <h3 class="font-semibold text-gray-900">Statuts des appels</h3>
                        <p class="mt-1 text-sm text-gray-500">Suivi de l’état de traitement des demandes.</p>
                    </div>
                    <div class="h-80">
                        <canvas id="call-statuses-chart" aria-label="Graphique des appels par statut" role="img"></canvas>
                    </div>
                </article>
            </section>

            <section class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100" aria-labelledby="agent-ranking-title">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h3 id="agent-ranking-title" class="font-semibold text-gray-900">Activité par agent</h3>
                    <p class="mt-1 text-sm text-gray-500">Classement par nombre d’appels sur la période sélectionnée.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Rang</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Agent</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Appels</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Durée moyenne</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($agentRanking as $index => $agent)
                                <tr>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500">#{{ $index + 1 }}</td>
                                    <td class="px-5 py-4 text-sm font-medium text-gray-900">{{ $agent['name'] }}</td>
                                    <td class="px-5 py-4 text-right text-sm text-gray-600">{{ $agent['calls'] }}</td>
                                    <td class="px-5 py-4 text-right text-sm text-gray-600">{{ $agent['average_duration'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center text-sm text-gray-500">Aucune activité sur cette période.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <script id="dashboard-chart-data" type="application/json">@json($charts)</script>

    @push('scripts')
        @vite('resources/js/dashboard.js')
    @endpush
</x-app-layout>
