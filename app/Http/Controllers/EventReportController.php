<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event; // Ajustar según el modelo de eventos
use App\Models\Places; // Ajustar según el modelo de eventos
use Carbon\Carbon;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;
use DatePeriod;
use DateTime;
use DateInterval;


class EventReportController extends Controller
{

    public function generateReport(Request $request)
    {
        //dd($request->date_range);

        $dateRange = $request->date_range;
        [$startDate, $endDate] = explode(' a ', $dateRange);

        // Convertir las fechas al formato d-m-Y
        $formattedStartDate = $startDate;
        $formattedEndDate = $endDate;

        // Obtener eventos desde la base de datos
        $eventos = Event::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();

        // Transformar el campo 'place' para obtener su nombre
        foreach ($eventos as $evento) {
            $evento->place = Places::getNameById($evento->place);
        }

        // Agrupar eventos por fecha
        $groupedEvents = $eventos->groupBy(function ($event) {
            return $event->date;
        });

        // Generar rango de fechas
        $period = new DatePeriod(
            new DateTime($startDate),
            new DateInterval('P1D'),
            (new DateTime($endDate))->modify('+1 day')
        );

        // Generar el PDF
        $pdf = Pdf::loadView('reports.events', [
            'period' => $period,
            'groupedEvents' => $groupedEvents,
            'startDate' => $formattedStartDate, // Enviar fecha formateada
            'endDate' => $formattedEndDate,     // Enviar fecha formateada
        ]);

        // Opcional: establecer el tamaño del papel y la orientación
        $pdf->setPaper('a4', 'landscape'); // Horizontal para un calendario

        // Descargar o mostrar el PDF
        return $pdf->stream('Calendario_de_Eventos.pdf');
    }


    public function reportView()
    {
        return view('reports.reports');
    }


}

