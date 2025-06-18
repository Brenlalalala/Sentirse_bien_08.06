@extends('layouts.sidebar')

@section('content')
    <div class="p-6 bg-white rounded shadow-md">
        <h1 class="text-2xl font-bold text-pink-700 mb-4">
            ¡Bienvenid@ {{ Auth::user()->name }}!
        </h1>

        <p class="text-gray-700 text-lg mb-4">
            Este es tu panel de 
            <strong>
                @role('admin')
                    administración
                @elserole('profesional')
                    profesional
                @else
                    cliente
                @endrole
            </strong>.
        </p>

        <p class="text-gray-600">
            Desde aquí podés acceder a todas tus funciones principales. Por favor, usá el menú lateral para navegar.
        </p>
    </div> 
       @role('cliente')
            <div class="relative p-2 bg-green-200 text-green-900 rounded-md shadow-lg my-6 postit-anim" style="clip-path: polygon(0 0, 100% 0, 100% 90%, 90% 100%, 0 100%); width: 250px; height: 250px;">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-green-700 mt-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12h2V8H9v4zm0 4h2v-2H9v2zm1-16C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0z"/>
                    </svg>
                    <div>
                        <p class="font-bold text-lg mb-1">🎉 ¡Descuento exclusivo!</p>
                        <p class="text-sm leading-relaxed">
                            💳 Si el pago se realiza por la <strong>web</strong> antes de las <strong>48 hs</strong> del servicio 🕒,<br>
                            obtenés un <strong>15% de descuento</strong> 💸.<br>
                            Solo se puede pagar con <strong>tarjeta de débito</strong> 😃 .<br>
                            Caso contrario, se abona el <strong>precio de lista</strong> 🛒.
                        </p>
                    </div>
                </div>

                <!-- Esquina doblada -->
                <div class="absolute bottom-0 right-0 w-0 h-0 border-b-[20px] border-l-[20px] border-b-white border-l-green-300"></div>
            </div>

            <style>
                /* Animación de bounce + izquierda-derecha */
                @keyframes bounceLR {
            0%, 80%, 100% {
                transform: translateX(0) translateY(0);
                box-shadow: 4px 7px 12px rgba(0,0,0,0.3);
            }
            25% {
                transform: translateX(12px) translateY(-6px);
                box-shadow: 6px 10px 15px rgba(0,0,0,0.4);
            }
            50% {
                transform: translateX(-12px) translateY(0);
                box-shadow: 6px 10px 15px rgba(0,0,0,0.4);
            }
            75% {
                transform: translateX(8px) translateY(-3px);
                box-shadow: 5px 9px 14px rgba(0,0,0,0.35);
            }
        }

                /* Animación para que aparezca flotando y desvaneciéndose */
                @keyframes floatIn {
                    0% {
                        transform: translateY(20px);
                        opacity: 0;
                    }
                    100% {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }

                .postit-anim {
                    animation: 
                        floatIn 2s ease-out forwards,
                        bounceLR 12s ease-in-out 2s infinite;
                } 

            </style>
        @endrole   
@endsection
