{{-- Este código muestra los detalles de un servicio individual, 
    incluyendo su nombre, imagen, descripción y precio. --}}


    <!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $servicio->nombre }} - Sentirse bien</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="font-sans bg-gray-50">

    <!-- Header -->
    <header class="bg-white shadow py-6">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-pink-600">{{ $servicio->nombre }}</h1>
        </div>
    </header>

    <!-- Detalle del servicio -->
    <main class="container mx-auto px-4 py-10 max-w-3xl">
        <div class="bg-white rounded-lg shadow-md p-6">
            @if($servicio->imagen)
                <img src="{{ asset('storage/' . $servicio->imagen) }}" alt="{{ $servicio->nombre }}" class="rounded mb-6 w-full">
            @endif

            <p class="text-gray-700 text-lg mb-4">{{ $servicio->descripcion }}</p>

            <p class="text-xl font-bold text-pink-600 mb-4">Precio: ${{ number_format($servicio->precio, 2) }}</p>

            <a href="{{ route('servicios.index') }}" class="inline-block mt-4 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
                ← Volver a servicios
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-200 text-center py-4 mt-10">
        <p>&copy; {{ date('Y') }} Sentirse bien. Todos los derechos reservados.</p>
    </footer>

</body>

</html>
