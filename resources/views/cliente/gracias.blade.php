@extends('layouts.cliente')

@section('title', 'Gracias por tu compra')

@section('content')
<div class="container py-5 text-center">
    <div class="alert alert-success">
        <h2><i class="bi bi-check-circle"></i> ¡Pago completado con éxito!</h2>
        <p class="lead">Tu pedido ha sido procesado correctamente</p>
        <a href="{{ route('cliente.negocios') }}" class="btn btn-primary">
            Volver a la tienda
        </a>
    </div>
</div>
@endsection