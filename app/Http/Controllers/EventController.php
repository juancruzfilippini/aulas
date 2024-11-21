<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        //dd($request->all());
        $validatedData = $request->validate([
            'title' => 'required|string|max:255', // Obligatorio y debe ser un string con máximo 255 caracteres
            'date' => 'required|date', // Obligatorio y debe ser una fecha válida
            'start_time' => 'required|date_format:H:i', // Obligatorio, formato de hora (24 horas)
            'end_time' => 'required|date_format:H:i|after:start_time', // Obligatorio, formato de hora y debe ser después de `start_time`
            'place' => 'required|string|max:255', // Obligatorio, máximo 255 caracteres
            'requested_by' => 'required|string|max:255', // Obligatorio, máximo 255 caracteres
            'members' => 'required|integer', 
            'reason' => 'required|string|max:500', // Obligatorio, máximo 500 caracteres
        ]);

        $event = new Event();
        $event->title = $validatedData['title'];
        $event->date = $validatedData['date'];
        $event->start_time = $validatedData['start_time']; // Guardar con el nuevo formato
        $event->end_time = $validatedData['end_time'];     // Guardar con el nuevo formato
        $event->place = $validatedData['place'];
        $event->requested_by = $validatedData['requested_by'];
        $event->reason = $validatedData['reason'];
        $event->members = $validatedData['members'];
        $event->save();

        // Redirigir con un mensaje de éxito
        return redirect()->route('dashboard')->with('success', 'El evento se ha registrado exitosamente.');
    }

}
