<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentirse bien - Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow-y: auto;
            scroll-snap-type: y mandatory;
        }

        section {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            scroll-snap-align: start;
        }

        #section1 {
            background-image: url('/imagenes/imagen_Fondo.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

                /* Excepción para section2: altura automática según contenido */
        #section2 {
            height: auto;
            padding-top: 4rem;
            padding-bottom: 4rem;
            display: block; /* para que el contenido fluya naturalmente */
        }

        #section2, #section3 {
            background-color: #fbb6ce;
        }

        #section3 {
    background-image: url('/imagenes/imagen_contacto.webp');
    background-size: cover; /* Cubre todo el fondo */
    background-position: center; /* Centra la imagen */
    background-repeat: no-repeat; /* Evita la repetición de la imagen */
    filter: brightness(0.9); /* Ajusta el brillo de la imagen */
           /* ... otros estilos ... */
}


        .btn-nav {
            @apply text-white text-xl px-4 py-2 rounded transition duration-300;
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-nav:hover {
            background: rgba(255, 255, 255, 0.25);
            color: #fbb6ce;
        }

        #main-header.scrolled {
            background-color: rgba(0, 0, 0, 0.8);
        }

        @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
        animation: fade-in 1s ease-out;
        }

        #scrollTopBtn {
        opacity: 0;
        font-size: 1.5rem;
        line-height: 1;
        z-index: 9999;
        transition: opacity 0.3s ease;
        
        }

        .tarjeta-promocion {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .tarjeta-promocion:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .boton-brillante {
            position: relative;
            overflow: hidden;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .boton-brillante::after {
            content: "";
            position: absolute;
            top: 0;
            left: -75%;
            width: 50%;
            height: 100%;
            background: linear-gradient(120deg, rgba(255,255,255,0.5) 0%, rgba(255,255,255,0.2) 100%);
            transform: skewX(-25deg);
        }

        .boton-brillante:hover::after {
            animation: shine 0.75s forwards;
        }

        @keyframes shine {
            0% { left: -75%; }
            100% { left: 125%; }
        }

        .boton-brillante {
        position: relative;
        overflow: hidden;
        background-size: 200% auto;
        color: white;
        animation: brillar 2s linear infinite;
        }

        @keyframes brillar {
        0% { background-position: 0% center; }
        100% { background-position: 200% center; }
        }

        .fade-in {
        opacity: 1 !important;
        transition: opacity 0.5s ease-in;
        display: block !important;
        }

        .fade-out {
        opacity: 0 !important;
        transition: opacity 0.5s ease-out;
        display: none !important;
        }

        @keyframes float-up-down {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-8px);
            }
        }

        .floating {
            animation: float-up-down 2s ease-in-out infinite;
        }
                
        @keyframes vibrar-suave {
            0%, 100% { transform: translate(0); }
            20% { transform: translateX(-2px); }
            40% { transform: translateX(2px); }
            60% { transform: translateX(-1px); }
            80% { transform: translateX(1px); }
        }

        .boton-vibrador {
            animation: vibrar-suave 0.5s ease-in-out infinite;
            animation-delay: 0s;
            animation-iteration-count: infinite;
            animation-play-state: running;
        }

        /* Solo lo hace vibrar cada 5s */
        @keyframes intermitente {
            0%, 95% {
                animation-play-state: paused;
            }
            96%, 100% {
                animation-play-state: running;
            }
        }
    </style>
</head>

