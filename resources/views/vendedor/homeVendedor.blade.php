@extends('layouts.vendedor')

@section('title', 'Inicio - Panel de Vendedor')

@section('content')

<style>
    /* Estilos generales */
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #0048c4, #fdf50a);
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
        color: #ffffff; /* Título en blanco para contraste */
        text-align: center;
        margin-bottom: 2rem;
        animation: slideDown 1s ease-in-out;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem; /* Espacio entre las cards */
        justify-content: center; /* Centrar las cards */
    }

    .card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 500px; /* Ancho máximo de cada card */
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        animation: fadeIn 1.5s ease-in-out;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    }

    .card h2 {
        font-size: 2rem;
        color: #0048c4;
        margin-bottom: 1.5rem;
    }

    .card p {
        font-size: 1.1rem;
        color: #555;
        margin-bottom: 1.5rem;
    }

    .btn {
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        font-size: 1rem;
        cursor: pointer;
        transition: transform 0.3s ease, background 0.3s ease;
    }

    .btn-primary {
        background: #0048c4;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        background: #003366;
    }

    .btn-secondary {
        background: #fdf50a;
        color: #0048c4;
        border: none;
    }

    .btn-secondary:hover {
        transform: translateY(-3px);
        background: #e6c200;
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

    <div class="row">
        <!-- Card para Ver Negocios -->
        <div class="card">
            <h2>Ver Productos</h2>
            <p>
                Gestiona y revisa todos los productos registrados en la plataforma. Puedes ver detalles, aprobar nuevos
                productos y realizar los ajustes necesarios.
            </p>
            <a href="{{ route('producto.index') }}" class="btn btn-primary">Ver Productos</a>
        </div>

        <!-- Card para Ver Usuarios -->
        <div class="card">
            <h2>Contactanos</h2>
            <p>
                ¿Tienes alguna duda o sugerencia? No dudes en contactarnos mediante correo electronico. 
                Envia tu mensaje claro y detallado para que podamos darte la mejor atención.
            </p>
            <a href="{{ route('contactanosV') }}" class="btn btn-secondary">Contactanos</a>
        </div>
    </div>
</div>
@endsection