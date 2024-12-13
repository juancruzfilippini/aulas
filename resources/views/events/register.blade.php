<x-app-layout> 

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mx-auto py-10">
                    <div class="w-full max-w-4xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">

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


                            <form action="{{ route('events.storeEvent') }}" method="POST" class="space-y-8">
                                @csrf

                                <div class="space-y-6">
                                    <div class="space-y-2">
                                        <label for="title" class="block text-sm font-medium text-gray-700">Título del
                                            Evento</label>
                                        <input type="text" id="title" name="title" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            value="{{ old('title') }}">
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-4 mb-4 mt-3">
                                    <!-- Input de fechas con botón limpiar -->
                                    <div id="recurrence_options" class="flex flex-1 items-end gap-2">
                                        <div class="flex-1">
                                            <label for="recurrence_dates"
                                                class="block text-sm font-medium text-gray-700">
                                                Fechas de repetición
                                            </label>
                                            <input type="text" id="recurrence_dates" name="recurrence_dates"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                placeholder="Selecciona las fechas" required>
                                        </div>
                                        <button type="button" id="clear_recurrence_dates"
                                            class="mt-6 bg-red-500 text-white px-2 py-2 rounded hover:bg-red-600 transition h-10 mt-auto">
                                            Limpiar
                                        </button>
                                    </div>

                                    <!-- Input de hora de inicio -->
                                    <div class="space-y-2">
                                        <label for="start_time" class="block text-sm font-medium text-gray-700">Hora
                                            inicio</label>
                                        <input type="time" id="start_time" name="start_time"
                                            value="{{ old('start_time') }}" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>

                                    <!-- Input de hora de fin -->
                                    <div class="space-y-2">
                                        <label for="end_time" class="block text-sm font-medium text-gray-700">Hora
                                            fin</label>
                                        <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}"
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                </div>



                                <div class="space-y-6 mb-4">
                                    <div class="flex space-x-4 gap-4">
                                        <div class="space-y-2 flex-1">
                                            <label for="place"
                                                class="block text-sm font-medium text-gray-700">Espacio</label>
                                            <select type="text" id="place" name="place" required
                                                value="{{ old('place') }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="" disabled selected>Seleccione un lugar</option>
                                                @foreach ($places as $place)
                                                    <option value="{{ $place->id }}" data-capacity="{{ $place->capacity }}">
                                                        {{ $place->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <p id="capacity-warning" class="mt-2 text-sm text-red-600"
                                                style="display: none;"></p>
                                        </div>
                                        <div class="space-y-2 flex-1">
                                            <label for="requested_by"
                                                class="block text-sm font-medium text-gray-700">Solicitado por</label>
                                            <input type="text" id="requested_by" name="requested_by" required
                                                value="{{ old('requested_by') }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </div>
                                        
                                    </div>

                                   
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="btn"
                                        style="background-color: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; transition: background-color 0.3s; margin-bottom: 20px"
                                        onmouseover="this.style.backgroundColor='#1d4ed8'"
                                        onmouseout="this.style.backgroundColor='#2563eb'">
                                        Registrar Evento
                                    </button>
                                </div>
                            </form>
                            <div id="events-card" class="mt-6 hidden bg-gray-100 p-4 rounded-lg shadow-md">
                                <h3 class="text-lg font-semibold mb-3">Eventos Programados</h3>
                                <ul id="events-list" class="space-y-2">
                                    <!-- Aquí se llenarán los eventos -->
                                </ul>
                            </div>

                            <div id="events-container" class="flex flex-wrap gap-4">
                                <!-- Las tarjetas de eventos se generan aquí dinámicamente -->
                            </div>

                        </div>
                    </div>

                </div>

            </div>

        

    </div>

</x-app-layout>

<script>

    const events = @json($events);

    // Inicializar Flatpickr y guardar su instancia
    let flatpickrInstance = flatpickr("#recurrence_dates", {
        locale: 'es',
        mode: 'multiple', // Selección de varias fechas
        dateFormat: 'Y-m-d', // Formato de fecha
    });

    // Botón para reiniciar el calendario
    document.getElementById("clear_recurrence_dates").addEventListener("click", function () {
        // Destruir la instancia de Flatpickr
        flatpickrInstance.destroy();

        // Limpiar el campo de texto
        document.getElementById("recurrence_dates").value = "";

        // Volver a inicializar Flatpickr
        flatpickrInstance = flatpickr("#recurrence_dates", {
            locale: 'es',
            mode: 'multiple', // Selección de varias fechas
            dateFormat: 'Y-m-d', // Formato de fecha
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const placeSelect = document.getElementById('place');
        const capacityWarning = document.getElementById('capacity-warning');

        placeSelect.addEventListener('change', function () {
            const selectedOption = placeSelect.options[placeSelect.selectedIndex];
            const capacity = selectedOption.getAttribute('data-capacity');

            if (capacity) {
                capacityWarning.textContent = `Capacidad: ${capacity} personas.`;
                capacityWarning.style.display = 'block';
            } else {
                capacityWarning.style.display = 'none';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const recurrenceDatesInput = document.getElementById('recurrence_dates');
        const eventsContainer = document.getElementById('events-container');
        const clearRecurrenceDatesButton = document.getElementById('clear_recurrence_dates');

        // Lista de días de la semana
        const daysOfWeek = [
            'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'
        ];

        // Función para filtrar eventos por una fecha específica
        function filterEventsByDate(date) {
            return events.filter(event => event.date === date);
        }

        // Función para formatear la fecha como "día de la semana dd/mm/yyyy"
        function formatDateWithDay(dateString) {
            console.log('Fecha que llega por parámetro a la función: ' + dateString);

            // Dividir el string en componentes de fecha (año, mes, día)
            const [year, month, day] = dateString.split('-').map(Number);

            // Crear la fecha ajustando el mes (0-11 en JavaScript)
            const date = new Date(year, month - 1, day);
            console.log('Fecha creada manualmente: ' + date);

            // Validar si la fecha es válida
            if (isNaN(date.getTime())) {
                throw new Error(`Fecha inválida: ${dateString}`);
            }

            // Obtener el día de la semana
            const daysOfWeek = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            const dayOfWeek = daysOfWeek[date.getDay()];

            // Formatear los valores de día, mes y año
            const formattedDay = String(date.getDate()).padStart(2, '0');
            const formattedMonth = String(date.getMonth() + 1).padStart(2, '0'); // Mes ajustado
            const formattedYear = date.getFullYear();

            // Retornar la fecha formateada con día de la semana
            return `${dayOfWeek} ${formattedDay}/${formattedMonth}/${formattedYear}`;
        }


        // Función para crear una tarjeta de eventos para una fecha específica
        function createEventCard(date, eventsForDate) {
            const card = document.createElement('div');
            card.className = 'w-60 bg-gray-800 text-white p-4 rounded shadow-md';
            const header = document.createElement('h3');
            header.className = 'text-center font-bold text-lg mb-2';

            header.textContent = formatDateWithDay(date); // Formatear fecha
            card.appendChild(header);

            const ul = document.createElement('ul');
            ul.className = 'space-y-2';

            if (eventsForDate.length > 0) {
                eventsForDate.forEach(event => {
                    const li = document.createElement('li');
                    li.className = 'p-2 bg-blue-600 rounded text-center shadow-sm';
                    li.innerHTML = `<strong>${event.title}</strong><br><span style="text-decoration: underline;">${event.place}</span><br>${formatTime(event.start_time)} - ${formatTime(event.end_time)}`;
                    ul.appendChild(li);
                });
            } else {
                const noEvents = document.createElement('li');
                noEvents.className = 'text-center text-gray-400';
                noEvents.textContent = 'No hay eventos.';
                ul.appendChild(noEvents);
            }

            card.appendChild(ul);
            return card;
        }

        function formatTime(time) {
            return time.slice(0, 5); // Extraer solo los primeros 5 caracteres (hh:mm)
        }

        // Función para mostrar eventos para múltiples fechas seleccionadas
        function updateEventsView(selectedDates) {
            eventsContainer.innerHTML = '';
            console.log(selectedDates);
            selectedDates.forEach(date => {
                if (!date.trim()) return;

                try {
                    const formattedDate = new Date(date).toISOString().split('T')[0];
                    const eventsForDate = filterEventsByDate(formattedDate);

                    const card = createEventCard(formattedDate, eventsForDate);
                    eventsContainer.appendChild(card);
                } catch (error) {
                    console.error(`Fecha inválida: ${date}`, error);
                }
            });
        }

        // Evento para manejar el cambio en la selección de fechas
        recurrenceDatesInput.addEventListener('change', function () {
            const selectedDates = this.value.split(',');

            if (selectedDates.length > 0) {
                updateEventsView(selectedDates);
            } else {
                eventsContainer.innerHTML = '';
            }
        });

        // Evento para manejar el clic en el botón de limpiar
        clearRecurrenceDatesButton.addEventListener('click', function () {
            recurrenceDatesInput.value = '';
            eventsContainer.innerHTML = '';
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");
        const recurrenceDatesInput = document.getElementById("recurrence_dates");
        const placeInput = document.getElementById("place");
        const startTimeInput = document.getElementById("start_time");
        const endTimeInput = document.getElementById("end_time");

        form.addEventListener("submit", function (e) {
            const selectedDates = recurrenceDatesInput.value.split(",").map(date => date.trim());
            const selectedPlace = placeInput.value;
            const selectedStartTime = startTimeInput.value;
            const selectedEndTime = endTimeInput.value;

            // Validación de conflictos
            let conflict = false;
            let conflictDetails = [];

            selectedDates.forEach(date => {
                events.forEach(event => {
                    console.log('evento: ' + event.date);
                    if (

                        event.date === date && // Misma fecha
                        event.place_id == selectedPlace && // Mismo lugar
                        (
                            (selectedStartTime >= event.start_time && selectedStartTime < event.end_time) || // Inicio en rango
                            (selectedEndTime > event.start_time && selectedEndTime <= event.end_time) || // Fin en rango
                            (selectedStartTime <= event.start_time && selectedEndTime >= event.end_time) // Abarca todo el evento
                        )
                    ) {
                        conflict = true;
                        conflictDetails.push(`Conflicto con el evento "${event.title}" en ${date}, de ${event.start_time} a ${event.end_time}`);
                    }
                });
            });


            if (conflict) {
                e.preventDefault(); // Detener el envío del formulario
                alert(`No se puede registrar el evento debido a conflictos:\n\n${conflictDetails.join("\n")}`);
            }
        });
    });







</script>

<style>
    #events-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: flex-start;
    }

    .w-60 {
        width: 15rem;
    }

    .bg-gray-800 {
        background-color: #2d3748;
    }

    .bg-blue-600 {
        background-color: #3182ce;
    }

    .shadow-md {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .shadow-sm {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .space-y-2>*+* {
        margin-top: 0.5rem;
    }
</style>