<!-- Botón flotante -->
<!-- Botón flotante con mensaje -->
<div id="boton-chat-container" style="position:fixed; bottom:20px; right:20px; z-index:9999; display: flex; align-items: center; gap: 10px;">

    <!-- Mensaje de ayuda -->
    <span id="mensaje-ayuda" style="background:#fff0f5; color:#ec4899; padding:8px 12px; border-radius:20px; font-weight:500; font-size:14px; box-shadow:0 0 5px rgba(0,0,0,0.1);">
        ¿Necesitás ayuda?
    </span>

    <!-- Botón flotante -->
    <button id="boton-chat" onclick="toggleChat()"
        style="background:#ec4899; border:none; border-radius:50%; width:70px; height:70px; box-shadow:0 0 10px rgba(0,0,0,0.3); cursor:pointer; position:relative; display:flex; align-items:center; justify-content:center;"
        class="vibrar">

        <img src="{{ asset('imagenes/iconochatbot.png') }}" width="32" height="32">

        <span id="punto-notificacion" style="display:none; position:absolute; top:8px; right:8px; background:red; width:12px; height:12px; border-radius:50%; z-index:10000;"></span>
    </button>


</div>


    <!-- Ventana del chatbot -->
    <div id="chatbot" style="display:none; position:fixed; bottom:90px; right:20px; width:300px; background:white; border:1px solid #ccc; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.2); z-index:9999;">
    <div class="bg-pink-500 border border-white rounded-t-xl p-3 flex items-center justify-between shadow-md">
        <div class="flex items-center space-x-2">
            <span class="text-xl">🌼</span>
            <span class="font-semibold text-white text-sm sm:text-base">Asistente Sentirse Bien</span>
        </div>
        <button onclick="toggleChat()" class="text-white hover:text-gray-200 transition text-lg font-bold">
            ✖
        </button>
    </div>

        <div id="mensajes" style="height:200px; overflow-y:auto; padding:10px; font-size:14px;"></div>
        <div style="display:flex; border-top:1px solid #eee;">
        <input type="text" id="entrada" placeholder="Escribe tu mensaje..." style="flex:1; border:none; padding:8px;" onkeypress="detectarEnter(event)">
            <button
                onclick="enviarMensaje()"
                style="background:#ec4899; color:white; border:none; padding:8px 16px; border-radius:9999px; font-family:inherit; font-weight:500;"
            >
                Enviar
            </button>
        </div>
    </div>

    <audio id="sonido-notificacion" src="https://assets.mixkit.co/sfx/preview/mixkit-software-interface-start-2574.mp3" preload="auto"></audio>


<body class="font-sans">

<header id="main-header" class="py-2 shadow fixed w-full z-50">
    <nav class="container mx-auto px-4 flex items-center justify-between">
        <!-- Logo con animación -->
        <a href="/" class="transform transition-transform duration-500 hover:scale-105">
            <img src="/imagenes/logo.png" alt="Logo Sentirse Bien" class="h-24 w-auto animate-fade-in">
        </a>

        <!-- Menú de navegación -->
        <div id="nav-links" class="hidden md:flex space-x-4 items-center">
            <a href="/conocenos" class="text-white font-semibold px-4 py-2 rounded transition duration-300 hover:bg-pink-500 hover:text-white boton-brillante">Conócenos</a>
            <a href="/servicios" class="text-white font-semibold px-4 py-2 rounded transition duration-300 hover:bg-pink-500 hover:text-white boton-brillante">Servicios</a>
            <a href="#" id="ir-a-consultas-nav" class="text-white font-semibold px-4 py-2 rounded transition duration-300 hover:bg-pink-500 hover:text-white boton-brillante">Consultas</a>

            @auth
               <!-- Menú desplegable con usuario -->
    <div class="relative">
        <button id="userDropdown" class="flex items-center space-x-2 px-4 py-2 bg-pink-500 text-white rounded hover:bg-pink-600">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 10a4 4 0 100-8 4 4 0 000 8zm0 2c-3 0-6 1.5-6 4v1h12v-1c0-2.5-3-4-6-4z" />
            </svg>
            <span>{{ auth()->user()->name }}</span>
        </button>

    <!-- Menú desplegable -->
    <div id="userMenu" class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded shadow-md z-50">
        @php
            $user = auth()->user();
        @endphp

        <a href="
            @switch($user->role)
                @case('admin')
                    {{ route('dashboard') }}
                    @break

                @case('cliente')
                    {{ route('dashboard') }}
                    @break

                @case('profesional')
                    {{ route('dashboard') }}
                    @break

                @default
                    #
            @endswitch
        " class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            @switch($user->role)
                @case('admin')
                    Panel Admin
                    @break

                @case('cliente')
                    Ver Perfil
                    @break

                @case('profesional')
                    Perfil Profesional
                    @break

                @default
                    Perfil
            @endswitch
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                Cerrar sesión
            </button>
        </form>
    </div>
