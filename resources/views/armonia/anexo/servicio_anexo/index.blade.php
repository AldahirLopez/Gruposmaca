@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Servicios Anexo 30</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Botones de acción -->
                        <div style="margin-top: 15px;">
                            <a href="{{ route('anexo.index') }}" class="btn btn-danger">
                                <i class="bi bi-arrow-return-left"></i>
                            </a>

                            @can('crear-servicio')
                                <form action="{{ route('servicio_anexo.store') }}" method="POST" style="display: inline;">
                                    @csrf <!-- Agrega el token CSRF para protección -->
                                    <button type="submit" class="btn btn-success">Generar Nuevo Servicio</button>
                                </form>
                            @endcan
                        </div>

                        <!-- Filtro de usuario si el usuario es Administrador o Auditor -->
                        @if(auth()->check() && auth()->user()->hasAnyRole('Administrador', 'Auditor'))
                            <div style="margin-top: 15px;">
                                <label for="filtroUsuario">Filtrar por Usuario:</label>
                                <select id="filtroUsuario" class="form-control">
                                    <option value="">Todos los usuarios</option>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Mostrar servicios -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div id="tablaServicios">
                            <table class="table table-striped">
                                <thead style="text-align: center;">
                                    <tr>
                                        <th scope="col">Numero de servicio</th>
                                        <th scope="col">Cotizacion</th>
                                        <th scope="col">Expediente</th>
                                        <th scope="col">Listas de Inspeccion</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    @forelse($servicios as $servicio)
                                        <tr>
                                            <td scope="row">{{ $servicio->nomenclatura }}</td>
                                            <td scope="row">
                                                @if(!$servicio->pending_apro_servicio)
                                                    <center>
                                                        <button class="btn btn-primary" disabled><i
                                                                class="bi bi-file-earmark-excel-fill"></i></button>
                                                    </center>
                                                @elseif($servicio->pending_deletion_servicio)
                                                    <center>
                                                        <button class="btn btn-primary" disabled><i
                                                                class="bi bi-file-earmark-excel-fill"></i></button>
                                                    </center>
                                                @else
                                                    <a href="{{ $servicio->cotizacion ? $servicio->cotizacion->ruta_doc_url : '#' }}"
                                                        target="_blank" class="btn btn-primary btn-generar-pdf">
                                                        <i class="bi bi-file-earmark-check-fill"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td scope="row">
                                                @if(!$servicio->pending_apro_servicio)
                                                    <center>
                                                        <button class="btn btn-primary" disabled><i
                                                                class="bi bi-folder-fill"></i></button>
                                                    </center>
                                                @elseif($servicio->pending_deletion_servicio)
                                                    <center>
                                                        <button class="btn btn-primary" disabled><i
                                                                class="bi bi-folder-fill"></i></button>
                                                    </center>
                                                @else
                                                    <a href="{{ route('expediente.anexo30', ['servicio_anexo_id' => $servicio->id]) }}"
                                                        class="btn btn-primary">
                                                        <i class="bi bi-folder-fill"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td scope="row">
                                                @if(!$servicio->pending_apro_servicio)
                                                    <center>
                                                        <button class="btn btn-primary" disabled><i
                                                                class="bi bi-folder-fill"></i></button>
                                                    </center>
                                                @elseif($servicio->pending_deletion_servicio)
                                                    <center>
                                                        <button class="btn btn-primary" disabled><i
                                                                class="bi bi-folder-fill"></i></button>
                                                    </center>
                                                @else
                                                    <a href="{{ route('listas.anexo30', ['servicio_anexo_id' => $servicio->id]) }}"
                                                        class="btn btn-primary">
                                                        <i class="bi bi-folder-fill"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td scope="row">
                                                @if(!$servicio->pending_apro_servicio)

                                                    <button class="btn btn-primary" disabled><i
                                                            class="bi bi-pencil-square"></i></button>

                                                @elseif($servicio->pending_deletion_servicio)

                                                    <button class="btn btn-primary" disabled><i
                                                            class="bi bi-pencil-square"></i></button>

                                                @else
                                                    <!-- Modifica tu botón para usar la clase 'btn-generar-pdf' -->
                                                    <a href="{{ route('archivos.index', ['servicio_id' => $servicio->id]) }}"
                                                        class="btn btn-primary btn-generar-pdf">
                                                        <i class="bi bi-file-earmark-check-fill"></i>
                                                    </a>
                                                @endif
                                                @can('borrar-servicio')
                                                    @if($servicio->pending_deletion_servicio)
                                                        <button class="btn btn-danger" disabled>(pendiente)</button>
                                                    @else
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['servicio_anexo.destroy', $servicio->id], 'style' => 'display:inline']) !!}
                                                        {!! Form::button('<i class="bi bi-trash-fill"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'title' => 'Eliminar']) !!}
                                                        {!! Form::close() !!}
                                                    @endif
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">No se encontraron servicios para mostrar.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script para el filtro de usuario -->
<script>
    document.getElementById('filtroUsuario').addEventListener('change', function () {
        var usuarioId = this.value;
        // Realizar una solicitud AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '{{ route("servicio_anexo.obtenerServicios") }}?usuario_id=' + usuarioId, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Actualizar el contenido de la tabla con los servicios obtenidos
                document.getElementById('tablaServicios').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    });
</script>

@endsection