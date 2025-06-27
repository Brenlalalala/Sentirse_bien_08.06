@extends('layouts.sidebar')

@section('content')
    {{-- Contenedor de bienvenida animado --}}
    <div class="p-6 bg-white rounded shadow-md animate-slide-in-left fixed-welcome">
        <h1 class="text-3xl font-extrabold text-pink-700 mb-4">
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
            Desde aquí podés acceder a todas tus funciones principales. Usá el menú lateral para navegar.
        </p>
    </div> 

    @role('cliente')
        {{-- Nota con descuento --}}
        <div class="relative mt-6 p-4 bg-green-200 text-green-900 rounded-md shadow-lg postit-anim animate-slide-in-left" 
        style="clip-path: polygon(0 0, 100% 0, 100% 90%, 90% 100%, 0 100%); width: 320px;">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-green-700 mt-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 12h2V8H9v4zm0 4h2v-2H9v2zm1-16C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0z"/>
            </svg>
            <div>
               <p class="font-extrabold text-2xl mb-1 whitespace-nowrap">🎉 ¡Descuento exclusivo!</p>

                <p class="text-base leading-relaxed">
                    💳 Si el pago se realiza por la <strong>web</strong> antes de las <strong>48 hs</strong> del servicio 🕒<br>
                    obtenés un <strong>15% de descuento</strong> 💸<br>
                    Solo se puede pagar con <strong>tarjeta de débito</strong> 😃 <br>
                    Caso contrario, se abona el <strong>precio de lista</strong> 🛒
                </p>
            </div>
        </div>

    <!-- Esquina doblada -->
    <div class="absolute bottom-0 right-0 w-0 h-0 border-b-[20px] border-l-[20px] border-b-white border-l-green-300"></div>
</div>

    @endrole

    <style>
        @keyframes slideInLeft {
            from {
                transform: translateX(100px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-slide-in-left {
            animation: slideInLeft 1.2s ease-out both;
        }

        /* Animación "bounce" cíclica para el postit */
        @keyframes bounceLR {
            0%, 80%, 100% {
                transform: translateX(0);
                box-shadow: 4px 7px 12px rgba(0,0,0,0.3);
            }
            25% {
                transform: translateX(8px);
                box-shadow: 6px 10px 15px rgba(0,0,0,0.4);
            }
            50% {
                transform: translateX(-6px);
                box-shadow: 6px 10px 15px rgba(0,0,0,0.4);
            }
            75% {
                transform: translateX(4px);
                box-shadow: 5px 9px 14px rgba(0,0,0,0.35);
            }
        }

        .postit-anim {
            animation: bounceLR 6s ease-in-out 2s infinite;
        }

        .fixed-welcome {
            max-width: 550px;
        }
    </style>
@endsection
