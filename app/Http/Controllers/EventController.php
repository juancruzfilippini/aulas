<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonInterval;

use App\Models\Event; // Importamos el modelo correcto
use App\Models\Places; // Importamos el modelo correcto

class EventController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $events = Event::whereNull('deleted_at') // Excluir eventos borrados lógicamente
            ->whereBetween('date', [$start, $end])
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'requested_by' => $event->requested_by,
                    'place' => is_numeric($event->place)
                        ? Places::getNameById($event->place)
                        : $event->place,
                    'date' => $event->date, // En formato YYYY-MM-DD
                    'start' => $event->date . 'T' . $event->start_time, // Formato ISO para FullCalendar
                    'end' => $event->date . 'T' . $event->end_time,
                    'start_time' => $event->start_time, // Enviamos el horario tal como está en la base
                    'end_time' => $event->end_time,
                ];
            });

        return response()->json($events);
    }





    public function createEvent()
    {
        $today = date('Y-m-d');
        $places = Places::all();
        $events = Event::whereNull('deleted_at')->get();

        // Modificar los eventos para obtener el nombre del lugar
        foreach ($events as $event) {
            if (is_numeric($event->place)) {
                $event->place = Places::getNameById($event->place) ?? 'No especificado';
            }
        }

        return view('events.register', compact('today', 'places', 'events'));
    }



    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'place' => 'required|string|max:255',
            'requested_by' => 'required|string|max:255',
            'recurrence_dates' => 'required|string', // Cadena de fechas separadas por coma
        ]);

        $recurrenceDates = explode(', ', $validated['recurrence_dates']);

        foreach ($recurrenceDates as $recurrenceDate) {
            $conflict = Event::where('date', $recurrenceDate)
                ->where('place', $validated['place'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhereRaw('? BETWEEN start_time AND end_time', [$validated['start_time']])
                        ->orWhereRaw('? BETWEEN start_time AND end_time', [$validated['end_time']]);
                })
                ->exists();

            if ($conflict) {
                return redirect()->back()
                    ->withErrors(['error' => "Conflicto detectado en la fecha $recurrenceDate para el lugar seleccionado."])
                    ->with('error', "Conflicto detectado en la fecha $recurrenceDate para el lugar seleccionado.")
                    ->withInput(); // Retorna los datos ingresados previamente
            }

            Event::create([
                'title' => $validated['title'],
                'date' => $recurrenceDate,
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'place' => $validated['place'],
                'requested_by' => $validated['requested_by'],
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Eventos registrados exitosamente.');
    }



    public function manageEvent(Request $request)
    {
        $month = $request->input('month', now()->month); // Obtiene el mes del request o usa el mes actual
        $year = $request->input('year', now()->year);   // Obtiene el año del request o usa el año actual

        // Filtrar eventos por mes, año y que no estén borrados lógicamente
        $events = Event::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->whereNull('deleted_at') // Excluir eventos borrados lógicamente
            ->paginate(100);

        // Actualiza el atributo `place` con el nombre correspondiente al ID
        foreach ($events as $event) {
            $event->place = Places::getNameById($event->place);
        }

        return view('events.manage', compact('events', 'month', 'year'));
    }



    public function bulkUpdate(Request $request)
    {
        // Validar que haya eventos seleccionados
        $request->validate([
            'selected_events' => 'required|array', // Validar que se envíen eventos seleccionados
        ]);

        // Realizar el borrado lógico de los eventos seleccionados
        Event::whereIn('id', $request->selected_events)
            ->update(['deleted_at' => now()]);

        return redirect()->route('events.manage')->with('success', 'Los eventos seleccionados han sido eliminados correctamente.');
    }

    public function update(Request $request, $id)
    {
        // Buscar el evento existente
        $event = Event::findOrFail($id);

        // Validar los datos ingresados
        $request->validate([
            'title' => 'required|string|max:255',
            'requested_by' => 'required|string|max:255',
            'place' => 'required|integer',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // Verificar conflictos con otros eventos
        $conflictingEvent = Event::where('id', '!=', $id) // Excluir el evento actual
            ->where('place', $request->place)
            ->where('date', $request->date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->first();

        // Si hay un conflicto, redirigir con un error
        if ($conflictingEvent) {
            return redirect()->back()->withErrors([
                'conflict' => 'Ya existe otro evento programado en este lugar, fecha y horario.',
            ])->withInput();
        }

        // Actualizar los datos del evento
        $event->update($request->all());

        // Redirigir con un mensaje de éxito
        return redirect()->route('dashboard')->with('success', 'Evento actualizado con éxito.');
    }



    public function filterByMonth(Request $request)
    {
        return view('events.manage', compact('events', 'month', 'year'));
    }

    public function edit($id)
    {
        //dd($id);
        $event = Event::findOrFail($id);
        $events = Event::whereNull('deleted_at')->get();
        // Cargar datos adicionales necesarios para la edición
        $places = Places::all(); // Ejemplo de relación para los lugares disponibles

        // Retornar la vista con los datos necesarios
        return view('events.edit', compact('event', 'places', 'events'));
    }


    public function destroy($id)
    {
        Event::where('id', $id)
            ->update(['deleted_at' => now()]);

        return redirect()->route('dashboard')->with('success', 'Evento eliminado correctamente.');
    }

}