<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Turnos PDF</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background-color: #fce4ec; }
        h2, h3 { color: #c2185b; }
    </style>
</head>
<body>
    <h2>Turnos</h2>
    <p><strong>Desde:</strong> {{ $fechaInicio ?? 'N/A' }} <strong>Hasta:</strong> {{ $fechaFin ?? 'N/A' }}</p>

    @foreach ($turnos as $servicio => $listaTurnos)
        <h3>{{ $servicio }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($listaTurnos as $turno)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($turno->hora)->format('H:i') }}</td>
                        <td>{{ $turno->user->name }}</td>
                        <td>{{ ucfirst($turno->estado) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