</div>

                @else
                    <!-- Botones para usuarios no autenticados -->
                    <a href="{{ route('login') }}" class="text-white font-semibold px-4 py-2 rounded transition duration-300 hover:bg-pink-500 hover:text-white boton-brillante">
                        Iniciar sesión
                    </a>
                    <a href="{{ route('register') }}" class="text-white font-semibold px-4 py-2 rounded transition duration-300 hover:bg-pink-500 hover:text-white boton-brillante">
                        Registrarse
                    </a>

                @endauth
        </div>
    </nav>
</header>

<section id="section1">
    <div class="flex items-center justify-center h-full">
        <div class="flex flex-col items-center justify-center space-y-6" style="transform: translateY(10%);">
            
            <!-- TÍTULO -->
            <h2 class="text-5xl font-bold text-white text-center" style="text-shadow: 9px 9px 9px rgba(0, 0, 0, 0.5);">SENTIRSE BIEN</h2>

            <!-- SUBTÍTULO -->
            <p class="text-3xl text-white text-center" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">RELAJAR - RESTAURAR - REVIVIR</p>

            <!-- BOTÓN -->
            <a href="/servicios" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-3 px-6 rounded-lg text-xl transition duration-300 ease-in-out transform hover:scale-105">AGENDAR CITA</a>
        </div>
    </div>
</section>

