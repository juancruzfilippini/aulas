<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Calendario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Detalles del Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Título:</strong> <span id="eventTitle"></span></p>
                    <p><strong>Solicitado por:</strong> <span id="eventRequestedBy"></span></p>
                    <p><strong>Lugar:</strong> <span id="eventPlace"></span></p>
                    <p><strong>Fecha:</strong> <span id="eventDate"></span></p>
                    <p><strong>Hora de inicio:</strong> <span id="eventStartTime"></span></p>
                    <p><strong>Hora de fin:</strong> <span id="eventEndTime"></span></p>
                    <p><strong>Participantes:</strong> <span id="eventMembers"></span></p>
                    <p><strong>Razón:</strong> <span id="eventReason"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Estilos de FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

    <!-- Scripts de FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>


    <!-- Script de inicialización del calendario -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek',
                },
                events: '/aulas/public/api/events',
                eventContent: function (info) {
                    var startTime = info.event.extendedProps.start_time || 'No especificado';
                    var endTime = info.event.extendedProps.end_time || 'No especificado';
                    var place = info.event.extendedProps.place || 'No especificado';
                    return {
                        html: `
                                <table class="event-table">
                                    <tr>
                                        <td class="event-title">${info.event.title}</td>
                                        <td class="event-times">${formatTime(startTime)}-${formatTime(endTime)}</td>
                                    </tr>
                                </table>
                            `
                    };

                    // Función para formatear el tiempo
                    function formatTime(time) {
                        console.log(info.event);
                        if (time && typeof time === 'string') {
                            // Divide la cadena en partes y retorna solo HH:mm
                            const [hours, minutes] = time.split(':');
                            return `${hours}:${minutes}`;
                        }
                        return ''; // Devuelve un valor vacío si no hay tiempo válido
                    }

                },

                eventClassNames: function (info) {
                    // Cambia el color según el tipo de evento
                    switch (info.event.extendedProps.event_type) {
                        case 'important':
                            return ['event-important'];
                        case 'urgent':
                            return ['event-urgent'];
                        case 'completed':
                            return ['event-completed'];
                        default:
                            return ['event-general'];
                    }
                },
                eventClick: function (info) {
                    var date = new Date(info.event.start);
                    var formattedDate = new Intl.DateTimeFormat('es-ES', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                    }).format(date);
                    var startTime = info.event.extendedProps.start_time || 'No especificado';
                    var endTime = info.event.extendedProps.end_time || 'No especificado';

                    document.getElementById('eventTitle').textContent = info.event.title;
                    document.getElementById('eventRequestedBy').textContent = info.event.extendedProps.requested_by || 'No especificado';
                    document.getElementById('eventPlace').textContent = info.event.extendedProps.place || 'No especificado';
                    document.getElementById('eventDate').textContent = formattedDate;
                    document.getElementById('eventStartTime').textContent = startTime;
                    document.getElementById('eventEndTime').textContent = endTime;
                    document.getElementById('eventMembers').textContent = info.event.extendedProps.members || 'No especificado';
                    document.getElementById('eventReason').textContent = info.event.extendedProps.reason || 'No especificada';

                    var modal = new bootstrap.Modal(document.getElementById('eventModal'));
                    modal.show();
                }
            });
            calendar.render();
        });




    </script>

    <style>
        /* Estilo para los eventos en el calendario */
        .fc-event {
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            padding: 2px;
            color: white;
        }

        .fc-event.event-general {
            background-color: #;
            /* Azul claro */
        }

        .fc-event.event-important {
            background-color: #F5A623;
            /* Naranja */
        }

        .fc-event.event-urgent {
            background-color: #D0021B;
            /* Rojo */
        }

        .fc-event.event-completed {
            background-color: #7ED321;
            /* Verde */
        }

        .fc-day {
            background-color: #F9F9F9;
            /* Gris claro */
        }

        /* Contenedor principal del evento */
        .event-content {
            display: flex;
            /* Usar flexbox para la alineación */
            justify-content: space-between;
            /* Espaciar elementos a los extremos */
            align-items: center;
            /* Alinear verticalmente al centro */
            white-space: nowrap;
            /* Evitar que el contenido se divida en varias líneas */
            overflow: hidden;
            /* Ocultar contenido que exceda */
            text-overflow: ellipsis;
            /* Mostrar "..." si es demasiado largo */
        }

        /* Título del evento */
        /* Estilo general de la tabla */
        .event-table {
            width: 100%;
            /* Ocupa todo el ancho del evento */
            border-collapse: collapse;
            /* Quita los bordes internos */
            table-layout: fixed;
            /* Asegura que las columnas tengan un ancho fijo */
        }

        /* Estilo de las celdas */
        .event-table td {
            padding: 2px 5px;
            /* Espaciado interno */
            vertical-align: middle;
            /* Centrar verticalmente */
            white-space: nowrap;
            /* Evitar el salto de línea */
            overflow: hidden;
            /* Ocultar contenido extra */
            text-overflow: ellipsis;
            /* Mostrar "..." si el texto excede */
        }

        /* Columna de título */
        .event-table .event-title {
            text-align: left;
            /* Alinear texto a la izquierda */
            font-weight: bold;
            /* Negrita */
            font-size: 12px;
            /* Tamaño de texto */
            color: white;
            /* Color del texto */
        }

        /* Columna de horario */
        .event-table .event-times {
            text-align: right;
            /* Alinear texto a la derecha */
            font-size: 11px;
            /* Tamaño de texto */
            color: white;
            /* Color del texto */
        }



        /* Cambiar color de los números de los días */
        .fc-daygrid-day-number {
            color: #000000;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
        }

        .fc-daygrid-day-top {
            color: #000000;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            justify-content: center;
            align-items: center;
        }

        /* Cambiar color de los nombres de los días (cabecera) */
        .fc-col-header-cell {
            color: #000000;
            text-decoration: none;
            font-weight: bold;
        }

        .fc-col-header-cell-cushion {
            color: #000000;
            /* Negro */
            text-decoration: none;
            /* Quitar subrayado */
            font-weight: bold;
            /* Opcional: Hacerlos más destacados */
            text-align: center;

        }

        /* Ajustar el ancho del contenedor del calendario */
        #calendar {
            max-width: 90%;
            /* Cambia el ancho a un porcentaje más amplio */
            margin: 0 auto;
            /* Centrar el calendario */
        }

        .fc-day-today {
            background-color: #FFCCCC;
            /* Rojo claro */
            border: 1px solid #FF9999;
            /* Borde opcional para destacar más */
        }

        /* Cambiar el color del texto del número del día actual (si es necesario) */
        

        /* Centrar los números de los días en el calendario */
        .fc-daygrid-day-number {
            display: flex;
            text-decoration: none;
            justify-content: center;
            align-items: center;
            height: 100%;
            font-weight: bold;
            font-size: 14px;
            color: #000000;
            text-align: center;
        }
    </style>

</x-app-layout>