@extends('layouts.cliente')

@section('title', 'Sobre Nosotros - SUPER MARKET')

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

    .card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        margin-bottom: 2rem;
        animation: fadeIn 2.5s ease-in-out;
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
    <h1>Sobre Nosotros</h1>

    <!-- Información sobre la plataforma -->
    <div class="card">
        <h2>¿Qué es SUPER MARKET?</h2>
        <p>
            <strong>SUPER MARKET</strong> es una plataforma en línea que conecta a vendedores y clientes de manera fácil y eficiente. Nuestro objetivo es brindar a los vendedores las herramientas necesarias para crear y gestionar sus negocios, mientras que los clientes pueden explorar y comprar productos de manera segura y conveniente.
        </p>
    </div>

    <!-- Información para vendedores -->
    <div class="card">
        <h2>Para Vendedores</h2>
        <p>
            Si eres un vendedor, <strong>SUPER MARKET</strong> te ofrece la oportunidad de crear tu propio negocio en línea. Con nuestra plataforma, puedes:
        </p>
        <ul>
            <li>Crear y personalizar tu tienda en línea.</li>
            <li>Agregar y gestionar tus productos.</li>
            <li>Vender directamente a los clientes.</li>
            <li>Administrar tus ventas y pedidos.</li>
        </ul>
        <p>
            ¡Es fácil, rápido y seguro! Únete a nuestra comunidad de vendedores y lleva tu negocio al siguiente nivel.
        </p>
    </div>

    <!-- Información para clientes -->
    <div class="card">
        <h2>Para Clientes</h2>
        <p>
            Como cliente, <strong>SUPER MARKET</strong> te permite explorar una amplia variedad de negocios y productos. Con nuestra plataforma, puedes:
        </p>
        <ul>
            <li>Buscar y descubrir negocios locales.</li>
            <li>Ver los productos que ofrecen los vendedores.</li>
            <li>Realizar compras de manera segura y rápida.</li>
            <li>Disfrutar de una experiencia de compra única.</li>
        </ul>
        <p>
            Explora, compra y apoya a los negocios locales desde la comodidad de tu hogar.
        </p>
    </div>

    <!-- Misión y visión -->
    <div class="card">
        <h2>Nuestra Misión y Visión</h2>
        <p>
            <strong>Misión:</strong> Facilitar el comercio en línea entre vendedores y clientes, brindando una plataforma segura, fácil de usar y llena de oportunidades para todos.
        </p>
        <p>
            <strong>Visión:</strong> Ser la plataforma líder en comercio electrónico, conectando a miles de vendedores y clientes en un solo lugar.
        </p>
    </div>
</div>
@endsection