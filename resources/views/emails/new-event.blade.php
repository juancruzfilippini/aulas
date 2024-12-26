<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Evento</title>
</head>
<body>
    <h1>Nuevo Evento Registrado</h1>
    <p><strong>Título:</strong> {{ $event['title'] }}</p>
    <p><strong>Fecha:</strong> {{ $event['date'] }}</p>
    <p><strong>Hora de inicio:</strong> {{ $event['start_time'] }}</p>
    <p><strong>Hora de fin:</strong> {{ $event['end_time'] }}</p>
    <p><strong>Lugar:</strong> {{ \App\Models\Places::getNameById($event['place']) }}</p>
    <p><strong>Solicitado por:</strong> {{ $event['requested_by'] }}</p>
</body>
</html>
