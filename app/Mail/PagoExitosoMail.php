<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class PagoExitosoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pago;
    public $turnos;
    public $nombre;

    public function __construct($pago, $turnos, $nombre)
    {
        $this->pago = $pago;
        $this->turnos = $turnos;
        $this->nombre = $nombre;
    }

    public function build()
    {
        return $this->subject('Confirmación de pago y reserva de turnos')
                    ->view('emails.pagoexitoso');
    }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Pago Exitoso Mail',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
