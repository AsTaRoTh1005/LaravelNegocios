@extends('layouts.admin')

@section('title', 'Negocios')

@section('content')
<style>
    #buscador {
        max-width: 300px;
        margin-bottom: 1rem;
    }
    .img-thumbnail {
        max-width: 100px;
        max-height: 100px;
    }
</style>

<h2 class="text-center mb-4">Negocios</h2>

<!-- Buscador y botón agregar -->
<div class="d-flex justify-content-between mb-4">
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAgregarNegocio">Agregar Negocio</button>
    <input type="text" id="buscador" class="form-control" placeholder="Buscar negocio...">
</div>

<!-- Tabla de negocios -->
<div class="table-responsive">
    <table class="table table-bordered table-striped text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Dueño</th>
                <th>Descripción</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaNegocios">
            @foreach ($negocios as $negocio)
            <tr>
                <td>{{ $negocio->negocio_id }}</td>
                <td>{{ $negocio->nombre }}</td>
                <td>{{ $negocio->usuario->nombre ?? 'N/A' }}</td>
                <td>{{ Str::limit($negocio->descripcion, 30) }}</td>
                <td>{{ Str::limit($negocio->direccion, 20) }}</td>
                <td>{{ $negocio->telefono }}</td>
                <td>
                    @if($negocio->imagen)
                        <img src="{{ asset('storage/'.$negocio->imagen) }}" class="img-thumbnail">
                    @else
                        <span class="text-muted">Sin imagen</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-warning btn-sm editar" data-id="{{ $negocio->negocio_id }}" data-bs-toggle="modal" data-bs-target="#modalEditarNegocio">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-danger btn-sm eliminar" data-id="{{ $negocio->negocio_id }}" data-bs-toggle="modal" data-bs-target="#modalEliminarNegocio">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal para agregar negocio -->
<div class="modal fade" id="modalAgregarNegocio" tabindex="-1" aria-labelledby="modalAgregarNegocioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarNegocioLabel">Agregar Negocio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarNegocio" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Cliente Dueño</label>
                        <select class="form-select" name="id_usuario" required>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id_usuario }}">
                                    {{ $cliente->nombre }} {{ $cliente->apellidoP }} ({{ $cliente->correo }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre del Negocio</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="direccion" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Imagen</label>
                        <input type="file" class="form-control" name="imagen" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar negocio -->
<div class="modal fade" id="modalEditarNegocio" tabindex="-1" aria-labelledby="modalEditarNegocioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarNegocioLabel">Editar Negocio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarNegocio" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editId" name="negocio_id">
                    <div class="mb-3">
                        <label class="form-label">Nombre del Negocio</label>
                        <input type="text" class="form-control" id="editNombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" id="editDescripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="editDireccion" name="direccion" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="editTelefono" name="telefono" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Imagen Actual</label>
                        <div id="imagenActual" class="mb-2"></div>
                        <label class="form-label">Cambiar Imagen</label>
                        <input type="file" class="form-control" name="imagen" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para eliminar negocio -->
<div class="modal fade" id="modalEliminarNegocio" tabindex="-1" aria-labelledby="modalEliminarNegocioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarNegocioLabel">Eliminar Negocio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este negocio? Todos los productos asociados también serán eliminados.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formEliminarNegocio">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="confirmarEliminar">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    // Inicializar modales
    const modalAgregar = new bootstrap.Modal(document.getElementById('modalAgregarNegocio'));
    const modalEditar = new bootstrap.Modal(document.getElementById('modalEditarNegocio'));
    const modalEliminar = new bootstrap.Modal(document.getElementById('modalEliminarNegocio'));

    // Buscador
    $('#buscador').on('keyup', function () {
        let valor = $(this).val().toLowerCase();
        $('#tablaNegocios tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
        });
    });

    // AGREGAR NEGOCIO
    $('#formAgregarNegocio').submit(function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        let $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...');
        
        $.ajax({
            url: "{{ route('negocio.store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                modalAgregar.hide();
                location.reload(); // Recargar la página para ver los cambios
            },
            error: function (xhr) {
                let errorMsg = xhr.responseJSON?.message || 'Error al agregar el negocio';
                alert(errorMsg);
            },
            complete: function() {
                $btn.prop('disabled', false).html('Guardar');
            }
        });
    });

    // CARGAR DATOS EN MODAL EDITAR
    $(document).on('click', '.editar', function () {
        let id = $(this).data('id');
        $.get(`/negocios/${id}`, function (data) {
            $('#editId').val(data.negocio_id);  
            $('#editNombre').val(data.nombre);
            $('#editDescripcion').val(data.descripcion);
            $('#editDireccion').val(data.direccion);
            $('#editTelefono').val(data.telefono);
            
            // Mostrar imagen actual
            let imagenHtml = data.imagen 
                ? `<img src="/storage/${data.imagen}" class="img-thumbnail">`
                : '<span class="text-muted">Sin imagen</span>';
            $('#imagenActual').html(imagenHtml);
        }).fail(function() {
            alert('Error al cargar los datos del negocio');
        });
    });

    // EDITAR NEGOCIO
    $('#formEditarNegocio').submit(function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        let id = $('#editId').val();
        let $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Actualizando...');
        
        $.ajax({
            url: `/negocios/update/${id}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function (response) {
                modalEditar.hide();
                location.reload(); // Recargar la página para ver los cambios
            },
            error: function (xhr) {
                let errorMsg = xhr.responseJSON?.message || 'Error al actualizar el negocio';
                alert(errorMsg);
            },
            complete: function() {
                $btn.prop('disabled', false).html('Actualizar');
            }
        });
    });

    // ELIMINAR NEGOCIO
    $(document).on('click', '.eliminar', function () {
        let id = $(this).data('id');
        $('#formEliminarNegocio').attr('action', `/negocios/delete/${id}`);
    });
    
    $('#formEliminarNegocio').submit(function (e) {
        e.preventDefault();
        let $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Eliminando...');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-HTTP-Method-Override': 'DELETE'
            },
            success: function (response) {
                modalEliminar.hide();
                location.reload();
            },
            error: function (xhr) {
                let errorMsg = xhr.responseJSON?.message || 'Error en la solicitud';
                alert(errorMsg);
            },
            complete: function() {
                $btn.prop('disabled', false).html('Eliminar');
            }
        });
    });

    // Limpiar formularios al cerrar modales
    $('#modalAgregarNegocio').on('hidden.bs.modal', function () {
        $('#formAgregarNegocio')[0].reset();
    });
    
    $('#modalEditarNegocio').on('hidden.bs.modal', function () {
        $('#formEditarNegocio')[0].reset();
    });
});
</script>
@endsection