<x-app-layout>
    <x-slot name="header">
        <p class="eyebrow">Relation client</p>
        <h1 class="page-title">Ajouter un client</h1>
        <p class="page-subtitle">Créez une fiche centralisée pour suivre ses réservations et ses échanges.</p>
    </x-slot>
    <div class="page-container">
        <div class="mx-auto max-w-4xl">
            <form method="POST" action="{{ route('clients.store') }}" class="panel p-5 sm:p-8">
                @csrf
                @include('clients.partials.form')
            </form>
        </div>
    </div>
</x-app-layout>
