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
                                <div class="event-container">
                                    <div class="event-title">${info.event.title}</div>
                                    <div class="event-time">${formatTime(startTime)} - ${formatTime(endTime)}</div>
                                </div>
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
                        return 'No especificado'; // Devuelve un valor vacío si no hay tiempo válido
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
                    document.getElementById('eventMembers').textContent = info.event.extendedProps.members || 'No especificadogi';
                    document.getElementById('eventReason').textContent = info.event.extendedProps.reason || 'No especificada';

                    var modal = new bootstrap.Modal(document.getElementById('eventModal'));
                    modal.show();
                }
            });
            calendar.render();
        });




    </script>

    <style>
        .fc-daygrid-event-harness {
            height: auto !important;
            /* Permite que el contenedor del evento tenga altura dinámica */
            min-height: 0 !important;
            /* Elimina restricciones de altura mínima */
            overflow: visible !important;
            /* Asegura que el contenido sea visible */

        }

        .fc-daygrid-event {
            border-radius: 15px !important;
        }





        /* Estilo para los eventos en el calendario */
        .event-container {
            position: relative;
            /* Asegura el posicionamiento dentro de la tarjeta */
            width: 100%;
            /* Toma todo el ancho disponible */
            height: 100%;
            /* Asegura que los elementos internos no desborden */
            overflow: hidden;
            /* Oculta cualquier contenido que salga del contenedor */
            text-align: center;
            /* Centra horizontalmente el contenido */
            display: flex;
            /* Usa Flexbox para centrar los elementos */
            flex-direction: column;
            /* Asegura que el título y el tiempo estén en columnas */
            justify-content: center;
            /* Centra el contenido verticalmente */
            align-items: center;
            /* Centra el contenido horizontalmente */
        }


        .fc-event {
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 2px;
            color: white;
        }

        .fc-event.event-general {
            background-color: #FF9999;
            border-color: lightcoral;
            over
            /* Azul claro */
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
            text-overflow: ellipsis;
        }


        .event-title {
            font-weight: bold;
            white-space: nowrap;
            /* Evita que el texto se divida en varias líneas */
            overflow: hidden;
            /* Oculta el texto que excede el tamaño del contenedor */
            text-overflow: ellipsis;
            /* Muestra los puntos suspensivos al final del texto */
            width: 100%;
            /* Asegura que el comportamiento se aplique dentro del contenedor */
            text-align: center;
            /* Centra horizontalmente el texto */
        }


        .event-general {
            display: flex;
            flex-direction: column;
            justify-content: center;
            /* Centra verticalmente */
            align-items: center;
            /* Centra horizontalmente */
            height: 100%;
            /* Asegura que ocupe todo el espacio del evento */
            text-align: center;
            color: white;
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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

        .fc-daygrid-event {
            border-radius: 8px !important;
            /* Bordes redondeados */
            transition: transform 0.2s ease, background-color 0.2s ease;
            /* Animación suave */
        }


        .fc-daygrid-event:hover {
            transform: scale(1.05);
            /* Aumenta ligeramente el tamaño del evento */
            background-color: pink;
            /* Cambia el color de fondo al hacer hover */
            cursor: pointer;
            /* Cambia el cursor a una mano para indicar clickeable */
            color: #fff;
            /* Cambia el color del texto si es necesario */
        }

        .fc-day-today {
            background-color: #FFCCCC;
            /* Rojo claro */
            border: 1px solid #FF9999;
            /* Borde opcional para destacar más */
        }
    </style>

</x-app-layout>