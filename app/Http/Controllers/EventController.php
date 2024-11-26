<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonInterval;

use App\Models\Event; // Importamos el modelo correcto

class EventController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $events = Event::whereBetween('date', [$start, $end])

            ->get()
            ->map(function ($event) {
                return [
                    'title' => $event->title,
                    'requested_by' => $event->requested_by,
                    'members' => $event->members,
                    'place' => $event->place,
                    'date' => $event->date, // En formato YYYY-MM-DD
                    'start' => $event->date . 'T' . $event->start_time, // Formato ISO para FullCalendar
                    'end' => $event->date . 'T' . $event->end_time,
                    'start_time' => $event->start_time, // Enviamos el horario tal como está en la base
                    'end_time' => $event->end_time,
                    'reason' => $event->reason,
                ];
            });

        return response()->json($events);
    }




    public function createEvent()
    {
        $today = date('Y-m-d');

        return view('events.register', compact('today'));
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'place' => 'required|string|max:255',
            'requested_by' => 'required|string|max:255',
            'members' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'recurrence_dates' => 'required|string', // Valida que sea un string
        ]);

        // Convertir el string de fechas a un array
        $recurrenceDates = explode(', ', $validated['recurrence_dates']);

        foreach ($recurrenceDates as $recurrenceDate) {
            Event::create([
                'title' => $validated['title'],
                'date' => $recurrenceDate, // Asigna la fecha convertida
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'place' => $validated['place'],
                'requested_by' => $validated['requested_by'],
                'members' => $validated['members'],
                'reason' => $validated['reason'],
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Eventos registrados exitosamente.');
    }

    public function manageEvent(Request $request)
    {
        $events = Event::orderBy('id', 'asc')->paginate(10); // Paginación opcional
        return view('events.manage', compact('events'));
    }

    public function bulkUpdate(Request $request)
    {
        // Validar la entrada del formulario
        $request->validate([
            'selected_events' => 'required|array',   // Eventos seleccionados
            'bulk_action' => 'required|string',       // Acción seleccionada
            'new_date' => 'nullable|date',            // Fecha nueva (solo si aplica)
            'new_start_time' => 'nullable|date_format:H:i', // Hora de inicio nueva (solo si aplica)
            'new_end_time' => 'nullable|date_format:H:i',   // Hora de fin nueva (solo si aplica)
            'new_place' => 'nullable|string',          // Lugar nuevo (solo si aplica)
            'new_members' => 'nullable|integer',      // Miembros nuevos (solo si aplica)
            'new_reason' => 'nullable|string',         // Razón nueva (solo si aplica)
            'new_requested_by' => 'nullable|string',   // Solicitante nuevo (solo si aplica)
        ]);

        // Procesar según la acción seleccionada
        switch ($request->bulk_action) {
            case 'update_date':
                // Actualizar la fecha de los eventos seleccionados
                Event::whereIn('id', $request->selected_events)
                    ->update(['date' => $request->new_date]);
                break;

            case 'update_time':
                // Actualizar las horas de inicio y fin de los eventos seleccionados
                Event::whereIn('id', $request->selected_events)
                    ->update([
                        'start_time' => $request->new_start_time,
                        'end_time' => $request->new_end_time,
                    ]);
                break;

            case 'update_place':
                // Actualizar el lugar de los eventos seleccionados
                Event::whereIn('id', $request->selected_events)
                    ->update(['place' => $request->new_place]);
                break;

            case 'update_members':
                // Actualizar el número de miembros de los eventos seleccionados
                Event::whereIn('id', $request->selected_events)
                    ->update(['members' => $request->new_members]);
                break;

            case 'update_reason':
                // Actualizar la razón de los eventos seleccionados
                Event::whereIn('id', $request->selected_events)
                    ->update(['reason' => $request->new_reason]);
                break;

            case 'update_requested_by':
                // Actualizar el solicitante de los eventos seleccionados
                Event::whereIn('id', $request->selected_events)
                    ->update(['requested_by' => $request->new_requested_by]);
                break;

            default:
                // Acción no válida
                return redirect()->route('events.manage')->with('error', 'Acción no válida.');
        }

        // Redirigir con mensaje de éxito
        return redirect()->route('events.manage')->with('success', 'Los eventos seleccionados han sido actualizados.');
    }


}
