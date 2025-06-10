@extends('layouts.vendedor')

@section('title', 'Mi Negocio')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Perfil de Mi Negocio</h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        @if($negocio->imagen)
                            <img src="{{ asset('storage/'.$negocio->imagen) }}" class="img-fluid rounded" style="max-height: 200px;">
                        @else
                            <div class="bg-light p-5 text-muted rounded">
                                <i class="bi bi-image" style="font-size: 3rem;"></i>
                                <p class="mt-2">Sin imagen</p>
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('vendedor.negocio.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre del Negocio</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $negocio->nombre) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono', $negocio->telefono) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required>{{ old('descripcion', $negocio->descripcion) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion', $negocio->direccion) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="imagen" class="form-label">Cambiar Imagen</label>
                            <input class="form-control" type="file" id="imagen" name="imagen" accept="image/*">
                            <div class="form-text">Formatos aceptados: JPEG, PNG, JPG, GIF. Máx. 2MB</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection