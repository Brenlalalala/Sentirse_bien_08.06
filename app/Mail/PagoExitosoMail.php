<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class PagoExitosoMail extends Mailable
{
    public $nombre_cliente;
    public $servicio;
    public $profesional;
    public $fecha_pago;
    public $turno;
    public $monto;
    public $pdf;  // Añadimos el PDF como parámetro adicional

    // Constructor con 7 parámetros
    public function __construct($nombre_cliente, $servicio, $profesional, $fecha_pago, $turno, $monto, $pdf)
    {
        $this->nombre_cliente = $nombre_cliente;
        $this->servicio = $servicio;
        $this->profesional = $profesional;
        $this->fecha_pago = $fecha_pago;
        $this->turno = $turno;
        $this->monto = $monto;
        $this->pdf = $pdf;  // Almacenar el PDF
    }

    // Función de construcción del correo
    public function build()
    {
        return $this->view('emails.pago_exitoso')
                    ->subject('Comprobante de pago exitoso')
                    ->attachData($this->pdf, 'comprobante_pago.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