<section id="section2" class="py-16 bg-pink-100">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold text-center text-pink-700 mb-12">Paquetes y Promociones</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Primer paquete -->
            <div class="bg-white rounded-xl p-4 text-sm text-center shadow-lg hover:shadow-2xl transform hover:scale-105 transition duration-300 ease-in-out h-full flex flex-col justify-between min-h-[28rem]">
            <img src="/imagenes/promocion1.jpg" alt="Masaje Anti-Stress" class="rounded mb-4">
                <h3 class="text-2xl font-semibold text-pink-600 mb-2">Pack Relax Total</h3>
                <p class="text-gray-700 mb-4">Incluye Masaje Anti-Stress + Limpieza facial profunda + Yoga grupal.</p>
                <span class="block text-lg font-bold text-pink-500 mt-auto">$35000 ARS</span>
                @auth
                <button
                    class="w-full mt-4 bg-pink-500 text-white px-2 py-2 rounded hover:bg-pink-600 transition"
                    disabled>
                    Reservar
                </button>
                @endauth
                @guest
                 <p class="text-sm text-gray-600 mt-2">Inicia sesión para reservar.</p>
                @endguest
            </div>
            

            <!-- Segundo paquete -->
            <div class="bg-white rounded-xl p-4 text-sm text-center shadow-lg hover:shadow-2xl transform hover:scale-105 transition duration-300 ease-in-out h-full flex flex-col justify-between min-h-[28rem]">
            <img src="/imagenes/promocion2.jpg" alt="Pack Belleza Total" class="rounded mb-4">
                <h3 class="text-2xl font-semibold text-pink-600 mb-2">Pack Belleza Total</h3>
                <p class="text-gray-700 mb-4">Incluye Lifting de pestañas + Belleza de manos y pies + Depilación facial.</p>
                <span class="block text-lg font-bold text-pink-500 mt-auto">$40000 ARS</span>
                @auth
                <button
                    class="w-full mt-4 bg-pink-500 text-white px-2 py-2 rounded hover:bg-pink-600 transition"
                    disabled>
                    Reservar
                </button>
                @endauth
                @guest
                 <p class="text-sm text-gray-600 mt-2">Inicia sesión para reservar.</p>
                @endguest
            </div>

            <!-- Tercer paquete -->
            <div class="bg-white rounded-xl p-4 text-sm text-center shadow-lg hover:shadow-2xl transform hover:scale-105 transition duration-300 ease-in-out h-full flex flex-col justify-between min-h-[28rem]">
            <img src="/imagenes/promocion3.jpg" alt="Pack Relax y Detox" class="rounded mb-4">
                <h3 class="text-2xl font-semibold text-pink-600 mb-2">Pack Relax y Detox</h3>
                <p class="text-gray-700 mb-4">Incluye Masaje descontracturante + Punta de diamante (microexfoliación) + Criofrecuencia facial.</p>
                <span class="block text-lg font-bold text-pink-500 mt-auto">$52000 ARS</span>
                @auth
                <button
                    class="w-full mt-4 bg-pink-500 text-white px-2 py-2 rounded hover:bg-pink-600 transition"
                    disabled>
                    Reservar
                </button>
                @endauth
                @guest
                 <p class="text-sm text-gray-600 mt-2">Inicia sesión para reservar.</p>
                @endguest
            </div>

            <!-- Cuarto paquete -->
            <div class="bg-white rounded-xl p-4 text-sm text-center shadow-lg hover:shadow-2xl transform hover:scale-105 transition duration-300 ease-in-out h-full flex flex-col justify-between min-h-[28rem]">
            <img src="/imagenes/promocion4.jpg" alt="Pack Hidro Spa" class="rounded mb-4">
                <h3 class="text-2xl font-semibold text-pink-600 mb-2">Pack Hidro Spa</h3>
                <p class="text-gray-700 mb-4">Incluye Hidromasajes grupales + Masaje circulatorio + Limpieza facial profunda.</p>
                <span class="block text-lg font-bold text-pink-500 mt-auto">$45000 ARS</span>
                @auth
                <button
                    class="w-full mt-4 bg-pink-500 text-white px-2 py-2 rounded hover:bg-pink-600 transition"
                    disabled>
                    Reservar
                </button>
                @endauth
                @guest
                 <p class="text-sm text-gray-600 mt-2">Inicia sesión para reservar.</p>
                @endguest
            </div>

            <!-- Quinto paquete -->
            <div class="bg-white rounded-xl p-4 text-sm text-center shadow-lg hover:shadow-2xl transform hover:scale-105 transition duration-300 ease-in-out h-full flex flex-col justify-between min-h-[28rem]">
            <img src="/imagenes/promocion5.jpg" alt="Pack Bienestar Corporal" class="rounded mb-4">
                <h3 class="text-2xl font-semibold text-pink-600 mb-2">Pack Bienestar Corporal</h3>
                <p class="text-gray-700 mb-4">Incluye Ultracavitación + VelaSlim + Yoga grupal.</p>
                <span class="block text-lg font-bold text-pink-500 mt-auto">$48000 ARS</span>
                @auth
                <button
                    class="w-full mt-4 bg-pink-500 text-white px-2 py-2 rounded hover:bg-pink-600 transition"
                    disabled>
                    Reservar
                </button>
                @endauth
                @guest
                 <p class="text-sm text-gray-600 mt-2">Inicia sesión para reservar.</p>
                @endguest
            </div>

            <!-- Sexto paquete -->
            <div class="bg-white rounded-xl p-4 text-sm text-center shadow-lg hover:shadow-2xl transform hover:scale-105 transition duration-300 ease-in-out h-full flex flex-col justify-between min-h-[28rem]">
            <img src="/imagenes/promocion6.jpg" alt="Pack Anti-Stress Premium" class="rounded mb-4">
                <h3 class="text-2xl font-semibold text-pink-600 mb-2">Pack Anti-Stress Premium</h3>
                <p class="text-gray-700 mb-4">Incluye Masaje con piedras calientes + Criofrecuencia facial + DermoHealth corporal.</p>
                <span class="block text-lg font-bold text-pink-500 mt-auto">$60000 ARS</span>
                @auth
                <button
                    class="w-full mt-4 bg-pink-500 text-white px-2 py-2 rounded hover:bg-pink-600 transition"
                    disabled>
                    Reservar
                </button>
                @endauth
                @guest
                 <p class="text-sm text-gray-600 mt-2">Inicia sesión para reservar.</p>
                @endguest
            </div>
        </div>
    </div>
