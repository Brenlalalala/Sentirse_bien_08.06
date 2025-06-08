
<div id="modal-dia" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h3 class="text-xl font-bold text-pink-600 mb-4">¿Qué día deseas asistir?</h3>
        <input type="date" id="fecha-seleccionada" class="w-full border border-gray-300 rounded-lg p-2 mb-4">
        <div class="flex justify-end space-x-4">
            <button id="cancelar-dia" class="bg-gray-300 text-gray-700 px-4 py-2 rounded">Cancelar</button>
            <button id="confirmar-dia" class="bg-pink-500 text-white px-4 py-2 rounded">Confirmar</button>
        </div>
    </div>
</div>

<div id="modal-horario" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h3 class="text-xl font-bold text-pink-600 mb-4">Selecciona un horario disponible</h3>
        <div id="horarios-disponibles" class="space-y-2">
            <!-- Los horarios se generarán dinámicamente -->
        </div>
        <div class="flex justify-end space-x-4 mt-4">
            <button id="cancelar-horario" class="bg-gray-300 text-gray-700 px-4 py-2 rounded">Cancelar</button>
        </div>
    </div>
</div>

<script>
    let servicioSeleccionado = '';

    function abrirModalDia(servicio) {
        servicioSeleccionado = servicio;
        const modalDia = document.getElementById('modal-dia');
        if (modalDia) {
            modalDia.classList.remove('hidden');
        } else {
            console.error('El modal de selección de día no existe en el DOM.');
        }
    }

    document.getElementById('cancelar-dia').addEventListener('click', () => {
        document.getElementById('modal-dia').classList.add('hidden');
    });

    document.getElementById('confirmar-dia').addEventListener('click', () => {
        const fecha = document.getElementById('fecha-seleccionada').value;
        if (!fecha) {
            alert('Por favor selecciona una fecha.');
            return;
        }

        const horarios = ['10:00 AM', '12:00 PM', '2:00 PM', '4:00 PM', '6:00 PM'];
        const contenedorHorarios = document.getElementById('horarios-disponibles');
        contenedorHorarios.innerHTML = '';

        horarios.forEach(horario => {
            const boton = document.createElement('button');
            boton.textContent = horario;
            boton.className = 'block w-full bg-pink-500 text-white px-4 py-2 rounded mb-2';
            boton.addEventListener('click', () => {
                alert(`Has seleccionado el servicio "${servicioSeleccionado}" para el día ${fecha} a las ${horario}.`);
                document.getElementById('modal-horario').classList.add('hidden');
            });
            contenedorHorarios.appendChild(boton);
        });

        document.getElementById('modal-dia').classList.add('hidden');
        document.getElementById('modal-horario').classList.remove('hidden');
    });

    document.getElementById('cancelar-horario').addEventListener('click', () => {
        document.getElementById('modal-horario').classList.add('hidden');
    });
</script>