<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago - Spa Belleza</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h2 { color: #e91e63; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #aaa; padding: 8px; }
        th { background-color: #f8bbd0; }
    </style>
</head>
<body>
    <h2>Comprobante de Pago</h2>
    <p><strong>Cliente:</strong> {{ Auth::user()->name }}</p>
    <p><strong>Fecha de pago:</strong> {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}</p>
    <p><strong>Monto total:</strong> ${{ number_format($pago->monto, 2) }}</p>
    @if($pago->descuento > 0)
        <p><strong>Descuento aplicado:</strong> {{ $pago->descuento * 100 }}%</p>
    @endif
    <p><strong>Forma de pago:</strong> {{ ucfirst($pago->forma_pago) }}</p>

    <h2>Turnos Reservados</h2>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Servicio</th>
                <th>Profesional</th>
            </tr>
        </thead>
        <tbody>
            @foreach($turnos as $turno)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($turno->fecha)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($turno->hora)->format('H:i') }}</td>
                    <td>{{ $turno->servicio->nombre ?? 'N/A' }}</td>
                    <td>{{ $turno->profesional->name ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
