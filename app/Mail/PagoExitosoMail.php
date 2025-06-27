<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class PagoExitosoMail extends Mailable
{
    public $nombre_cliente;

    // Constructor que solo recibe el nombre del cliente
    public function __construct($nombre_cliente)
    {
        $this->nombre_cliente = $nombre_cliente;
    }

    // Función para construir el correo
    public function build()
    {
        return $this->view('emails.pagoexitoso') // Asegúrate de crear la vista
                    ->subject('Comprobante de pago exitoso')
                    ->with([
                        'nombre_cliente' => $this->nombre_cliente
                    ]);
    }

    
}

