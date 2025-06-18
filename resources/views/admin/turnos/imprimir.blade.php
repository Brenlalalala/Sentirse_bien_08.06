<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Turnos</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            color: #333;
        }

        @page {
            margin: 130px 50px;
        }

        header {
            position: fixed;
            top: -110px;
            left: 0;
            right: 0;
            text-align: center;
            padding: 10px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            text-align: center;
            padding: 10px;
            border-top: 1px solid #ccc;
            font-style: italic;
            font-size: 11px;
            color: #777;
        }

        h2 {
            font-family: 'Georgia', serif;
            font-size: 24px;
            margin-bottom: 4px;
            font-style: italic;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 5px 0;
        }

        ul li {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 140px;
        }

        th, td {
            border: 1px solid #999;
            padding: 8px 6px;
            text-align: left;
        }

        th {
            background-color: #f4e4ec;
            color: #333;
            font-style: italic;
            font-size: 13px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        img.logo {
            height: 70px;
            margin-bottom: 10px;
        }

        .fecha {
            font-size: 13px;
            margin-top: 4px;
            font-style: italic;
            color: #555;
        }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('imagenes/logo.png') }}" alt="Logo" class="logo">
        <h2>Listado de Turnos</h2>
        <p class="fecha">Fecha de generación: {{ now()->format('d/m/Y H:i') }}</p>
        <p style="margin: 5px 0 2px; font-weight: bold;">Filtros aplicados:</p>
        <ul>
            @if($request->filled('fecha_inicio') && $request->filled('fecha_fin'))
                <li>📅 Rango de fechas: {{ $fechaInicio }} a {{ $fechaFin }}</li>
            @endif
            @if($request->filled('servicio_id')) <li>🧴 Servicio ID: {{ $request->servicio_id }}</li> @endif
            @if($request->filled('estado')) <li>📌 Estado: {{ ucfirst($request->estado) }}</li> @endif
            @if($request->filled('profesional_id')) <li>👩‍⚕️ Profesional ID: {{ $request->profesional_id }}</li> @endif
            @if($request->filled('cliente_nombre')) <li>👤 Cliente: {{ $request->cliente_nombre }}</li> @endif
        </ul>
    </header>

    <footer>
        Sentirse Bien · Todos los derechos reservados
    </footer>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Profesional</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($turnos as $fecha => $turnosPorFecha)
                @foreach ($turnosPorFecha as $turno)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($turno->fecha)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($turno->hora)->format('H:i') }}</td>
                    <td>{{ $turno->user->name }}</td>
                    <td>{{ $turno->servicio->nombre }}</td>
                    <td>{{ $turno->profesional->name ?? 'No asignado' }}</td>
                    <td>{{ ucfirst($turno->estado) }}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