</section>

<section id="section3">
 @include('contacto')
</section>
    
    <div id="scroll-anchor"></div>

    <footer class="bg-gray-200 py-4 text-center">
        <p>&copy; {{ date('Y') }} Sentirse bien. Todos los derechos reservados.</p>
    </footer>

   <!-- Botón login -->
    <!-- <div id="login-modal" class="fixed z-50 inset-0 overflow-y-auto invisible opacity-0 pointer-events-none transition-opacity duration-300">

        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Iniciar sesión</h3>
                    <div class="mt-2">
                        @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif -->
<!-- 
                        <form method="POST" action="{{ route('login') }}">
                        @csrf
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Correo electrónico</label>
                                <input type="email" name="email" id="email" class="w-full px-3 py-2 border rounded" placeholder="Tu correo electrónico">
                            </div>
                            <div class="mb-6">
                                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña</label>
                                <div class="relative">
                                    <input type="password" name="password" id="login-password" class="w-full px-3 py-2 border rounded pr-10" placeholder="Tu contraseña">
                                    <button type="button" id="toggle-login-password" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-600">
                                        👁️
                                    </button>
                                </div>
                            </div>


                            <div class="flex items-center justify-between">
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">
                                    Iniciar sesión
                                </button>
                                <a href="#" class="text-blue-500 hover:text-blue-800 text-sm font-bold">¿Olvidaste tu contraseña?</a>
                            </div>
                        </form>

                        <!-- Botón Registrarse fuera del form -->
                        <!-- <div class="mt-4 text-center">
                        <a href="#" id="open-register-modal" class="text-blue-600 font-semibold hover:underline">
                            ¿No tenés cuenta? Registrate
                        </a>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button id="close-modal" type="button" class="w-full inline-flex justify-center rounded-md bg-pink-500 text-white font-medium px-4 py-2 hover:bg-pink-600">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div> --> 


<!-- Modal de registro -->
    <!-- <div id="register-modal" class="fixed z-50 inset-0 overflow-y-auto invisible opacity-0 pointer-events-none transition-opacity duration-300">

    <div class="flex items-center justify-center min-h-screen text-center sm:block sm:p-0 relative">
        <div class="absolute inset-0 bg-gray-500 opacity-75 z-10"></div>
            <div class="inline-block bg-white rounded-lg shadow-xl sm:max-w-lg sm:w-full p-6 z-20 relative">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Crear una cuenta</h3>
                    <form method="POST" action="/registrar-cliente">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombre</label>
                            <input type="text" name="name" required class="w-full px-3 py-2 border rounded">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Correo electrónico</label>
                            <input type="email" name="email" required class="w-full px-3 py-2 border rounded">
                        </div>
                        <div class="mb-6">
                            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña</label>
                            <div class="relative">
                                <input type="password" name="password" id="register-password" required class="w-full px-3 py-2 border rounded pr-10" placeholder="Tu contraseña">
                                <button type="button" id="toggle-register-password" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-600">
                                    👁️
                                </button>
                            </div>
                        </div>


                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirmar Contraseña</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="register-password_confirmation" required class="w-full px-3 py-2 border rounded pr-10" placeholder="Confirma tu contraseña">
                                <button type="button" id="toggle-register-password_confirmation" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-600">
                                    👁️
                                </button>
                            </div>
                        </div>



                        <div class="flex justify-between">
                            <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">
                                Registrarse
                            </button>
                            <button type="button" id="close-register-modal" class="text-pink-500 hover:text-pink-700">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div> -->

