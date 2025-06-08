<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios - Sentirse Bien</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>


<body class="bg-pink-100 text-gray-800 font-sans">

    {{-- HEADER CON LOGO --}}
    <header id="main-header" class="sticky top-0 w-full z-50 transition-transform duration-300 bg-opacity-80 backdrop-blur-md">
        <nav class="px-4 flex items-center justify-between" style="background-color: #fbb6ce;">
            <a href="/" class="transform transition-transform duration-500 hover:scale-105">
                <img src="/imagenes/logo.png" alt="Logo Sentirse Bien" class="h-24 w-auto animate-fade-in">
            </a>

            <button id="menu-toggle" class="md:hidden text-pink-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </nav>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="container mx-auto px-4 py-10 pt-[220px] space-y-16">

        @if(isset($servicios))
            @foreach($servicios as $categoria => $subcategorias)
                <section>
                    <h2 class="text-3xl font-bold text-pink-500 mb-6">
                        Servicios {{ ucfirst($categoria) }}
                    </h2>

                    @foreach($subcategorias as $subcategoria => $items)
                        <h3 class="text-2xl font-semibold text-pink-400 mt-8 mb-4">• {{ $subcategoria }}</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($items as $servicio)
                                @php
                                    $nombreArchivoImagen = Str::slug($servicio->nombre) . '.jpg';
                                    $rutaImagen = asset('imagenes/' . $nombreArchivoImagen);
                                @endphp
                                    <div class="bg-white rounded-xl p-4 text-sm text-center shadow-lg hover:shadow-2xl transform hover:scale-105 transition duration-300 ease-in-out h-full flex flex-col justify-between min-h-[28rem]">
                                        <img src="{{ $rutaImagen }}" alt="{{ $servicio->nombre }}" class="w-full h-40 object-cover rounded-lg mb-4">

                                     <div class="flex-grow">
                                         <h4 class="text-xl font-bold text-pink-600">{{ $servicio->nombre }}</h4>

                                            <p class="text-gray-600 mt-2 mb-4">
                                                 {{ $servicio->descripcion ?? 'Servicio personalizado y de alta calidad.' }}
                                            </p>
                                    </div>

                                @if($servicio->precio)
                                    <span class="block text-lg font-bold text-pink-500 mt-auto">
                                        ${{ number_format($servicio->precio, 0, ',', '.') }} ARS
                                    </span>
                                    <button onclick="abrirModalDia('{{ $servicio->nombre }}')"
                                        class="mt-4 bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition">
                                        Reservar
                                    </button>


                                @endif
                            </div>

                            @endforeach
                        </div>
                    @endforeach
                </section>
            @endforeach
        @else
            <p class="text-center text-gray-600">No hay servicios disponibles en este momento.</p>
        @endif

    </main>

    {{-- FOOTER --}}
    <footer class="bg-gray-200 text-center py-4 mt-10">
        <p>&copy; {{ date('Y') }} Sentirse Bien. Todos los derechos reservados.</p>
    </footer>

    {{-- HEADER OCULTO AL SCROLL --}}
    <script>
        let lastScroll = 0;
        const header = document.getElementById('main-header');

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;

            if (currentScroll > lastScroll && currentScroll > 100) {
                header.classList.add('-translate-y-full');
            } else {
                header.classList.remove('-translate-y-full');
            }

            lastScroll = currentScroll;
        });
    </script>

    {{-- MODALES DEL CALENDARIO --}}
    {{-- MODAL: Selección de día --}}
    <div id="modal-dia" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Selecciona una fecha</h2>
            <input type="date" id="fecha-seleccionada" class="w-full border border-pink-300 p-2 rounded mb-4">
            <div class="flex justify-between">
                <button id="cancelar-dia" class="bg-gray-400 text-white px-4 py-2 rounded">Cancelar</button>
                <button id="confirmar-dia" class="bg-pink-500 text-white px-4 py-2 rounded">Confirmar</button>
            </div>
        </div>
    </div>

    {{-- MODAL: Selección de horario --}}
    <div id="modal-horario" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Selecciona un horario</h2>
            <div id="horarios-disponibles" class="mb-4"></div>
            <div class="text-right">
                <button id="cancelar-horario" class="bg-gray-400 text-white px-4 py-2 rounded">Cancelar</button>
            </div>
        </div>
    </div>
    {{-- MODAL: Datos del cliente --}}  
    <!-- Modal: Datos del cliente -->
    <form id="modal-datos" method="POST" action="{{ route('reservar') }}" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
        @csrf
        <input type="hidden" name="servicio" id="input-servicio">
        <input type="hidden" name="fecha" id="input-fecha">
        <input type="hidden" name="hora" id="input-hora">

        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Tus datos</h2>
            <input type="text" name="nombre" placeholder="Nombre completo" required class="w-full border p-2 mb-2 rounded">
            <input type="email" name="email" placeholder="Correo electrónico" required class="w-full border p-2 mb-2 rounded">
            <input type="text" name="telefono" placeholder="Teléfono (opcional)" class="w-full border p-2 mb-4 rounded">

            <div class="flex justify-between">
                <button type="button" onclick="document.getElementById('modal-datos').classList.add('hidden')" class="bg-gray-400 px-4 py-2 rounded text-white">Cancelar</button>
                <button type="submit" class="bg-pink-500 px-4 py-2 rounded text-white">Reservar</button>
            </div>
        </div>
    </form>


         {{-- Script MODAL CALENDARIO --}}
    <!-- Modal fecha → Modal hora → Modal cliente -->
    <script>
    let servicioSeleccionado = '';
    let fechaSeleccionada = '';
    let horaSeleccionada = '';

    function abrirModalDia(servicio) {
        servicioSeleccionado = servicio;
        document.getElementById('modal-dia').classList.remove('hidden');
    }

    document.getElementById('cancelar-dia').addEventListener('click', () => {
        document.getElementById('modal-dia').classList.add('hidden');
    });

    document.getElementById('confirmar-dia').addEventListener('click', () => {
        const fecha = document.getElementById('fecha-seleccionada').value;
        if (!fecha) return alert('Seleccioná una fecha');

        fechaSeleccionada = fecha;
        const horarios = ['10:00', '12:00', '14:00', '16:00'];

        const contenedor = document.getElementById('horarios-disponibles');
        contenedor.innerHTML = '';

        horarios.forEach(hora => {
            const btn = document.createElement('button');
            btn.textContent = hora;
            btn.className = 'bg-pink-500 text-white px-4 py-2 rounded mb-2 w-full';
            btn.onclick = () => {
                horaSeleccionada = hora;

                document.getElementById('input-servicio').value = servicioSeleccionado;
                document.getElementById('input-fecha').value = fechaSeleccionada;
                document.getElementById('input-hora').value = horaSeleccionada;

                document.getElementById('modal-horario').classList.add('hidden');
                document.getElementById('modal-datos').classList.remove('hidden');
            };
            contenedor.appendChild(btn);
        });

        document.getElementById('modal-dia').classList.add('hidden');
        document.getElementById('modal-horario').classList.remove('hidden');
    });
    </script>

@if(session('success'))
    <div id="toast-success"
        class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-green-100 border border-green-400 text-green-800 px-6 py-3 rounded shadow-lg z-50 transition-opacity duration-300 opacity-0">
        {{ session('success') }}
    </div>

    <script>
        // Esperamos a que el DOM esté listo
        document.addEventListener("DOMContentLoaded", function () {
            const toast = document.getElementById('toast-success');
            if (toast) {
                toast.classList.add('opacity-100'); // Mostrar
                setTimeout(() => {
                    toast.classList.remove('opacity-100'); // Ocultar
                    toast.classList.add('opacity-0');
                }, 7000); // 4 segundos visible
            }
        });
    </script>
@endif

</body>
</html>
