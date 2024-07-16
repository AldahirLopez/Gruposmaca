@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Servicios operacion y Mantenimiento</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Botones de acción -->
                        <div style="margin-top: 15px;">
                            <a href="{{ route('operacion.index') }}" class="btn btn-danger">
                                <i class="bi bi-arrow-return-left"></i> Volver
                            </a>

                            @can('crear-servicio_operacion_mantenimiento')
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#generarServicioModal">
                                    Generar Nuevo Servicio
                                </button>
                            @endcan
                        </div>

                        <!-- Filtro de usuario si el usuario es Administrador o Auditor -->
                        @if(auth()->check() && auth()->user()->hasAnyRole('Administrador'))
                            <form action="{{ route('servicio-operacion.obtenerServicios') }}" method="GET"
                                class="container mt-4">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="filtroUsuario" class="form-label">Usuario</label>
                                        <select id="filtroUsuario" class="form-select" name="usuario_id">
                                            <option value="todos">Todos los usuarios</option>
                                            @foreach($usuarios as $usuario)
                                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="filtroAño" class="form-label">Año</label>
                                        <select id="filtroAño" class="form-select" name="year">
                                            <option value="selecciona" disabled selected>Selecciona un año</option>
                                            <option value="2024">2024</option>
                                            <option value="2025">2025</option>
                                            <option value="2026">2026</option>
                                            <option value="2027">2027</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="filtroEstado" class="form-label">Estado</label>
                                        <select id="filtroEstado" class="form-select" name="estado">
                                            <option value="selecciona" disabled selected>Selecciona un estado</option>
                                            <option value="Aguascalientes">Aguascalientes</option>
                                            <option value="Baja California">Baja California</option>
                                            <option value="Baja California Sur">Baja California Sur</option>
                                            <option value="Campeche">Campeche</option>
                                            <option value="Chiapas">Chiapas</option>
                                            <option value="Chihuahua">Chihuahua</option>
                                            <option value="Ciudad de México">Ciudad de México</option>
                                            <option value="Coahuila">Coahuila</option>
                                            <option value="Colima">Colima</option>
                                            <option value="Durango">Durango</option>
                                            <option value="Estado de México">Estado de México</option>
                                            <option value="Guanajuato">Guanajuato</option>
                                            <option value="Guerrero">Guerrero</option>
                                            <option value="Hidalgo">Hidalgo</option>
                                            <option value="Jalisco">Jalisco</option>
                                            <option value="Michoacán">Michoacán</option>
                                            <option value="Morelos">Morelos</option>
                                            <option value="Nayarit">Nayarit</option>
                                            <option value="Nuevo León">Nuevo León</option>
                                            <option value="Oaxaca">Oaxaca</option>
                                            <option value="Puebla">Puebla</option>
                                            <option value="Querétaro">Querétaro</option>
                                            <option value="Quintana Roo">Quintana Roo</option>
                                            <option value="San Luis Potosí">San Luis Potosí</option>
                                            <option value="Sinaloa">Sinaloa</option>
                                            <option value="Sonora">Sonora</option>
                                            <option value="Tabasco">Tabasco</option>
                                            <option value="Tamaulipas">Tamaulipas</option>
                                            <option value="Tlaxcala">Tlaxcala</option>
                                            <option value="Veracruz">Veracruz</option>
                                            <option value="Yucatán">Yucatán</option>
                                            <option value="Zacatecas">Zacatecas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">Filtrar</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        @if (auth()->user()->hasRole('Operacion y Mantenimiento'))
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
                                            <th scope="col">Descargar factura</th>
                                            <th scope="col">Pago</th>
                                            <th scope="col">Cotizacion</th>
                                            <th scope="col">Expediente</th>
                                            <th scope="col">Documentacion</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">


                                        @forelse($servicios as $servicio)
                                            <tr>
                                                <td scope="row">{{ $servicio->nomenclatura }}</td>

                                                @if ($servicio->pago === null)
                                                    <td>Subir pago para generar factura</td>
                                                @else   
                                                    @if ($servicio->pago->estado_facturado == true)
                                                            <td>
                                                                <a href="{{ route('descargar.factura.operacion') }}?rutaDocumento={{ urlencode($servicio->pago->factura->rutadoc_factura) }}"
                                                                    class="btn btn-primary btn-descargar-pdf"
                                                                    data-carpeta="{{ $servicio->nomenclatura }}">
                                                                    <i class="bi bi-file-earmark-check-fill"></i>
                                                                </a>
                                                            </td>

                                                    @else
                                                        <td>Generando factura</td>
                                                    @endif                                          @endif

                                                <td scope="row">

                                                    @if ($servicio->pago !== null)
                                                            <button type="button" class="btn btn-success" data-toggle="modal"
                                                                data-target="#agregarDocumentoModal-{{$servicio->nomenclatura }}"
                                                                disabled>
                                                                <i class="bi bi-upload"></i> Agregar
                                                            </button>

                                                    @else
                                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                                                data-target="#agregarDocumentoModal-{{$servicio->nomenclatura }}">
                                                                <i class="bi bi-upload"></i> Agregar
                                                            </button>

                                                    @endif

                                                </td>
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
                                                    @can('borrar-servicio_operacion_mantenimiento')
                                                        @if($servicio->pending_deletion_servicio)
                                                            <button class="btn btn-danger" disabled>(pendiente)</button>
                                                        @else
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['servicio_operacion.destroy', $servicio->id], 'style' => 'display:inline']) !!}
                                                            {!! Form::button('<i class="bi bi-trash-fill"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'title' => 'Eliminar']) !!}
                                                            {!! Form::close() !!}
                                                        @endif
                                                    @endcan
                                                </td>
                                            </tr>

                                            <!-- Modal para agregar documento -->
                                            <div class="modal fade" id="agregarDocumentoModal-{{$servicio->nomenclatura }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="agregarDocumentoLabel-{{$servicio->nomenclatura }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header"
                                                                style="background-color: #007bff; color: #ffffff;">
                                                                <h5 class="modal-title"
                                                                    id="agregarDocumentoLabel-{{ $servicio->nomenclatura }}">
                                                                    Agregar Pago:{{$servicio->nomenclatura}}</h5>
                                                                <button type="button" class="btn-close btn-close-white"
                                                                    data-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('pago_operacion.store') }}" method="POST"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label for="rutadoc">Seleccionar Archivo</label>
                                                                        <input type="file" name="rutadoc" class="form-control"
                                                                            required>
                                                                    </div>
                                                                    <input type="hidden" name="servicio_id"
                                                                        value="{{ $servicio->id }}">
                                                                    <input type="hidden" name="nomenclatura"
                                                                        value="{{ $servicio->nomenclatura }}">

                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Cerrar</button>
                                                                        <button type="submit" class="btn btn-primary">Agregar
                                                                            pago</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- FIN Modal para agregar documento -->
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
        @endif


        @if (session('servicios'))
                @if (session('año') || session('estado') || session('usuario'))
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            Datos del Filtro
                        </div>
                        <div class="card-body">
                            @if(session('usuario'))
                                <p><strong>Usuario:</strong> {{ session('usuario')->name }}</p>
                            @endif
                            @if(session('año'))
                                <p><strong>Año:</strong> {{ session('año') }}</p>
                            @endif
                            @if(session('estado'))
                                <p><strong>Estado:</strong> {{ session('estado') }}</p>
                            @endif
                        </div>
                    </div>
                @endif


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
                                                <th scope="col">Descargar factura</th>
                                                <th scope="col">Pago</th>
                                                <th scope="col">Cotizacion</th>
                                                <th scope="col">Expediente</th>
                                                <th scope="col">Documentacion</th>
                                                <th scope="col">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody style="text-align: center;">


                                            @forelse(session('servicios') as $servicio)
                                                <tr>
                                                    <td scope="row">{{ $servicio->nomenclatura }}</td>

                                                    @if ($servicio->pago === null)
                                                        <td>Subir pago para generar factura</td>
                                                    @else   
                                                        @if ($servicio->pago->estado_facturado == true)
                                                            <td>
                                                                <a href="{{ route('descargar.factura.operacion') }}?rutaDocumento={{ urlencode($servicio->pago->factura->rutadoc_factura) }}"
                                                                    class="btn btn-primary btn-descargar-factura"
                                                                    data-carpeta="{{ $servicio->nomenclatura }}">
                                                                    <i class="bi bi-file-earmark-check-fill"></i>
                                                                </a>
                                                            </td>

                                                        @else
                                                            <td>Generando factura</td>
                                                        @endif                                          @endif


                                                    <td scope="row">

                                                        @if ($servicio->pago !== null)
                                                            <button type="button" class="btn btn-success" data-toggle="modal"
                                                                data-target="#agregarDocumentoModal-{{$servicio->nomenclatura }}"
                                                                disabled>
                                                                <i class="bi bi-upload"></i> Agregar
                                                            </button>

                                                        @else
                                                            <button type="button" class="btn btn-success" data-toggle="modal"
                                                                data-target="#agregarDocumentoModal-{{$servicio->nomenclatura }}">
                                                                <i class="bi bi-upload"></i> Agregar
                                                            </button>

                                                        @endif


                                                    </td>
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
                                                    <!--BOTON DE DOCUMENTACION-->
                                                    <td scope="row">
                                                        <form action="{{ route('documentacion_operacion') }}" method="GET"
                                                            style="display:inline;">
                                                            <input type="hidden" name="id" value="{{ $servicio->id }}">
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="bi bi-folder-fill"></i>
                                                            </button>
                                                        </form>
                                                    </td><!--FIN BOTON DE DOCUMENTACION-->
                                                    <td scope="row">
                                                        @can('borrar-servicio_operacion_mantenimiento')
                                                            @if($servicio->pending_deletion_servicio)
                                                                <button class="btn btn-danger" disabled>(pendiente)</button>
                                                            @else
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['servicio_operacion.destroy', $servicio->id], 'style' => 'display:inline']) !!}
                                                                {!! Form::button('<i class="bi bi-trash-fill"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'title' => 'Eliminar']) !!}
                                                                {!! Form::close() !!}
                                                            @endif
                                                        @endcan
                                                    </td>
                                                </tr>

                                                <!-- Modal para agregar documento -->
                                                <div class="modal fade" id="agregarDocumentoModal-{{$servicio->nomenclatura }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="agregarDocumentoLabel-{{$servicio->nomenclatura }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header"
                                                                style="background-color: #007bff; color: #ffffff;">
                                                                <h5 class="modal-title"
                                                                    id="agregarDocumentoLabel-{{ $servicio->nomenclatura }}">
                                                                    Agregar Pago:{{$servicio->nomenclatura}}</h5>
                                                                <button type="button" class="btn-close btn-close-white"
                                                                    data-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('pago_operacion.store') }}" method="POST"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label for="rutadoc">Seleccionar Archivo</label>
                                                                        <input type="file" name="rutadoc" class="form-control"
                                                                            required>
                                                                    </div>
                                                                    <input type="hidden" name="servicio_id"
                                                                        value="{{ $servicio->id }}">
                                                                    <input type="hidden" name="nomenclatura"
                                                                        value="{{ $servicio->nomenclatura }}">

                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Cerrar</button>
                                                                        <button type="submit" class="btn btn-primary">Agregar
                                                                            pago</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- FIN Modal para agregar documento -->
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
        @endif

    <!-- Formulario con soporte AJAX -->
    <div class="modal fade" id="generarServicioModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Generar
                        Servicio Operacion y mantenimiento</h5>
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
<script>

    $(document).ready(function () {
        $('.btn-descargar-factura').click(function (event) {
            // Prevenir el comportamiento predeterminado del enlace (navegación)
            event.preventDefault();

            // Obtener la ruta del documento desde el atributo href del enlace
            var rutaDocumento = $(this).attr('href');

            // Obtener el nombre de la carpeta desde el atributo data-carpeta del enlace
            var carpeta = $(this).data('carpeta');

            // Construir el nombre de archivo para la descarga
            var nombreArchivo = 'Factura_' + carpeta + '.pdf';

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