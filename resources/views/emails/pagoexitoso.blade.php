<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago Exitoso</title>
</head>
<body>
    <h1>¡Gracias por tu pago, {{ $nombre_cliente }}!</h1>

<p>Tu pago ha sido procesado correctamente y tu reserva está confirmada.</p>

<p>Para descargar el comprobante de pago, por favor visita la sección <strong><a href="http://tusitio.com/mis-servicios">Mis Servicios</a></strong> en nuestro sitio web.</p>

<p><strong>Servicio:</strong> {{ $servicio }}</p>
<p><strong>Profesional:</strong> {{ $profesional }}</p>
<p><strong>Fecha del pago:</strong> {{ $fecha_pago }}</p>
<p><strong>Turno reservado:</strong> {{ $turno }}</p>
<p><strong>Monto abonado:</strong> {{ $monto }}</p>

<p>Si tienes alguna pregunta, no dudes en contactarnos.</p>

<p>¡Gracias por elegirnos!</p>
</body>
</html>
