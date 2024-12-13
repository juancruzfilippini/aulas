<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Eventos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .calendar-container {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .day-column {
            display: table-cell;
            border: 2px solid black;
            /* Borde negro para cada día */
            text-align: left;
            background-color: #f9f9f9;
            padding: 10px;
            vertical-align: top;
        }

        .day-header {
            background-color: #f4f4f4;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }

        .event {
            background-color: #e3f2fd;
            margin: 5px 0;
            padding: 5px;
            border: 1px solid #555;
            /* Borde gris oscuro para cada evento */
            border-radius: 3px;
            font-size: 12px;
            text-align: left;
        }

        .empty {
            color: #999;
            font-style: italic;
            text-align: center;
            padding: 10px;
        }

        h1,
        p {
            text-align: center;
        }
    </style>


</head>

<body>
    <h1>Calendario de Eventos</h1>
    <p>{{ $startDate }} --- {{ $endDate }}</p>

    <div class="calendar-container">
        @foreach ($period as $date)
                @php
                    $formattedDate = $date->format('Y-m-d');
                    $events = $groupedEvents[$formattedDate] ?? [];
                @endphp
                <div class="day-column">
                    <div class="day-header">{{ $date->format('d-m-Y') }}</div>
                    @if (empty($events))
                        <div class="empty">Sin eventos</div>
                    @else
                        @foreach ($events as $event)
                            <div class="event">
                                <strong>{{ $event['title'] }}</strong><br>
                                <small>{{ $event['place'] }}</small><br>
                                <small>{{ $event['start_time'] }} - {{ $event['end_time'] }}</small>
                            </div>
                        @endforeach
                    @endif
                </div>
        @endforeach
    </div>


</body>

</html>