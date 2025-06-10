@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
<style>
    /* Estilos anteriores... */
    #buscador {
        max-width: 300px;
        margin-bottom: 1rem;
    }
</style>

<h2 class="text-center mb-4">Usuarios</h2>

<!-- Buscador -->
<div class="d-flex justify-content-between mb-4">
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAgregarUsuario">Agregar Usuario</button>
    <input type="text" id="buscador" class="form-control" placeholder="Buscar usuario...">
</div>

<!-- Tabla de usuarios -->
<div class="table-responsive">
    <table class="table table-bordered table-striped text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaUsuarios">
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id_usuario }}</td>
                <td>{{ $user->nombre }}</td>
                <td>{{ $user->apellidoP }} {{ $user->apellidoM }}</td>
                <td>{{ $user->correo }}</td>
                <td>{{ $user->rol }}</td>
                <td>
                    <button class="btn btn-warning btn-sm editar" data-id="{{ $user->id_usuario }}" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario"><i class="bi bi-pencil-square"></i></button>
                    <button class="btn btn-danger btn-sm eliminar" data-id="{{ $user->id_usuario }}" data-bs-toggle="modal" data-bs-target="#modalEliminarUsuario"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal para agregar usuario -->
<div class="modal fade" id="modalAgregarUsuario" tabindex="-1" aria-labelledby="modalAgregarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarUsuarioLabel">Agregar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarUsuario">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" name="apellidoP" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" name="apellidoM" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" class="form-control" name="correo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rol</label>
                        <select class="form-select" name="rol" required>
                            <option value="Administrador">Administrador</option>
                            <option value="Cliente">Cliente</option>
                            <option value="Vendedor">Vendedor</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar usuario -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarUsuario">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editId" name="id_usuario">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editNombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="editApellidoP" name="apellidoP" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="editApellidoM" name="apellidoM" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" class="form-control" id="editCorreo" name="correo" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rol</label>
                        <select class="form-select" id="editRol" name="rol" required>
                            <option value="Administrador">Administrador</option>
                            <option value="Cliente">Cliente</option>
                            <option value="Vendedor">Vendedor</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para eliminar usuario -->
<div class="modal fade" id="modalEliminarUsuario" tabindex="-1" aria-labelledby="modalEliminarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarUsuarioLabel">Eliminar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este usuario?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formEliminar">
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
    // Buscador
    $('#buscador').on('keyup', function () {
        let valor = $(this).val().toLowerCase(); // Obtener el valor del buscador en minúsculas

        // Filtrar las filas de la tabla
        $('#tablaUsuarios tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
        });
    });

    // AGREGAR USUARIO
    $('#formAgregarUsuario').submit(function (e) {
        e.preventDefault();
        $.post("{{ route('usuario.store') }}", $(this).serialize(), function (data) {
            if (data.success) {
                location.reload();
            }
        });
    });

    // CARGAR DATOS EN MODAL EDITAR
    $(document).on('click', '.editar', function () {
        let id = $(this).data('id');
        $.get(`/usuarios/${id}`, function (data) {
            $('#editId').val(data.id_usuario);
            $('#editNombre').val(data.nombre);
            $('#editApellidoP').val(data.apellidoP);
            $('#editApellidoM').val(data.apellidoM);
            $('#editCorreo').val(data.correo);
            $('#editRol').val(data.rol);
        });
    });

    // EDITAR USUARIO
    $('#formEditarUsuario').submit(function (e) {
        e.preventDefault();
        let id = $('#editId').val();
        $.ajax({
            url: `/usuarios/update/${id}`,
            type: 'PUT',
            data: $(this).serialize(),
            success: function (data) {
                if (data.success) {
                    location.reload();
                }
            }
        });
    });

    $(document).ready(function () {
    $(document).on('click', '.eliminar', function () {
        let id = $(this).data('id'); // 
        $('#formEliminar').attr('action', `/usuarios/delete/${id}`); 
    });
    $('#formEliminar').submit(function (e) {
        e.preventDefault(); 

        $.ajax({
            url: $(this).attr('action'), 
            type: 'DELETE',
            data: $(this).serialize(),
            success: function (data) {
                if (data.success) {
                    var modalEliminar = bootstrap.Modal.getInstance(document.getElementById('modalEliminarUsuario'));
                    modalEliminar.hide();
                    location.reload();
                }
            },
            error: function (response) {
                alert('Error al eliminar el usuario: ' + response.responseJSON.message);
            }
        });
    });
});
});
</script>
@endsection