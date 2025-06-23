<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Turnos - PDF</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h2 { color: #e91e63; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #aaa; padding: 8px; }
        th { background-color: #f8bbd0; }
    </style>
</head>
<body>
    <h2>Turnos del Profesional {{ $profesional->name }}</h2>


    @foreach ($turnos as $key => $listaTurnos)
        @php [$fecha, $servicio] = explode('|', $key); @endphp
        <h4>{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }} - {{ $servicio }}</h4>

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
                        <td>{{ $turno->user->name ?? 'Sin cliente' }}</td>
                        <td>{{ ucfirst($turno->estado) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
