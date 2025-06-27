
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Spa Sentirse Bien') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
     <style>
    body {
        background-image: url('https://images.pexels.com/photos/4041392/pexels-photo-4041392.jpeg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        }

    </style>

</head>
<body class="font-sans antialiased text-gray-900">

    {{-- 🔽 Aquí va tu navbar superior --}}

    {{-- 🔽 Aquí va tu navbar superior responsivo --}}
<nav x-data="{ open: false }" class="text-white shadow-md bg-pink-200/60">
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-4">
            <div class="flex justify-between items-center h-24">
                <!-- Logo (izquierda) -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="transform transition-transform duration-500 hover:scale-105">
                        <img src="/imagenes/logo.png" alt="Logo Sentirse Bien" class="h-24 w-auto animate-fade-in">
                    </a>
                </div>

                <!-- Menú de usuario (derecha) -->
                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-white shadow-md text-sm text-gray-700 hover:text-gray-900 focus:outline-none transition duration-150">
                                <!-- Foto o ícono -->
                                @if(Auth::user()->foto)
                                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto de perfil" class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Nombre -->
                                <span>{{ Auth::user()->name }}</span>

                                <!-- Flechita -->
                                <svg class="ml-1 h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Mi Perfil') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Cerrar Sesión') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Menú Hamburguesa (responsive) -->
                <div class="sm:hidden flex items-center">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menú responsive desplegable -->
        <div :class="{ 'block': open, 'hidden': ! open }" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            </div>

            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Perfil') }}
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Cerrar Sesión') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- 🔽 Acá comienza tu layout actual con Sidebar --}}
    <div class="flex min-h-screen bg-white/70 backdrop-blur-sm rounded-xl shadow-xl">

        <!-- Sidebar -->
        <aside class="w-52 text-oscuro shadow-lg flex flex-col">
<!-- Foto de perfil y nombre del usuario -->
<div class="flex flex-col items-center mt-4 mb-6 px-4">
        @if(Auth::check() && Auth::user()->foto)
            <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto de perfil" class="h-24 w-24 rounded-lg object-cover shadow-md">
        @else
            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                <svg class="h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                </svg>
            </div>
        @endif

    <p class="mt-2 text-sm font-bold text-gray-700 text-center">{{ Auth::user()->name }}</p>
</div>
            <nav class="mt-18 space-y-2 text-ms">
                @if (Auth::user()->isAdmin())
                    <x-button-link :href="route('admin.servicios.index')" :active="request()->routeIs('admin.servicios.index')">
                        <x-lucide-settings class="w-5 h-5" />
                        Gestionar Servicios
                    </x-button-link>

                    <x-button-link :href="route('admin.turnos.index')" :active="request()->routeIs('admin.turnos.index')">
                        <x-lucide-calendar-days class="w-5 h-5" />
                        Gestionar Turnos
                    </x-button-link>

                    <x-button-link :href="route('admin.usuarios.index')" :active="request()->routeIs('admin.usuarios.index')">
                        <x-lucide-users class="w-5 h-5" />
                        Gestionar Usuarios
                    </x-button-link>

                    <x-button-link :href="route('admin.turnos.dia')" :active="request()->routeIs('admin.turnos.dia')">
                        <x-lucide-calendar-check class="w-5 h-5" />
                        Ver Turnos por Día
                    </x-button-link>

                    <x-button-link :href="route('admin.clientes')" :active="request()->routeIs('admin.clientes')">
                        <x-lucide-history class="w-5 h-5" />
                        Ver historial de clientes
                    </x-button-link>
                    <x-button-link :href="route('admin.horarios.index')" :active="request()->routeIs('admin.horarios')">
                        <x-lucide-history class="w-5 h-5" />
                        Horarios de profesionales
                    </x-button-link>
                    <x-button-link :href="route('pagos.por-servicio')" :active="request()->routeIs('pagos.por-servicio')">
                    <x-lucide-credit-card class="w-5 h-5" />
                    Pagos por Servicio
                </x-button-link>

                <x-button-link :href="route('pagos.por-profesional')" :active="request()->routeIs('pagos.por-profesional')">
                    <x-lucide-user-check class="w-5 h-5" />
                    Pagos por Profesional
                </x-button-link>


                @elseif (Auth::user()->isProfesional())
                    <x-button-link :href="route('profesional.turnos')" :active="request()->routeIs('profesional.turnos')">
                        <x-lucide-file-text class="w-5 h-5" />
                        Ver Turnos
                    </x-button-link>

                     <x-button-link :href="route('profesional.historial.ver', Auth::user()->id)" :active="request()->routeIs('profesional.historial.ver')">
                        <x-lucide-history class="w-5 h-5" />
                        Historial Clientes
                    </x-button-link> 

                @elseif (Auth::user()->hasRole('cliente'))
                    <x-button-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <x-lucide-home class="w-5 h-5" />
                        Inicio
                    </x-button-link>
                    <x-button-link :href="route('cliente.reservar-turno')" :active="request()->routeIs('cliente.reservar-turno')">
                        <x-lucide-calendar-plus class="w-5 h-5" />
                        Reservar Turno
                    </x-button-link>
                    <x-button-link :href="route('cliente.mis-servicios')" :active="request()->routeIs('cliente.mis-servicios')">
                        <x-lucide-heart class="w-5 h-5" />
                        Mis Servicios
                    </x-button-link>
                    <x-button-link :href="route('cliente.pagos.mis-pagos')" :active="request()->routeIs('cliente.pagos.mis-pagos')">
                        <x-lucide-wallet class="w-5 h-5" />
                        Mis Pagos
                    </x-button-link>
                    <x-button-link :href="route('cliente.historial')" :active="request()->routeIs('cliente.historial')">
                        <x-lucide-history class="w-5 h-5" />
                        Historial
                    </x-button-link>
                @endif
            </nav>
        </aside>

        <!-- Content -->
        <div class="flex-1 p-8">
            @yield('content')
        </div>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/card/2.5.6/card.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/card/2.5.6/card.min.css" />

</body>
</html>