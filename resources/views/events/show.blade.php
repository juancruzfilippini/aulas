<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles del Evento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">{{ $event->title }}</h3>
                    <p><strong>Fecha:</strong> {{ $event->date }}</p>
                    <p><strong>Hora Inicio:</strong> {{ $event->start_time }}</p>
                    <p><strong>Hora Fin:</strong> {{ $event->end_time }}</p>
                    <p><strong>Espacio:</strong> {{ $event->place }}</p>
                    <p><strong>Solicitado por:</strong> {{ $event->requested_by }}</p>
                    <p><strong>Participantes estimados:</strong> {{ $event->members }}</p>
                    <p><strong>Motivo:</strong> {{ $event->reason }}</p>
                    <a href="{{ route('dashboard') }}"
                        class="mt-4 inline-block text-blue-500 hover:underline">Volver al listado</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
