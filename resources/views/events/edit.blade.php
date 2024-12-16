<x-app-layout>
    <div class="mt-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="container">
            @if ($errors->has('conflict'))
                <div class="alert alert-danger">
                    {{ $errors->first('conflict') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg">
                <div class="modal-header text-white p-6 rounded-top" style="background-color: #475e75;">
                    <h5 class="modal-title" id="eventModalLabel">
                        <i class="fa-solid fa-calendar-check me-2"></i> Editar evento
                    </h5>
                </div>

                <div class="p-4">
                    <form action="{{ route('eventos.update', $event->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">Título</label>
                            <input type="text" name="title" id="title" class="form-control" 
                                value="{{ $event->title }}" placeholder="Título del evento" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="requested_by" class="form-label fw-bold">Solicitado por</label>
                                <input type="text" name="requested_by" id="requested_by" class="form-control" 
                                    value="{{ $event->requested_by }}" placeholder="Nombre del solicitante" required>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="place" class="form-label fw-bold">Lugar</label>
                                <select name="place" id="place" class="form-select border-black rounded-0 h-10" required>
                                    @foreach($places as $place)
                                        <option value="{{ $place->id }}" {{ $event->place == $place->id ? 'selected' : '' }}>
                                            {{ $place->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label for="date" class="form-label fw-bold">Fecha</label>
                                <input type="date" name="date" id="date" class="form-control" 
                                    value="{{ $event->date }}" required>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="start_time" class="form-label fw-bold">Hora de Inicio</label>
                                <input type="time" name="start_time" id="start_time" class="form-control" 
                                    value="{{ $event->start_time }}" required>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label for="end_time" class="form-label fw-bold">Hora de Fin</label>
                                <input type="time" name="end_time" id="end_time" class="form-control" 
                                    value="{{ $event->end_time }}" required>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn text-white" style="background-color: #475e75;">
                                <i class="fa-solid fa-save me-2"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<SCRIpt>
    let flatpickrInstance = flatpickr("#date", {
        locale: 'es',
        dateFormat: 'Y-m-d',
    });
</SCRIpt>