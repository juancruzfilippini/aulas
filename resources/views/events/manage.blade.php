<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Eventos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form id="bulk-update-form" method="POST" action="{{ route('events.bulkUpdate') }}">
                        @csrf
                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-4 py-2 text-left">
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Título</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Fecha</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Horario</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Espacio</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Miembros</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Solicitado por</th>
                                    <th class="border border-gray-300 px-4 py-2 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border border-gray-300 px-4 py-2">
                                            <input type="checkbox" name="selected_events[]" value="{{ $event->id }}"
                                                class="event-checkbox">
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $event->id }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $event->title }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $event->date }}</td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}
                                            - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $event->place }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $event->members }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $event->requested_by }}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-center">
                                            <a href="{{ route('events.show', $event->id) }}"
                                                class="text-blue-500 hover:underline">Ver</a>
                                            |
                                            <a href="{{ route('events.edit', $event->id) }}"
                                                class="text-green-500 hover:underline">Editar</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                        <!-- Paginación -->
                        <div class="mt-4">
                            {{ $events->links() }}
                        </div>

                        <div class="mt-4">
                            <label for="bulk-action" class="block text-sm font-medium text-gray-700">Modificar</label>
                            <select name="bulk_action" id="bulk-action"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                <option value="" disabled selected>Seleccione una acción</option>
                                <option value="update_date">Editar día</option>
                                <option value="update_time">Editar horario</option>
                                <option value="update_place">Editar lugar</option>
                                <option value="update_members">Editar miembros</option>
                                <option value="update_reason">Editar razón</option>
                                <option value="update_requested_by">Editar solicitante</option>
                            </select>
                        </div>

                        <!-- Contenedor dinámico para los inputs -->
                        <div id="dynamic-inputs" class="mt-4"></div>

                        <!-- Botón para aplicar cambios -->
                        <div class="mt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Aplicar a Seleccionados
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('select-all').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.event-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });


    document.getElementById('bulk-action').addEventListener('change', function () {
        const dynamicInputs = document.getElementById('dynamic-inputs');
        const selectedAction = this.value;

        // Limpia los inputs dinámicos antes de agregar nuevos
        dynamicInputs.innerHTML = '';

        // Genera los inputs según la acción seleccionada
        switch (selectedAction) {
            case 'update_date':
                // Campo para actualizar la fecha
                dynamicInputs.innerHTML = `
                <label for="new_date" class="block text-sm font-medium text-gray-700">Nueva fecha</label>
                <input type="date" name="new_date" id="new_date"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
            `;
                break;
            case 'update_time':
                // Campos para actualizar el horario
                dynamicInputs.innerHTML = `
                <label for="new_start_time" class="block text-sm font-medium text-gray-700">Nuevo horario de inicio</label>
                <input type="time" name="new_start_time" id="new_start_time"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">

                <label for="new_end_time" class="block text-sm font-medium text-gray-700">Nuevo horario de fin</label>
                <input type="time" name="new_end_time" id="new_end_time"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
            `;
                break;
            case 'update_place':
                // Campo para actualizar el lugar
                dynamicInputs.innerHTML = `
                <label for="new_place" class="block text-sm font-medium text-gray-700">Nuevo lugar</label>
                <input type="text" name="new_place" id="new_place"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
            `;
                break;
            case 'update_members':
                // Campo para actualizar el número de miembros
                dynamicInputs.innerHTML = `
                <label for="new_members" class="block text-sm font-medium text-gray-700">Nuevo número de miembros</label>
                <input type="number" name="new_members" id="new_members"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
            `;
                break;
            case 'update_reason':
                // Campo para actualizar la razón
                dynamicInputs.innerHTML = `
                <label for="new_reason" class="block text-sm font-medium text-gray-700">Nueva razón</label>
                <input type="text" name="new_reason" id="new_reason"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
            `;
                break;
            case 'update_requested_by':
                // Campo para actualizar el solicitante
                dynamicInputs.innerHTML = `
                <label for="new_requested_by" class="block text-sm font-medium text-gray-700">Nuevo solicitante</label>
                <input type="text" name="new_requested_by" id="new_requested_by"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
            `;
                break;
            default:
                // Si no se ha seleccionado ninguna opción válida
                dynamicInputs.innerHTML = '';
                break;
        }
    });

</script>