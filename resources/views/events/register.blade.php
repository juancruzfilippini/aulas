<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registrar Evento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto py-10">
                    <div class="w-full max-w-4xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-gray-100 border-b border-gray-200">
                        </div>
                        <div class="p-6">

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ route('events.store') }}" method="POST" class="space-y-8">
                                @csrf

                                <div class="space-y-6">
                                    <div class="space-y-2">
                                        <label for="title" class="block text-sm font-medium text-gray-700">Título del
                                            Evento</label>
                                        <input type="text" id="title" name="title" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-4 mb-4 mt-3">

                                    <div class="flex-1 space-y-2">
                                        <label for="date" class="block text-sm font-medium text-gray-700">Fecha
                                            del evento</label>
                                        <input type="date" id="date" name="date" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    <div class="flex-1 space-y-2">
                                        <label for="start_time" class="block text-sm font-medium text-gray-700">Hora
                                            inicio</label>
                                        <input type="time" id="start_time" name="start_time" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    <div class="flex-1 space-y-2">
                                        <label for="end_time" class="block text-sm font-medium text-gray-700">Hora
                                            fin</label>
                                        <input type="time" id="end_time" name="end_time" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                </div>


                                <div class="space-y-6 mb-4">
                                    <div class="flex space-x-4 gap-4">
                                        <div class="space-y-2 flex-1">
                                            <label for="place"
                                                class="block text-sm font-medium text-gray-700">Espacio</label>
                                            <input type="text" id="place" name="place" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </div>
                                        <div class="space-y-2 flex-1">
                                            <label for="requested_by"
                                                class="block text-sm font-medium text-gray-700">Solicitado por</label>
                                            <input type="text" id="requested_by" name="requested_by" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </div>
                                        <div class="space-y-2 flex-1">
                                            <label for="members"
                                                class="block text-sm font-medium text-gray-700">Participantes
                                                estimados</label>
                                            <input type="number" id="members" name="members" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </div>
                                    </div>

                                    <div class="space-y-6">
                                        <div class="space-y-2">
                                            <label for="reason"
                                                class="block text-sm font-medium text-gray-700">Motivo</label>
                                            <input type="text" id="reason" name="reason" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="btn"
                                        style="background-color: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; transition: background-color 0.3s;"
                                        onmouseover="this.style.backgroundColor='#1d4ed8'"
                                        onmouseout="this.style.backgroundColor='#2563eb'">
                                        Registrar Personal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>