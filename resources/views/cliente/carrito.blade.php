@extends('layouts.cliente')

@section('title', 'Mi Carrito')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="bi bi-cart3"></i> Mi Carrito</h2>

    @if(empty($productos))
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Tu carrito está vacío
        </div>
        <a href="{{ route('cliente.negocios') }}" class="btn btn-primary">
            Ir a negocios
        </a>
    @else
        <!-- Debug Info (Opcional, puedes eliminar en producción) -->
        <div class="alert alert-info small mb-3 d-flex justify-content-between align-items-center">
            <div>
                <strong>Estado:</strong> 
                <span id="debug-status">Verificando...</span>
            </div>
            <button class="btn btn-sm btn-outline-info" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i> Recargar
            </button>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        @foreach($productos as $producto)
                        <div class="row mb-3 border-bottom pb-3">
                            <div class="col-3">
                                @if($producto['imagen'])
                                    <img src="{{ asset('storage/'.$producto['imagen']) }}" 
                                         class="img-fluid rounded" style="max-height: 100px;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 100px; width: 100px;">
                                        <i class="bi bi-box text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-6">
                                <h5>{{ $producto['nombre'] }}</h5>
                                <p class="text-muted mb-1">
                                    Negocio: {{ $producto['negocio'] }}
                                </p>
                                <p class="mb-1">
                                    <strong>${{ number_format($producto['precio'], 2) }}</strong>
                                </p>
                            </div>
                            <div class="col-3">
                                <form action="{{ route('cart.remove', $producto['id']) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                                
                                <form action="{{ route('cart.update', $producto['id']) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="number" name="cantidad" value="{{ $producto['cantidad'] }}" 
                                           min="1" class="form-control d-inline-block" style="width: 60px;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary mt-1">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="card-title">Resumen</h5>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong>${{ number_format($total, 2) }}</strong>
                        </div>
                        
                        <!-- Contenedor PayPal con estados -->
                        <div id="payment-container">
                            <div id="paypal-button-container" class="mb-2"></div>
                            <div id="paypal-alternative" class="d-none">
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle"></i> No se pudo cargar PayPal
                                    <button onclick="window.location.reload()" class="btn btn-sm btn-outline-dark ms-2">
                                        <i class="bi bi-arrow-clockwise"></i> Recargar
                                    </button>
                                </div>
                            </div>
                            <div id="paypal-loading" class="text-center py-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2">Configurando métodos de pago</p>
                            </div>
                        </div>
                        
                        <a href="{{ route('cliente.negocios') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Seguir comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
@if(!empty($productos) && $total > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos UI
    const paymentContainer = document.getElementById('payment-container');
    const paypalButtonContainer = document.getElementById('paypal-button-container');
    const paypalAlternative = document.getElementById('paypal-alternative');
    const paypalLoading = document.getElementById('paypal-loading');
    const debugStatus = document.getElementById('debug-status');

    // 1. Configuración inicial
    paypalButtonContainer.classList.add('d-none');
    paypalLoading.classList.remove('d-none');
    debugStatus.textContent = 'Inicializando pago...';

    // 2. Carga dinámica del SDK
    const loadPayPalSDK = () => {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = `https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}&currency=MXN&components=buttons&intent=capture`;
            script.onload = () => {
                if (typeof paypal !== 'undefined') {
                    resolve();
                } else {
                    reject(new Error('SDK no se cargó correctamente'));
                }
            };
            script.onerror = () => reject(new Error('Error al cargar el SDK de PayPal'));
            document.head.appendChild(script);
        });
    };

    // 3. Inicialización del botón
    const initPayPalButton = () => {
        try {
            paypal.Buttons({
                style: {
                    layout: 'vertical',
                    color: 'gold',
                    shape: 'rect',
                    label: 'paypal',
                    height: 45
                },
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '{{ number_format($total, 2, '.', '') }}',
                                currency_code: 'MXN',
                                breakdown: {
                                    item_total: {
                                        value: '{{ number_format($total, 2, '.', '') }}',
                                        currency_code: 'MXN'
                                    }
                                }
                            },
                            items: [
                                @foreach($productos as $producto)
                                {
                                    name: '{{ addslashes($producto['nombre']) }}'.substring(0, 127),
                                    unit_amount: {
                                        value: '{{ number_format($producto['precio'], 2, '.', '') }}',
                                        currency_code: 'MXN'
                                    },
                                    quantity: '{{ $producto['cantidad'] }}'
                                },
                                @endforeach
                            ]
                        }],
                        application_context: {
                            shipping_preference: 'NO_SHIPPING',
                            brand_name: '{{ config('app.name') }}'
                        }
                    });
                },
                onApprove: function(data, actions) {
                    showProcessingState();
                    return actions.order.capture().then(processPayment);
                },
                onError: handlePayPalError,
                onClick: function() {
                    console.log('PayPal button clicked');
                }
            }).render('#paypal-button-container');
            
            return true;
        } catch (error) {
            console.error('Error al renderizar PayPal:', error);
            return false;
        }
    };

    const showProcessingState = () => {
        paypalButtonContainer.innerHTML = `
            <div class="alert alert-info d-flex align-items-center">
                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                Procesando tu pago...
            </div>
        `;
    };
    const processPayment = async (details) => {
    showProcessingState();
    
    try {
        const response = await fetch("{{ route('paypal.execute') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                paymentID: details.id,
                payerID: details.payer.payer_id,
                orderData: details
            })
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error en el servidor');
        }

        const data = await response.json();
        
        if (data.success && data.redirect) {
            window.location.href = data.redirect;
        } else {
            throw new Error(data.message || 'Respuesta inesperada del servidor');
        }
    } catch (error) {
        console.error('Error en processPayment:', error);
        
        paypalButtonContainer.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> ${error.message}
                <div class="mt-2 small">ID de transacción: ${details.id}</div>
                <button onclick="window.location.reload()" class="btn btn-sm btn-warning mt-2">
                    <i class="bi bi-arrow-clockwise"></i> Reintentar
                </button>
            </div>
        `;
        
        debugStatus.textContent = 'Error en el pago';
    }
};

    const handlePaymentError = (error) => {
        paypalButtonContainer.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> ${error.message}
                <button onclick="window.location.reload()" class="btn btn-sm btn-warning ms-2">
                    <i class="bi bi-arrow-clockwise"></i> Reintentar
                </button>
            </div>
        `;
        debugStatus.textContent = 'Error en el pago';
    };

    const handlePayPalError = (err) => {
        console.error('Error PayPal:', err);
        paypalButtonContainer.classList.add('d-none');
        paypalAlternative.classList.remove('d-none');
        debugStatus.textContent = 'Mostrando alternativa de pago';
    };

    // 5. Flujo principal
    loadPayPalSDK()
        .then(() => {
            debugStatus.textContent = 'SDK cargado, iniciando botón...';
            if (initPayPalButton()) {
                paypalLoading.classList.add('d-none');
                paypalButtonContainer.classList.remove('d-none');
                debugStatus.textContent = 'Listo para pagar';
            } else {
                throw new Error('Error al iniciar PayPal');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            handlePayPalError(error);
            paypalLoading.classList.add('d-none');
        });

    setTimeout(() => {
        if (paypalButtonContainer.classList.contains('d-none') && 
            paypalAlternative.classList.contains('d-none')) {
            handlePayPalError(new Error('Tiempo de espera agotado'));
            paypalLoading.classList.add('d-none');
        }
    }, 10000); 
});
</script>
@endif