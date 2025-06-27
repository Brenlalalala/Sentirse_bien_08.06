<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Pago - Spa Belleza</title>
    <style>
        body { font-family: Arial, sans-serif; background: #fff0f6; color: #333; }
        h1 { color: #e91e63; }
        p { font-size: 16px; }
        .footer { margin-top: 30px; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <h1>¡Gracias por tu pago!</h1>
    <p>Hola {{ $nombre }},</p>
    <p>Hemos recibido correctamente el pago de <strong>${{ number_format($pago->monto, 2) }}</strong> mediante <strong>{{ ucfirst($pago->forma_pago) }}</strong>.</p>
    <p>Adjuntamos el comprobante con los detalles de tus turnos reservados.</p>

    <p>Esperamos verte pronto en nuestro Spa de Belleza 💖</p>

    <div class="footer">
        <p>Spa Belleza - Tu bienestar, nuestra prioridad.</p>
    </div>
</body>
</html>
