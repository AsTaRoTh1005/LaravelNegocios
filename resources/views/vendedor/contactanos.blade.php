@extends('layouts.vendedor')

@section('title', 'Contáctanos - SUPER MARKET')

@section('content')
<style>
    /* Estilos generales */
    body {
        font-family: 'Poppins', sans-serif;
        background: #f8f9fa;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    h1 {
        font-size: 2.5rem;
        color: #0048c4;
        text-align: center;
        margin-bottom: 2rem;
        animation: slideDown 1s ease-in-out;
    }

    h2 {
        font-size: 2rem;
        color: #fdf50a;
        margin-bottom: 1.5rem;
        animation: fadeIn 1.5s ease-in-out;
    }

    p {
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        animation: fadeIn 2s ease-in-out;
    }

    .contact-form {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        max-width: 600px;
        margin: 0 auto;
        animation: fadeIn 2.5s ease-in-out;
    }

    .contact-form input,
    .contact-form textarea {
        width: 100%;
        padding: 0.8rem;
        margin-bottom: 1rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .contact-form input:focus,
    .contact-form textarea:focus {
        border-color: #0048c4;
        box-shadow: 0 0 8px rgba(0, 72, 196, 0.5);
        outline: none;
    }

    .contact-form textarea {
        resize: vertical;
    }

    .contact-form button {
        padding: 0.8rem 1.5rem;
        background: #0048c4;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        cursor: pointer;
        transition: transform 0.3s ease, background 0.3s ease;
    }

    .contact-form button:hover {
        transform: translateY(-3px);
        background: #003366;
    }

    /* Animaciones */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>

<div class="container">
    <h1>Contáctanos</h1>

    <!-- Información referente -->
    <div class="info-section">
        <h2>¿Necesitas ayuda?</h2>
        <p>
            En <span>SUPER MARKET</span>, estamos comprometidos a brindarte la mejor experiencia. Si tienes alguna pregunta, sugerencia o
            necesitas asistencia, no dudes en contactarnos. Nuestro equipo estará encantado de ayudarte.
        </p>
        <p>
            Puedes enviarnos un mensaje directamente desde este formulario y te responderemos a la brevedad posible.
        </p>
    </div>

    <!-- Formulario de contacto -->
    <div class="contact-form">
        <form action="{{ route('enviar.consulta') }}" method="POST">
            @csrf
            <input type="text" name="nombre" value="{{ Auth::user()->nombre }}" readonly>
            <input type="email" name="email" value="{{ Auth::user()->correo }}" readonly>
            <textarea name="mensaje" rows="5" placeholder="Tu mensaje" required></textarea>
            <button type="submit">Enviar mensaje</button>
        </form>
    </div>
</div>
@endsection