<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN (si lo necesitás en entorno local, usá tu build) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div id="formulario-contacto" class="container mx-auto p-8 mt-20">
    <div class="flex flex-wrap -mx-4 items-start">
        
        <!-- Formulario -->
        <div class="w-full md:w-1/2 px-4 mt-8 flex justify-center">
            <form action="{{ route('contacto.enviar') }}" method="POST" id="formularioContacto" class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
                @csrf
                <div class="mb-4">
                    <label for="nombre" class="text-2 font-bold text-pink-500 mb-2">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="telefono" class="text-2 font-bold text-pink-500 mb-2">Número de celular:</label>
                    <input type="tel" name="telefono" id="telefono" class="w-full px-3 py-2 border rounded">
                    <p class="text-xs text-gray-600 mt-1">*Sin incluir el número 0 ni el 15</p>
                </div>
                <div class="mb-4">
                    <label for="mensaje" class="text-2 font-bold text-pink-500 mb-2">Descripción de la consulta:</label>
                    <textarea name="mensaje" id="mensaje" rows="4" class="w-full px-3 py-2 border rounded" required></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Enviar mensaje</button>
                </div>
            </form>
        </div>

        <!-- Información de contacto -->
        <div class="w-full md:w-1/2 px-4 mt-8 flex justify-center">
            <div class="bg-white rounded-lg shadow-md p-6 w-full max-w-sm">
                <h2 class="text-2xl font-bold text-pink-600 mb-4 text-center">Información de contacto</h2>
                <p class="text-gray-700 mb-2"><span class="font-semibold">Dirección:</span> French 414, Ciudad de Resistencia</p>
                <p class="text-gray-700 mb-2"><span class="font-semibold">Teléfono:</span> +123 456 7890</p>

                <!-- Redes sociales -->
                <p class="text-gray-700 mb-2"><span class="font-semibold">Redes sociales:</span></p>
                <div class="flex space-x-4 mt-2">
                    <!-- Facebook -->
                    <a href="https://www.facebook.com" target="_blank" aria-label="Facebook" class="text-blue-600 hover:text-blue-800">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path d="M22 12a10 10 0 10-11.5 9.9v-7H8v-3h2.5V9c0-2.4 1.5-3.8 3.7-3.8 1.1 0 2.2.2 2.2.2v2.5H15c-1.2 0-1.6.8-1.6 1.6v2h2.7l-.4 3h-2.3v7A10 10 0 0022 12z"/>
                        </svg>
                    </a>
                    <!-- Instagram -->
                    <a href="https://www.instagram.com" target="_blank" aria-label="Instagram" class="text-pink-600 hover:text-pink-800">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path d="M7 2C4.8 2 3 3.8 3 6v12c0 2.2 1.8 4 4 4h10c2.2 0 4-1.8 4-4V6c0-2.2-1.8-4-4-4H7zm10 2c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H7c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2h10zm-5 3a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6zm4.5-.5a1 1 0 100 2 1 1 0 000-2z"/>
                        </svg>
                    </a>
                    <!-- Twitter -->
                    <a href="https://twitter.com" target="_blank" aria-label="Twitter" class="text-blue-400 hover:text-blue-600">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path d="M23 3a10.9 10.9 0 01-3.1 1.5 4.48 4.48 0 00-7.7 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
                        </svg>
                    </a>
                </div>

                <!-- Mapa -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Ubicación:</h3>
                    <div class="rounded overflow-hidden shadow-sm">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3540.5827001329344!2d-58.981597625299415!3d-27.451113015913165!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94450cf0ba20d4f7%3A0x931cab3d305f0437!2sC.%20French%20414%2C%20H3500%20Resistencia%2C%20Chaco!5e0!3m2!1ses-419!2sar!4v1746746249881!5m2!1ses-419!2sar" 
                            width="100%" 
                            height="150" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="w-full rounded"
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de respuesta -->
<div id="modalRespuesta" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full text-center border-t-4" id="modalContent">
        <h2 id="modalTitulo" class="text-xl font-bold mb-2 text-pink-600">Título</h2>
        <p id="modalMensaje" class="text-gray-700 mb-4">Mensaje de respuesta</p>
        <button onclick="cerrarModal()" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">
            Cerrar
        </button>
    </div>
</div>

<script>
// Función para mostrar el modal con mensaje personalizado
function mostrarModal(titulo, mensaje, esExito = true) {
    const modal = document.getElementById('modalRespuesta');
    const modalTitulo = document.getElementById('modalTitulo');
    const modalMensaje = document.getElementById('modalMensaje');
    const modalContent = document.getElementById('modalContent');

    modalTitulo.textContent = titulo;
    modalMensaje.textContent = mensaje;

    if (esExito) {
        modalContent.classList.remove('border-t-red-500');
        modalContent.classList.add('border-t-pink-500');
    } else {
        modalContent.classList.remove('border-t-pink-500');
        modalContent.classList.add('border-t-red-500');
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// Función para cerrar el modal
function cerrarModal() {
    const modal = document.getElementById('modalRespuesta');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

// Envío AJAX del formulario
document.getElementById('formularioContacto').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarModal('Consulta enviada', data.message, true);
            form.reset();
        } else {
            mostrarModal('Error al enviar', data.message || 'Error desconocido.', false);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarModal('Error de red', 'Ocurrió un error inesperado. Intenta más tarde.', false);
    });
});
</script>


</body>
</html>
