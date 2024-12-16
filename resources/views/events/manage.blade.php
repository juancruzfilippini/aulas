<x-app-layout>
    <div class="py-12 px-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="alert alert-success mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger mb-4 p-4 bg-red-100 text-red-800 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-6">
                <form method="GET" action="{{ route('events.manage') }}" class="flex items-center space-x-4">
                    <select name="month" class="border border-gray-300 rounded-md p-2" style="width: 200px;">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" @if($i == $month) selected @endif>
                                {{ \Carbon\Carbon::create()->month($i)->locale('es')->monthName }}
                            </option>
                        @endfor
                    </select>

                    <select name="year" class="border border-gray-300 rounded-md p-2" style="width: 100px;">
                        @for ($y = now()->year - 5; $y <= now()->year + 1; $y++)
                            <option value="{{ $y }}" @if($y == $year) selected @endif>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>

                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Filtrar
                    </button>
                </form>
            </div>

            <form id="bulk-update-form" method="POST" action="{{ route('events.bulkUpdate') }}">
                @csrf
                <table class="min-w-full border-collapse border border-gray-300 text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2 text-left">
                                <input type="checkbox" id="select-all" class="form-checkbox">
                            </th>
                            <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Título</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Fecha</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Horario</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Espacio</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Solicitado por</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr class="hover:bg-gray-50 cursor-pointer" data-id="{{ $event->id }}">
                                <td class="border border-gray-300 px-4 py-2 text-center">
                                    <input type="checkbox" name="selected_events[]" value="{{ $event->id }}"
                                        class="form-checkbox">
                                </td>
                                <td class="border border-gray-300 px-4 py-2">{{ $event->id }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $event->title }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    {{ \Carbon\Carbon::parse($event->date)->format('d/m/y') }} 
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}
                                    - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}
                                </td>
                                <td class="border border-gray-300 px-4 py-2">{{ $event->place }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $event->requested_by }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Botón para eliminar eventos seleccionados -->
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-red-500 text-white rounded-md hover:bg-red-600">
                        Eliminar eventos seleccionados
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.addEventListener('click', function (event) {
                if (event.target.type !== 'checkbox') {
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                }
            });
        });

        // Seleccionar/deseleccionar todos
        document.getElementById('select-all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    });
</script>