<!DOCTYPE html>
<html>
<head>
    <title>Comprobante de Pago Exitoso</title>
</head>
<body>
    <h1>¡Hola, {{ $nombre_cliente }}!</h1>
    <p>Tu pago se ha procesado con éxito. Puedes descargar tu comprobante de pago desde nuestra página web:</p>
    <p><strong>Visita la siguiente página para descargar el comprobante:</strong></p>
    <p><a href="{{ route('cliente.descargar_comprobante', ['pago_id' => $pago->id]) }}">Descargar Comprobante</a></p>
</body>
</html>
