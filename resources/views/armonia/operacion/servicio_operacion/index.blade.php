@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Servicios Operacion Y mantenimiento</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Botones de acción -->
                        <div style="margin-top: 15px;">
                            <a href="{{ route('servicio_operacion.index') }}" class="btn btn-danger">
                                <i class="bi bi-arrow-return-left"></i> Volver
                            </a>

                            @can('crear-servicio_anexo_30')
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#generarServicioModal">
                                    Generar Nuevo Servicio
                                </button>
                            @endcan
                        </div>

                        <!-- Filtro de usuario si el usuario es Administrador o Auditor -->
                        @if(auth()->check() && auth()->user()->hasAnyRole('Administrador', 'Auditor'))
                            <div style="margin-top: 15px;">
                                <label for="filtroUsuario">Filtrar por Usuario:</label>
                                <select id="filtroUsuario" class="form-control">
                                    <option value="todos">Todos los usuarios</option>
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
                                        <th scope="col">Pago</th>
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
                                            <td scope="row">Anexar Pago</td>
                                            <td scope="row">
                                                @if(!$servicio->cotizacion || !$servicio->cotizacion->estado_cotizacion || $servicio->pending_deletion_servicio)
                                                    <button class="btn btn-primary" disabled>
                                                        <i class="bi bi-file-earmark-excel-fill"></i>
                                                    </button>
                                                @else
                                                    <a href="{{ route('descargar.cotizacion.ajax') }}?rutaDocumento={{ urlencode($servicio->cotizacion->rutadoc_cotizacion) }}"
                                                        class="btn btn-primary btn-descargar-pdf"
                                                        data-carpeta="{{ $servicio->nomenclatura }}">
                                                        <i class="bi bi-file-earmark-check-fill"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td scope="row">
                                                @if(!$servicio->pending_apro_servicio || $servicio->pending_deletion_servicio || !$servicio->id)
                                                    <button class="btn btn-primary" disabled><i
                                                            class="bi bi-folder-fill"></i></button>
                                                @else
                                                    <a href="{{ route('expediente.operacion', ['slug' => $servicio->id]) }}"
                                                        class="btn btn-primary">
                                                        <i class="bi bi-folder-fill"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td scope="row">
                                                @if(!$servicio->pending_apro_servicio || $servicio->pending_deletion_servicio || !$servicio->slug)
                                                    <button class="btn btn-primary" disabled><i
                                                            class="bi bi-folder-fill"></i></button>
                                                @else
                                                    <a href="{{ route('listas.anexo30', ['slug' => $servicio->slug]) }}"
                                                        class="btn btn-primary">
                                                        <i class="bi bi-folder-fill"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td scope="row">
                                                @can('borrar-servicio_anexo_30')
                                                    @if($servicio->pending_deletion_servicio)
                                                        <button class="btn btn-danger" disabled>(pendiente)</button>
                                                    @else
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['servicio_inspector_anexo_30.destroy', $servicio->id], 'style' => 'display:inline']) !!}
                                                        {!! Form::button('<i class="bi bi-trash-fill"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'title' => 'Eliminar']) !!}
                                                        {!! Form::close() !!}
                                                    @endif
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">No se encontraron servicios para mostrar.</td>
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

    <!-- Formulario con soporte AJAX -->
    <div class="modal fade" id="generarServicioModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Generar
                        Servicio Anexo 30</h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                        aria-label="Close"></button>

                </div>


                <div class="modal-body">
                    <!-- Formulario de generación de expediente -->
                    <form id="generateWordForm" action="{{ route('servicio_operacion.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <h5 class="modal-title" style="padding-top: 10px;">Seleccione una estacion a la cual se le
                            anexara su servicio</h5>
                        <div class="row">
                            <!-- Select dentro del formulario -->
                            <div class="form-group" style="padding-top: 10px;">
                                <select name="estacion" class="form-select" id="estacion">
                                    <option value="">Selecciona una estación</option>
                                    @foreach ($estaciones as $estacion)
                                        <option value="{{ $estacion->id }}">
                                            {{ $estacion->razon_social }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center" style="padding-top: 10px;">
                            <button type="submit" class="btn btn-primary btn-generar">Generar</button>
                        </div>
                </div>
                </form>

            </div>
        </div>
    </div>
    </div>
</section>

<!-- Script para el filtro de usuario -->
<!-- Incluir jQuery y Bootstrap, preferiblemente desde un CDN para aprovechar el caché del navegador -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>
<script>
    document.getElementById('filtroUsuario').addEventListener('change', function () {
        var usuarioId = this.value;
        var url = '{{ route("servicio_inspector_anexo_30.obtenerServicios") }}';

        // Realizar una solicitud AJAX
        var xhr = new XMLHttpRequest();

        // Construir la URL de la solicitud AJAX
        if (usuarioId !== '') {
            url += '?usuario_id=' + usuarioId;
        }

        xhr.open('GET', url, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Actualizar el contenido de la tabla con los servicios obtenidos
                document.getElementById('tablaServicios').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    });

    $(document).ready(function () {
        $('.btn-descargar-pdf').click(function (event) {
            // Prevenir el comportamiento predeterminado del enlace (navegación)
            event.preventDefault();

            // Obtener la ruta del documento desde el atributo href del enlace
            var rutaDocumento = $(this).attr('href');

            // Obtener el nombre de la carpeta desde el atributo data-carpeta del enlace
            var carpeta = $(this).data('carpeta');

            // Construir el nombre de archivo para la descarga
            var nombreArchivo = 'Cotizacion_' + carpeta + '.pdf';

            // Crear un elemento <a> temporal y simular clic para descargar el archivo
            var link = document.createElement('a');
            link.href = rutaDocumento;
            link.setAttribute('download', nombreArchivo);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    });
</script>

@endsection