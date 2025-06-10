@extends('layouts.cliente')

@section('title', $negocio->nombre)

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <a href="{{ route('cliente.negocios') }}" class="btn btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Volver a negocios
            </a>
            
            <div class="card shadow-sm">
                @if($negocio->imagen)
                <img src="{{ asset('storage/'.$negocio->imagen) }}" class="card-img-top" style="height: 300px; object-fit: cover;">
                @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                    <i class="bi bi-shop text-muted" style="font-size: 4rem;"></i>
                </div>
                @endif
                
                <div class="card-body">
                    <h1 class="card-title">{{ $negocio->nombre }}</h1>
                    <p class="text-muted">
                        <i class="bi bi-person"></i> Propietario: {{ $negocio->usuario->nombre }} {{ $negocio->usuario->apellidoP }}
                    </p>
                    <p class="text-muted">
                        <i class="bi bi-geo-alt"></i> {{ $negocio->direccion }}
                    </p>
                    <p class="text-muted">
                        <i class="bi bi-telephone"></i> {{ $negocio->telefono }}
                    </p>
                    
                    <hr>
                    
                    <h4>Descripción</h4>
                    <p class="card-text">{{ $negocio->descripcion }}</p>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Productos Disponibles</h3>
                <div>
                    <a href="{{ route('cart.index') }}" class="btn btn-primary position-relative">
                        <i class="bi bi-cart"></i> Ver Carrito
                        @if($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $cartCount }}
                        </span>
                        @endif
                    </a>
                </div>
            </div>
            
            @if($negocio->productos->count() > 0)
                <div class="row">
                    @foreach($negocio->productos as $producto)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if($producto->imagen)
                            <img src="{{ asset('storage/'.$producto->imagen) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-box text-muted" style="font-size: 3rem;"></i>
                            </div>
                            @endif
                            
                            <div class="card-body">
                                <h5 class="card-title">{{ $producto->nombre }}</h5>
                                <p class="card-text text-muted small">{{ $producto->descripcion }}</p>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-success">${{ number_format($producto->precio, 2) }}</span>
                                    <span class="badge bg-primary">Stock: {{ $producto->stock }}</span>
                                </div>
                                
                                @if($producto->stock > 0)
                                <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $producto->id }}">
                                    <div class="input-group">
                                        <input type="number" name="cantidad" value="1" min="1" max="{{ $producto->stock }}" 
                                               class="form-control" style="width: 70px;">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-cart-plus"></i> Añadir
                                        </button>
                                    </div>
                                </form>
                                
                                <div id="paypal-button-container-{{ $producto->id }}" class="mt-2"></div>
                                @else
                                <button class="btn btn-outline-secondary w-100" disabled>
                                    <i class="bi bi-exclamation-circle"></i> Sin stock
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Este negocio no tiene productos disponibles actualmente.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para mensajes -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Carrito de compras</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="cart-message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Seguir comprando</button>
                <a href="{{ route('cart.index') }}" class="btn btn-primary">Ver carrito</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}&currency=MXN"></script>

<script>$(document).ready(function() {
    $('.add-to-cart-form').on('submit', function(e) {
        e.preventDefault();
        
        let form = $(this);
        let productId = form.data('product-id');
        let cantidad = form.find('.quantity-input').val();

        $.ajax({
            url: "{{ route('cart.add') }}",
            method: "POST",
            data: {
                id: id,
                cantidad: cantidad,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    alert('Producto añadido al carrito');
                    // Actualizar contador
                    $('.cart-count').text(response.cart_count);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });
});
    </script>
@endsection