<script>
document.addEventListener('DOMContentLoaded', function () {
    const encabezadoPrincipal = document.getElementById('main-header');
    const seccion2 = document.getElementById('section2');
    const enlaceConsultasNav = document.getElementById('ir-a-consultas-nav');

    // Desplazar a la sección de contacto (section3) desde el botón "Consultas" de la navegación
    if (enlaceConsultasNav) {
        enlaceConsultasNav.addEventListener('click', function (evento) {
            evento.preventDefault(); // Evita que el enlace intente navegar a "#"
            document.getElementById('section3').scrollIntoView({ behavior: 'smooth' });
        });
    }

    // Desplazar a la sección de contacto (section3) y mostrar/ocultar formulario (botón dentro de la sección)
    const botonMostrarContacto = document.getElementById('mostrar-contacto');
    const formularioContacto = document.getElementById('formulario-contacto');

    if (botonMostrarContacto) {
        botonMostrarContacto.addEventListener('click', function (evento) {
            evento.preventDefault(); // Evita el comportamiento predeterminado del enlace
            document.getElementById('section3').scrollIntoView({ behavior: 'smooth' });
            //formularioContacto.classList.toggle('hidden'); si no se oculta
        });
    }

    // Botón scroll arriba
    const botonScrollArriba = document.getElementById('scrollTopBtn');
    const puntoAnclajeScroll = document.getElementById('scroll-anchor');

    const observadorScrollArriba = new IntersectionObserver((entradas) => {
        entradas.forEach(entrada => {
            if (entrada.isIntersecting) {
                botonScrollArriba.classList.remove('fade-out');
                botonScrollArriba.classList.add('fade-in', 'floating'); // 👈 le agregamos animación
            } else {
                botonScrollArriba.classList.remove('fade-in', 'floating');
                botonScrollArriba.classList.add('fade-out');
            }
        });
    }, {
        root: null,
        threshold: 0.05
    });

    observadorScrollArriba.observe(puntoAnclajeScroll);

    //  Scroll al arriba
    botonScrollArriba.addEventListener('click', function () {
        document.getElementById('section1').scrollIntoView({
            behavior: 'smooth'
        });
    });

     // Dropdown usuario
     // Obtén el botón que activa el dropdown
    const dropdownButton = document.getElementById('userDropdown');
    const dropdownMenu = document.getElementById('userMenu');

    // Función para abrir/cerrar el dropdown
    if (dropdownButton && dropdownMenu) {
        dropdownButton.addEventListener('click', function (event) {
            event.preventDefault(); // Evita el comportamiento predeterminado
            dropdownMenu.classList.toggle('hidden'); // Muestra u oculta el menú
        });

        // Cierra el menú cuando haces clic fuera de él
        document.addEventListener('click', function (event) {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });
    }


    // Agregar evento para mostrar/ocultar la contraseña en el login
    const loginPasswordToggle = document.getElementById('toggle-login-password');
    if (loginPasswordToggle) {
        loginPasswordToggle.addEventListener('click', function () {
            togglePassword('login-password', loginPasswordToggle);
        });
    }

    // Agregar evento para mostrar/ocultar la contraseña en el registro
    const registerPasswordToggle = document.getElementById('toggle-register-password');
    if (registerPasswordToggle) {
        registerPasswordToggle.addEventListener('click', function () {
            togglePassword('register-password', registerPasswordToggle);
        });
    }

     // Agregar evento para mostrar/ocultar la confirmación de contraseña en el registro
        const registerPasswordConfirmationToggle = document.getElementById('toggle-register-password_confirmation');
    if (registerPasswordConfirmationToggle) {
        registerPasswordConfirmationToggle.addEventListener('click', function () {
            togglePassword('register-password_confirmation', registerPasswordConfirmationToggle);
        });
    }


    // Cambiar fondo dinámico en section1
    const fondo = document.getElementById('section1');
    const imagenes = [
        '/imagenes/imagen_Fondo.jpeg',
        '/imagenes/fondo1.jpg',
        '/imagenes/fondo2.jpg',
        '/imagenes/fondo3.jpg'
    ];
    let indice = 0;

    setInterval(() => {
        indice = (indice + 1) % imagenes.length;
        fondo.style.backgroundImage = `url('${imagenes[indice]}')`;
    }, 5000);

    // Ocultar barra de navegación en section2
    const observadorEncabezado = new IntersectionObserver(entradas => {
        entradas.forEach(entrada => {
            if (entrada.isIntersecting) {
                // Si la sección 2 está en la ventana de visualización, oculta la barra de navegación
                encabezadoPrincipal.classList.add('hidden');
            } else {
                // Si la sección 2 no está en la ventana de visualización, muestra la barra de navegación
                encabezadoPrincipal.classList.remove('hidden');
            }
        });
    }, 
    
    {
        root: null, // Observa la ventana de visualización del navegador
        threshold: 0.5 // Cambia este valor según cuánto de la sección 2 quieres que sea visible para ocultar la barra
    });

    // Comienza a observar la sección 2
    observadorEncabezado.observe(seccion2);
});

        //Chatbot
        function toggleChat() {
            const chat = document.getElementById('chatbot');
            const container = document.getElementById('boton-chat-container');
            const noti = document.getElementById('punto-notificacion');

            const abierto = chat.style.display === 'block';

            if (abierto) {
                chat.style.display = 'none';
                container.style.display = 'flex'; // Mostrar botón y mensaje
            } else {
                chat.style.display = 'block';
                container.style.display = 'none'; // Ocultar ambos
                noti.style.display = 'none'; // Limpiar notificación
            }
        }

        function enviarMensaje() {
        const entrada = document.getElementById('entrada');
        const mensaje = entrada.value.trim();
        if (mensaje === '') return;

        const mensajesDiv = document.getElementById('mensajes');
        mensajesDiv.innerHTML += `<div><strong>Tú:</strong> ${mensaje}</div>`;

        fetch('/chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ mensaje })
        })
        .then(res => res.json())
        .then(data => {
            mensajesDiv.innerHTML += `<div><strong>Bot:</strong> ${data.respuesta}</div>`;
            mensajesDiv.scrollTop = mensajesDiv.scrollHeight;

            const chatAbierto = document.getElementById('chatbot').style.display === 'block';
            if (!chatAbierto) {
                document.getElementById('punto-notificacion').style.display = 'block';
                document.getElementById('sonido-notificacion').play();
            }
        });

        entrada.value = '';
    }

    // Función para mostrar/ocultar la contraseña
    function togglePassword(inputId, toggleButton) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            toggleButton.textContent = '🙈'; // Cambia el ícono a "ojo cerrado"
        } else {
            input.type = 'password';
            toggleButton.textContent = '👁️'; // Cambia el ícono a "ojo abierto"
        }
    }

        // Detectar Enter en el campo de entrada del chatbot
    function detectarEnter(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Evita el comportamiento predeterminado del Enter
                enviarMensaje();
            }
        }
        
        setInterval(() => {
            const chatAbierto = document.getElementById('chatbot').style.display === 'block';
            const boton = document.getElementById('boton-chat');
            if (!chatAbierto) {
                boton.classList.add('boton-vibrador');
                setTimeout(() => boton.classList.remove('boton-vibrador'), 500);
            }
        }, 2000); // cada 2 segundos
    </script>


<div id="scrollTopBtn" class="fixed bottom-5 right-5 cursor-pointer z-50 fade-out" title="Volver arriba">
    <img src="/imagenes/flecha-arriba.png" alt="Volver arriba" class="w-12 h-12 drop-shadow-lg">
</div>

</body>

</html>
