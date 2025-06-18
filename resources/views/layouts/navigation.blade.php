<nav x-data="{ open: false }" class="bg-pink-200/60 backdrop-blur-md border-b border-gray-200">
    <!-- Primary Navigation Menu -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                     
                    </x-nav-link>
                </div>
            </div>

 <!-- Menú de usuario personalizado -->
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
                    {{ __('Mi Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar Sesión}') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
