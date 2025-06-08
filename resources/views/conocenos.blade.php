<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Conócenos - Sentirse Bien</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Lora:wght@400;600&display=swap');

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow-y: auto;
            font-family: 'Lora', serif;
            background: linear-gradient(to bottom, #ffdce0, #ff85a2);
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 2rem;
        }

        h2 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            color: #ffffff;
            text-shadow: 2px 2px 10px rgba(255, 133, 162, 0.7);
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 1s ease-out, transform 1s ease-out;
        }

        .fade-in-title {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        p {
            font-size: 1.5rem;
            color: #ff85a2;
        }

        .btn-primary {
            font-size: 1.8rem;
            font-weight: bold;
            color: #ffffff;
            background: linear-gradient(to right, #ff85a2, #d63384);
            padding: 1rem 2rem;
            border-radius: 12px;
            box-shadow: 0px 0px 15px rgba(255, 133, 162, 0.7);
            transition: all 0.3s ease-in-out;
            display: inline-block;
            text-align: center;
        }

        .btn-primary:hover {
            box-shadow: 0px 0px 20px rgba(255, 133, 162, 0.9);
            transform: scale(1.07);
            background: linear-gradient(to right, #d63384, #ff85a2);
        }

        .card {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 1s ease-out, transform 1s ease-out;
        }

        .fade-in-card {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        .flex-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
            justify-content: space-between;
        }

        .flex-item {
            flex: 1;
            min-width: 300px;
        }

        /* === NUEVOS EFECTOS === */

        /* Aparece al hacer scroll */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Imagen con animación */
        .slide-in-left {
            opacity: 0;
            transform: translateX(40px);
            animation: slideLeft 1s ease forwards;
        }

        @keyframes slideLeft {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Tarjetas con hover animado */
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll("h2").forEach(title => {
                setTimeout(() => {
                    title.classList.add("fade-in-title");
                }, 200);
            });

            document.querySelectorAll(".card").forEach(card => {
                setTimeout(() => {
                    card.classList.add("fade-in-card");
                }, 400);
            });

            // Animación al hacer scroll
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
        });
    </script>
</head>

<body class="bg-white text-gray-800">

<!-- Sección de presentación con la Dra. Felicidad -->
<section class="container text-center">
    <div class="flex-container">
        <!-- Texto alineado a la izquierda -->
        <div class="flex-item text-left w-1/2 fade-in">
            <h2 style="color: #ffffff; text-shadow: 2px 2px 8px rgba(255, 133, 162, 0.7);">
                ¿Qué es Sentirse Bien Spa?
            </h2>
            <p style="color: #fffaf5; text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);">
                Un equipo guiado por la experiencia, comprometido con tu bienestar.
            </p>
            <p style="color: #fffaf5; text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);">
                Fundado por la <strong>Dra. Felicidad</strong>, reconocida profesional
                en salud y estética, nuestro spa une innovación y calidez humana.
            </p>
            <a href="{{ route('servicios.index') }}" class="btn-primary mt-6">Reserva tu Experiencia</a>


        </div>

        <!-- Imagen alineada a la derecha -->
        <div class="flex-item w-1/2 flex justify-end">
            <img src="{{ asset('imagenes/dra_felicidad_puerta.png') }}" alt="Dra. Felicidad invitando a ingresar"
                 class="rounded-lg shadow-md w-full max-w-md slide-in-left" loading="lazy">
        </div>
    </div>
</section>

<!-- Información de la Empresa en tarjetas -->
<section class="container">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
        <div class="card fade-in">
            <h2 class="text-pink-700 mb-3">Origen</h2>
            <p>Nacimos con la misión de transformar el bienestar en una experiencia única.</p>
        </div>

        <div class="card fade-in">
            <h2 class="text-pink-800 mb-3">Objetivos</h2>
            <p>Brindar un servicio integral que combine salud, estética y relajación.</p>
        </div>

        <div class="card fade-in">
            <h2 class="text-pink-900 mb-3">Visión</h2>
            <p>Ser líderes en el sector, creando un espacio donde cada persona se sienta cuidada y renovada.</p>
        </div>
    </div>

    <div class="card mt-8 text-center fade-in">
        <h2 class="text-pink-700 mb-3">¿Por qué elegirnos?</h2>
        <p>Técnicas innovadoras, experiencia clínica y un trato excepcional.</p>
        <a href="{{ route('contacto') }}" class="btn-primary mt-4">Contáctanos</a>

    </div>
</section>

</body>
</html>
