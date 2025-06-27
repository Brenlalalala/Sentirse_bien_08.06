<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pagos por Profesional</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #aaa; padding: 6px; text-align: left; }
        h3 { background-color: #f3f3f3; padding: 5px; }
    </style>
</head>
<body>
    <h2>Pagos por Profesional ({{ $desde }} a {{ $hasta }})</h2>

    @foreach($pagos as $profesional => $lista)
        <h3>Profesional: {{ $profesional }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Forma de pago</th>
                    <th>Fecha de pago</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lista as $pago)
                    @foreach($pago->turnos as $turno)
                        <tr>
                            <td>{{ $pago->user->name }}</td>
                            <td>{{ $turno->servicio->nombre }}</td>
                            <td>${{ number_format($pago->monto, 2) }}</td>
                            <td>{{ $pago->descuento * 100 }}%</td>
                            <td>{{ ucfirst($pago->forma_pago) }}</td>
                            <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
