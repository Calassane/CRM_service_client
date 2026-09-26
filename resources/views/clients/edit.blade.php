<x-app-layout>
    <x-slot name="header">
        <p class="eyebrow">Relation client</p>
        <h1 class="page-title">Modifier {{ $client->full_name }}</h1>
        <p class="page-subtitle">Maintenez les coordonnées et informations du client à jour.</p>
    </x-slot>
    <div class="page-container">
        <div class="mx-auto max-w-4xl">
            <form method="POST" action="{{ route('clients.update', $client) }}" class="panel p-5 sm:p-8">
                @csrf
                @method('PUT')
                @include('clients.partials.form')
            </form>
        </div>
    </div>
</x-app-layout>
