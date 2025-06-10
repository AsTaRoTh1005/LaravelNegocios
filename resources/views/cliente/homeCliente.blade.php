@extends('layouts.cliente')

@section('title', 'Inicio - Panel de Cliente')

@section('content')
<style>
    /* Estilos generales */
    body {
        font-family: 'Poppins', sans-serif;
        background: #0048c4;
        color: #ffffff;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 800px; /* Ancho máximo reducido para centrar las cards */
        margin: 0 auto;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center; /* Centrar horizontalmente */
    }

    h1 {
        font-size: 2.5rem;
        color: #ffffff; /* Cambiado a blanco para mejor contraste */
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

    .card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        margin-bottom: 2rem;
        animation: fadeIn 2.5s ease-in-out;
        width: 100%; /* Ocupar el 100% del contenedor */
        max-width: 600px; /* Ancho máximo de la card */
        text-align: center; /* Centrar el contenido de la card */
    }

    .card h3 {
        font-size: 1.5rem;
        color: #0048c4;
        margin-bottom: 1rem;
    }

    .card p {
        font-size: 1rem;
        color: #555;
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
    <h1>Bienvenido a Nuestra Plataforma</h1>

    <!-- Sección para Vendedores -->
    <div class="card">
        <h2>Para Vendedores</h2>
        <p>
            Si eres un vendedor, puedes crear tu propio negocio en nuestra plataforma. Una vez registrado, podrás agregar
            productos, gestionar tu inventario y vender directamente a los clientes. ¡Es fácil y rápido!
        </p>
        <a href="{{ route('contactanosC') }}" class="btn btn-primary">Crear Mi Negocio</a>
    </div>

    <!-- Sección para Clientes -->
    <div class="card">
        <h2>Para Clientes</h2>
        <p>
            Explora los negocios registrados en nuestra plataforma y descubre una amplia variedad de productos. Puedes
            entrar en cualquier negocio para ver los productos que ofrecen y realizar tus compras de manera segura.
        </p>
        <a href="" class="btn btn-secondary">Ver Negocios</a>
    </div>

    <!-- Información Adicional -->
    <div class="card">
        <h3>¿Cómo Funciona?</h3>
        <p>
            Nuestra plataforma conecta a vendedores y clientes de manera eficiente. Los vendedores pueden gestionar sus
            negocios y productos, mientras que los clientes pueden explorar y comprar de manera sencilla. ¡Únete a
            nosotros y forma parte de esta comunidad!
        </p>
    </div>
</div>
@endsection