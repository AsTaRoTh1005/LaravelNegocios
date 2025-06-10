@extends('layouts.cliente')

@section('title', 'Negocios Disponibles')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4">Negocios Disponibles</h2>
    
    <!-- Buscador -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <div class="input-group">
                <input type="text" id="buscadorNegocios" class="form-control" placeholder="Buscar negocios...">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
            </div>
        </div>
    </div>
    
    <!-- Listado de negocios -->
    <div class="row" id="negocios-container">
        @foreach($negocios as $negocio)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                @if($negocio->imagen)
                <img src="{{ asset('storage/'.$negocio->imagen) }}" class="card-img-top" alt="{{ $negocio->nombre }}" style="height: 200px; object-fit: cover;">
                @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="bi bi-shop text-muted" style="font-size: 3rem;"></i>
                </div>
                @endif
                
                <div class="card-body">
                    <h5 class="card-title">{{ $negocio->nombre }}</h5>
                    <p class="card-text text-muted">
                        <i class="bi bi-geo-alt"></i> {{ Str::limit($negocio->direccion, 30) }}
                    </p>
                    <p class="card-text">{{ Str::limit($negocio->descripcion, 100) }}</p>
                </div>
                
                <div class="card-footer bg-transparent">
                    <a href="{{ route('cliente.negocios.show', $negocio->negocio_id) }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-eye"></i> Ver Detalles
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Buscador de negocios
    const buscador = document.getElementById('buscadorNegocios');
    if(buscador) {
        buscador.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const negocios = document.querySelectorAll('#negocios-container > div');
            
            negocios.forEach(negocio => {
                const text = negocio.textContent.toLowerCase();
                negocio.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });
    }
});
</script>
@endsection
@endsection