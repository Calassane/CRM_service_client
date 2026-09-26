<x-app-layout>
    <x-slot name="header">
        <p class="eyebrow">Gestion des locations</p>
        <h1 class="page-title">Modifier {{ $reservation->reference }}</h1>
        <p class="page-subtitle">Ajustez le véhicule, les dates ou le statut de la réservation.</p>
    </x-slot>
    <div class="page-container">
        <div class="mx-auto max-w-4xl">
            <form method="POST" action="{{ route('reservations.update', $reservation) }}" class="panel p-5 sm:p-8">
                @csrf
                @method('PUT')
                @include('reservations.partials.form')
            </form>
        </div>
    </div>
</x-app-layout>
