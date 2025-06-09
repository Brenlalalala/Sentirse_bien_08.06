<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">

    {{-- 🔽 Aquí va tu navbar superior responsivo --}}
    <nav x-data="{ open: false }" class="text-white shadow-md" style="background-color: #f9a8d4;">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}">
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        </x-nav-link>
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Cerrar Sesión') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Cerrar Sesión') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- 🔽 Acá comienza tu layout actual con Sidebar --}}
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-58 text-oscuro shadow-lg flex flex-col" style="background-color: #fce7f3;">
            <div class="p-6 flex justify-center">
                <a href="{{ route('home') }}" class="transform transition-transform duration-500 hover:scale-105">
                    <img src="/imagenes/logo.png" alt="Logo Sentirse Bien" class="h-24 w-auto animate-fade-in">
                </a>
            </div>

            <nav class="mt-4 space-y-2 text-ms"> {{-- text-base o text-lg para letra más grande --}}
               
            @if (Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.servicios.index')" class="flex items-center gap-2">
                        <x-lucide-settings class="w-5 h-5" />
                        Gestionar Servicios
                    </x-nav-link>

                    <x-nav-link :href="route('admin.turnos.index')" class="flex items-center gap-2">
                            <x-lucide-calendar-days class="w-5 h-5" />
                            Gestionar Turnos
                        </x-nav-link>

                    <x-nav-link :href="route('admin.usuarios.index')" class="flex items-center gap-2">
                        <x-lucide-users class="w-5 h-5" />
                        Gestionar Usuarios
                    </x-nav-link>
                    
                    <x-nav-link :href="route('admin.turnos.dia')" class="flex items-center gap-2">
                        <x-lucide-calendar-check class="w-5 h-5" />
                        Ver Turnos por Día
                    </x-nav-link>

                    <x-nav-link href="#" class="flex items-center gap-2">
                        <x-lucide-history class="w-5 h-5" />
                        Ver historial de clientes
                    </x-nav-link>

                @elseif (Auth::user()->isProfesional())
                    <x-nav-link href="#" class="flex items-center gap-2">
                        <x-lucide-calendar-check class="w-5 h-5" />
                        Turnos del Día
                    </x-nav-link>
                    <x-nav-link href="#" class="flex items-center gap-2">
                        <x-lucide-history class="w-5 h-5" />
                        Historial Clientes
                    </x-nav-link>
                @elseif (Auth::user()->hasRole('cliente'))

                    <x-nav-link  :href="route('cliente.reservar-turno')"  class="flex items-center gap-2">
                        <x-lucide-calendar-plus class="w-5 h-5" />
                        Reservar Turno
                    </x-nav-link>
                    <x-nav-link href="#" class="flex items-center gap-2">
                        <x-lucide-heart class="w-5 h-5" />
                        Mis Servicios
                    </x-nav-link>
                    <x-nav-link href="#" class="flex items-center gap-2">
                        <x-lucide-history class="w-5 h-5" />
                        Historial
                    </x-nav-link>
                    <x-nav-link href="#" class="flex items-center gap-2">
                        <x-lucide-user class="w-5 h-5" />
                        Mi Perfil
                    </x-nav-link>
                @endif
            </nav>


            <div class="mt-auto p-4 text-center text-gray-500">
                <p class="text-sm">© {{ date('Y') }} Sentirse Bien. Todos los derechos reservados.</p>
            </div>


        </aside>

        <!-- Content -->
        <div class="flex-1 p-8">
            @yield('content')
        </div>

    </div>
</body>
</html>