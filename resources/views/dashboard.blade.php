
<x-app-layout>
    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
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
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade animate__animated animate__fadeInDown" id="eventModal" tabindex="-1"
        aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header text-white" style="background: #475e75">
                    <h5 class="modal-title" id="eventModalLabel">
                        <input type="hidden" id="eventId">
                        <i class="fa-solid fa-calendar-check me-2"></i> Detalles del Evento
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <!-- Contenido del modal -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong><i class="fa-solid fa-heading me-2"></i>Título:</strong> <span
                                        id="eventTitle"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fa-solid fa-user me-2"></i>Solicitado por:</strong> <span
                                        id="eventRequestedBy"></span></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong><i class="fa-solid fa-map-marker-alt me-2"></i>Lugar:</strong> <span
                                        id="eventPlace"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fa-solid fa-calendar-day me-2"></i>Fecha:</strong> <span
                                        id="eventDate"></span></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong><i class="fa-solid fa-clock me-2"></i>Hora de inicio:</strong> <span
                                        id="eventStartTime"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fa-solid fa-clock me-2"></i>Hora de fin:</strong> <span
                                        id="eventEndTime"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="editEventButton" class="btn text-white" style="background: #475e75"
                        data-route="{{ route('events.edit', ':id') }}">
                        <i class="fa-solid fa-edit me-2"></i>Editar Evento
                    </a>
                    <button type="button" id="deleteEventButton" class="btn text-white" style="background: #ad1a1a">
                        <i class="fa-solid fa-trash me-2"></i>Eliminar Evento
                    </button>
                </div>
            </div>
        </div>
    </div>






    <!-- Estilos de FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

    <!-- Scripts de FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>

    <script>
        document.getElementById('deleteEventButton').addEventListener('click', function () {
            const eventId = document.getElementById('eventId').value;
            const deleteRoute = "{{ route('events.destroy', ':id') }}".replace(':id', eventId);

            Swal.fire({
                title: '¿Está seguro?',
                text: "¡No podrá recuperar este evento!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar solicitud de eliminación
                    fetch(deleteRoute, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => {
                            if (response.ok) {
                                Swal.fire(
                                    '¡Eliminado!',
                                    'El evento ha sido eliminado.',
                                    'success'
                                ).then(() => {
                                    // Cerrar el modal y refrescar la página
                                    $('#eventModal').modal('hide');
                                    location.reload();
                                });
                            } else {
                                $('#eventModal').modal('hide');
                                    location.reload();
                            }
                        })
                        .catch(error => {
                            Swal.fire(
                                'Error',
                                'Hubo un problema al intentar eliminar el evento.',
                                'error'
                            );
                        });
                }
            });
        });



        // Supongamos que la URL base para editar es '/events/edit/'
        document.addEventListener('DOMContentLoaded', () => {
            const editEventButton = document.getElementById('editEventButton');
            const eventIdInput = document.getElementById('eventId');

            document.getElementById('eventModal').addEventListener('show.bs.modal', function () {
                const eventId = eventIdInput.value; // Obtener el ID del evento
                const routeTemplate = editEventButton.dataset.route; // Obtener la plantilla de la ruta

                if (eventId) {
                    // Reemplazar ":id" con el valor real del evento
                    const editUrl = routeTemplate.replace(':id', eventId);
                    editEventButton.href = editUrl;
                } else {
                    console.error('No se encontró el ID del evento.');
                    editEventButton.href = '#'; // Evitar URLs inválidas
                }
            });
        });




        document.addEventListener('DOMContentLoaded', () => {
            const modalHeader = document.getElementById('modalHeader');
            const eventPlace = document.getElementById('eventPlace');

            // Función para actualizar el color del encabezado del modal
            function updateHeaderColor() {
                const place = eventPlace.textContent.trim().toLowerCase();

                var modalHeader = document.querySelector('#eventModal .modal-header');

                if (modalHeader) {
                    if (place === 'auditorio') {
                        // Cambia el color para eventos en el auditorio
                        modalHeader.style.backgroundColor = '#ad1a1a';
                    } else {
                        // Mantén el color predeterminado para otros lugares
                        modalHeader.style.backgroundColor = '#475e75';
                    }
                } else {
                    console.error('El elemento modalHeader no se encontró en el DOM.');
                }

            }

            // Llama esta función cada vez que se abre el modal
            const modal = document.getElementById('eventModal');
            modal.addEventListener('show.bs.modal', updateHeaderColor);
        });


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
                    //console.log(info.event.extendedProps.place);

                    var startTime = info.event.extendedProps.start_time || 'No especificado';
                    var endTime = info.event.extendedProps.end_time || 'No especificado';
                    var place = info.event.extendedProps.place || 'No especificado';
                    return {
                        html: `
                                <div class="event-container">
                                    <div class="event-title">${info.event.title}</div>
                                    <div class="event-place" style="text-decoration: underline;">${place}</div>
                                    <div class="event-time">${formatTime(startTime)} - ${formatTime(endTime)}</div>
                                </div>
                            `
                    };

                    // Función para formatear el tiempo
                    function formatTime(time) {
                        if (time && typeof time === 'string') {
                            // Divide la cadena en partes y retorna solo HH:mm
                            const [hours, minutes] = time.split(':');
                            return `${hours}:${minutes}`;
                        }
                        return 'No especificado'; // Devuelve un valor vacío si no hay tiempo válido
                    }

                },

                eventClassNames: function (info) {
                    // Verifica si el lugar es "auditorio" (5)ç
                    //console.log(info.event.extendedProps.place);

                    if (info.event.extendedProps.place === 'AUDITORIO') {
                        return ['event-auditorio']; // Clase específica para eventos en el auditorio

                    }

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

                    // Almacena el ID del evento en el campo oculto
                    document.getElementById('eventId').value = info.event.id;

                    // Cambiar el color de fondo del encabezado del modal
                    var modalHeader = document.querySelector('#eventModal .modal-header');
                    if (info.event.extendedProps.place === 'AUDITORIO') {
                        modalHeader.style.background = '#ad1a1a';
                    } else {
                        modalHeader.style.background = '#475e75';
                    }

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
            background-color: #2C3E50;
            border-color: #1E2B37;

        }


        .fc-day {
            background-color: #F9F9F9;
        }

        .event-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-overflow: ellipsis;
        }


        .event-title {
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
            text-align: center;
        }


        .event-general {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            text-align: center;
            color: white;
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

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

        .fc-col-header-cell {
            color: #000000;
            text-decoration: none;
            font-weight: bold;

        }

        .fc-col-header-cell-cushion {
            color: #000000;
            text-decoration: none;
            font-weight: bold;
            text-align: center;

        }

        #calendar {
            max-width: 90%;
            margin: 0 auto
        }

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
            transition: transform 0.2s ease, background-color 0.2s ease;
        }


        .fc-daygrid-event:hover {
            transform: scale(1.05);
            background-color: #475e75;
            cursor: pointer;
            color: #fff;
        }

        .fc-day-today {
            background-color: #FFFADF;
        }

        .event-auditorio {
            background-color: #ad1a1a !important;
            color: white;
            border: none;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .event-auditorio:hover {
            transform: scale(1.05);
            background-color: #aa4949 !important;
            cursor: pointer;
            color: #fff;
        }
    </style>

</x-app-layout>