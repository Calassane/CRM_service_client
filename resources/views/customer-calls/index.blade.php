<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Appels clients</h2>
                <p class="mt-1 text-sm text-gray-500">Consultez et filtrez les appels enregistrés.</p>
            </div>
            <a href="{{ route('customer-calls.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                Nouvel appel
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <form method="GET" action="{{ route('customer-calls.index') }}" class="grid gap-4 rounded-lg bg-white p-5 shadow-sm md:grid-cols-5">
                <div>
                    <label for="agent_id" class="block text-sm font-medium text-gray-700">Agent</label>
                    <select id="agent_id" name="agent_id" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm">
                        <option value="">Tous</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}" @selected(($filters['agent_id'] ?? null) == $agent->id)>{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm">
                        <option value="">Tous</option>
                        @foreach ($statusOptions as $option)
                            <option value="{{ $option['value'] }}" @selected(($filters['status'] ?? null) === $option['value'])>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700">Motif</label>
                    <select id="reason" name="reason" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm">
                        <option value="">Tous</option>
                        @foreach ($reasonOptions as $option)
                            <option value="{{ $option['value'] }}" @selected(($filters['reason'] ?? null) === $option['value'])>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700">Du</label>
                    <input id="date_from" name="date_from" type="date" value="{{ $filters['date_from'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm">
                </div>

                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700">Au</label>
                    <input id="date_to" name="date_to" type="date" value="{{ $filters['date_to'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm">
                </div>

                <div class="flex gap-3 md:col-span-5">
                    <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">Filtrer</button>
                    <a href="{{ route('customer-calls.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Réinitialiser</a>
                </div>
            </form>

            <div class="overflow-hidden rounded-lg bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Client</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Appel</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Agent</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($customerCalls as $customerCall)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600">{{ $customerCall->started_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $customerCall->client->full_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $customerCall->client->phone }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        <div>{{ $customerCall->direction->label() }} · {{ $customerCall->reason->label() }}</div>
                                        <div class="mt-1 text-xs text-gray-400">{{ $customerCall->duration_seconds }} secondes</div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $customerCall->status->label() }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $customerCall->agent->name }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-right text-sm">
                                        <a href="{{ route('customer-calls.show', $customerCall) }}" class="font-medium text-indigo-600 hover:text-indigo-500">Voir</a>
                                        @can('update', $customerCall)
                                            <a href="{{ route('customer-calls.edit', $customerCall) }}" class="ml-3 font-medium text-gray-700 hover:text-gray-900">Modifier</a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500">Aucun appel ne correspond aux filtres.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($customerCalls->hasPages())
                    <div class="border-t border-gray-100 px-4 py-4">{{ $customerCalls->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
