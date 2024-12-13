<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Generar nuevo reporte') }}
        </h2>
    </x-slot>

    <div class="py-12 flex items-start justify-center min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 w-full max-w-md mt-12">
            <form action="{{ route('events.report') }}" method="GET" class="space-y-4" target="_blank">
                <div class="text-center">
                    <label for="date_range" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Seleccionar rango de fechas') }}
                    </label>

                    <input type="text" id="date_range" name="date_range" required
                        class="mt-1 block w-full max-w-xs mx-auto border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 flatpickr-input">

                    <button type="submit"
                        class="mt-4 px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700 transition duration-200">
                        {{ __('Generar Reporte') }}
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Flatpickr CSS y JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

    <!-- Script para inicializar Flatpickr -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#date_range", {
                mode: "range",
                dateFormat: "Y-m-d",
                locale: {
                    firstDayOfWeek: 1,
                    weekdays: {
                        shorthand: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                        longhand: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                    },
                    months: {
                        shorthand: [
                            "Ene", "Feb", "Mar", "Abr", "May", "Jun",
                            "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"
                        ],
                        longhand: [
                            "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                        ],
                    },
                    rangeSeparator: " a ",
                },
            });
        });
    </script>
</x-app-layout>