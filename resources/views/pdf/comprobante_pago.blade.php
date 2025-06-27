<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago - Spa Belleza</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #e91e63; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: center; }
        th { background-color: #f8bbd0; }
        img { width: 100px; margin-bottom: 20px; }
        p { font-size: 14px; }
    </style>
</head>
<body>
    <div style="text-align: center;">
        <img src="imagenes/logo.png" alt="Spa Belleza">
    </div>

    <h2 style="text-align: center;">Comprobante de Pago</h2>

    <p><strong>Cliente:</strong> {{ Auth::user()->name }}</p>
    <p><strong>Fecha de pago:</strong> {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}</p>
    
    <p><strong>Monto original:</strong> ${{ number_format(9000, 2) }}</p>
    <p><strong>Descuento aplicado:</strong> 15%</p>
    <p><strong>Monto total a pagar:</strong> ${{ number_format(7650, 2) }}</p>

    <p><strong>Forma de pago:</strong> {{ ucfirst($pago->forma_pago) }}</p>

    <h3>Turnos Reservados</h3>
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