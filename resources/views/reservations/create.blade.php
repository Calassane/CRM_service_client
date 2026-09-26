<x-app-layout>
    <x-slot name="header">
        <p class="eyebrow">Gestion des locations</p>
        <h1 class="page-title">Créer une réservation</h1>
        <p class="page-subtitle">Associez un client, un véhicule et une période de location.</p>
    </x-slot>
    <div class="page-container">
        <div class="mx-auto max-w-4xl">
            <form method="POST" action="{{ route('reservations.store') }}" class="panel p-5 sm:p-8">
                @csrf
                @include('reservations.partials.form')
            </form>
        </div>
    </div>
</x-app-layout>
