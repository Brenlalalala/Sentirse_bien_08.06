<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function responder(Request $request)
    {
        $mensaje = strtolower($request->input('mensaje'));

        // Saludos
        if (str_contains($mensaje, 'hola')) {
            return response()->json(['respuesta' => '¡Hola! ¿En qué puedo ayudarte?']);
        }

        // Horarios
        if (str_contains($mensaje, 'horario') || str_contains($mensaje, 'horarios')) {
            return response()->json(['respuesta' => 'Nuestros horarios son de lunes a sábado de 9:00 a 18:00 horas.']);
        }

        // Servicios
        if (str_contains($mensaje, 'servicio') || str_contains($mensaje, 'tratamiento')) {
            return response()->json([
                'respuesta' => 'Ofrecemos masajes, limpieza facial, tratamientos corporales, y más. ¿Querés ver el listado completo?'
            ]);
        }

        // Confirmación para ver listado de servicios
        if (
            str_contains($mensaje, 'si') ||
            str_contains($mensaje, 'sí quiero') ||
            str_contains($mensaje, 'si quiero') ||
            str_contains($mensaje, 'ver listado') ||
            str_contains($mensaje, 'listado completo') ||
            str_contains($mensaje, 'quiero ver') ||
            str_contains($mensaje, 'quiero el listado') ||
            str_contains($mensaje, 'detalles de servicios') ||
            str_contains($mensaje, 'mostrar servicios')
        ) {
            return response()->json([
                'respuesta' => 'Genial 😊 Podés ver el listado completo de servicios en nuestra sección 👉 <a href="/servicios" target="_blank">Servicios</a>. Ahí vas a encontrar todos los tratamientos con descripción y precios.'
            ]);
        }

        // Precios
        if (str_contains($mensaje, 'precio') || str_contains($mensaje, 'costo')) {
            return response()->json(['respuesta' => 'Los precios varían según el tratamiento. Consultalos en la sección Servicios o escribinos por WhatsApp.']);
        }

        // Turnos o reservas
        if (str_contains($mensaje, 'turno') || str_contains($mensaje, 'reserva')) {
            return response()->json(['respuesta' => 'Podés reservar desde tu panel de cliente o por WhatsApp.']);
        }

        // Cancelación o modificación de turno
        if (str_contains($mensaje, 'cancelar') || str_contains($mensaje, 'modificar')) {
            return response()->json(['respuesta' => 'Podés cancelar o modificar tu turno desde tu panel de cliente.']);
        }

        // Formas de pago
        if (str_contains($mensaje, 'forma de pago') || str_contains($mensaje, 'pagos')) {
            return response()->json(['respuesta' => 'Aceptamos efectivo, tarjeta de crédito, débito y transferencias.']);
        }

        // Contacto
        if (str_contains($mensaje, 'contacto') || str_contains($mensaje, 'email')) {
            return response()->json(['respuesta' => 'Podés contactarnos al 362-1234567 o a info@sentirsebien.com']);
        }
        if (str_contains($mensaje, 'whatsapp') || str_contains($mensaje, 'email')) {
            return response()->json(['respuesta' => 'Podés contactarnos al 362-1234567 o a info@sentirsebien.com']);
        }
        if (str_contains($mensaje, 'numero') || str_contains($mensaje, 'email')) {
            return response()->json(['respuesta' => 'Podés contactarnos al 362-1234567 o a info@sentirsebien.com']);
        }

        // Despedida
        if (str_contains($mensaje, 'gracias')) {
            return response()->json(['respuesta' => '¡De nada! Si necesitás algo más, no dudes en preguntar.']);
        }

        if (str_contains($mensaje, 'adiós') || str_contains($mensaje, 'chau')) {
            return response()->json(['respuesta' => '¡Hasta luego! Que tengas un buen día.']);
        }

        // Ayuda
        if (str_contains($mensaje, 'ayuda') || str_contains($mensaje, 'soporte')) {
            return response()->json(['respuesta' => 'Si necesitás ayuda, podés contactarnos al 362-1234567 o escribirnos por WhatsApp.']);
        }

        // Ubicación
        if (str_contains($mensaje, 'ubicacion') || str_contains($mensaje, 'dirección')) {
            return response()->json(['respuesta' => 'Estamos ubicados en French 414, Ciudad de Resistencia.']);
        }

        // Duración de los tratamientos
        if (str_contains($mensaje, 'duracion') || str_contains($mensaje, 'cuánto dura')) {
            return response()->json(['respuesta' => 'La duración de los tratamientos depende del tipo, pero en promedio duran entre 30 y 60 minutos.']);
        }

        // Recomendaciones
        if (str_contains($mensaje, 'recomendación') || str_contains($mensaje, 'sugerencia')) {
            return response()->json(['respuesta' => 'Te recomendamos reservar con anticipación para asegurarte un turno.']);
        }

        // Información adicional
        if (str_contains($mensaje, 'información') || str_contains($mensaje, 'detalles')) {
            return response()->json(['respuesta' => 'Podés encontrar más información en nuestra página web o contactarnos directamente.']);
        }

        // Mensaje por defecto
        return response()->json([
            'respuesta' => 'Lo siento, no entendí tu mensaje. ¿Podés reformularlo?'
        ]);
    }
}
