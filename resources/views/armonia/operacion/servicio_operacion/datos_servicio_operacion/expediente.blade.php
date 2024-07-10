@extends('layouts.app')

@section('content')
<section class="section">

    <div class="section-header">

        <h3 class="page__heading">Generar Expediente de ({{$servicioAnexo->nomenclatura}})</h3>
    </div>
    <div class="section-header" style="margin: 5px 5px 15px 5px;">
        <a href="{{ route('servicio_operacion.index') }}" class="btn btn-danger">
            <i class="bi bi-arrow-return-left"></i> Volver
        </a>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-dark alert-dismissible fade show" role="alert">
                                <strong>¡Revise los campos!</strong>
                                @foreach ($errors->all() as $error)
                                    <span class="badge badge-danger">{{ $error }}</span>
                                @endforeach
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="container">

                            <div class="row">
                                <!-- Tarjeta 1 -->
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Expediente</h5>
                                            <ol class="list-group list-group-numbered" style="text-align: left;">
                                                <li class="list-group-item">ORDEN DE TRABAJO</li>
                                                <li class="list-group-item">CONTRATO</li>
                                                <li class="list-group-item">DETECCION DE RIESGOS A LA IMPARCIALIDAD</li>
                                                <li class="list-group-item">PLAN DE INSPECCION DE PROGRAMAS INFORMATICOS
                                                </li>
                                                <li class="list-group-item">PLAN DE INSPECCION DE LOS SISTEMAS DE
                                                    MEDICION</li>
                                            </ol>
                                            <a href="#" class="btn btn-primary" id="generateExpedienteButton"
                                                data-toggle="modal" data-target="#generarExpedienteModal"
                                                style="margin-top: 10px;">Generar</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tarjeta 2 - Dictámenes Informáticos -->
                                <div class="col-md-4 dictamenes-card" style="display: none;">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Dictámenes Informáticos</h5>
                                            <ol class="list-group list-group-numbered" style="text-align: left;">
                                                <li class="list-group-item">DICTAMEN TÉCNICO DE PROGRAMAS INFORMÁTICOS
                                                </li>
                                            </ol>
                                            <button type="button" class="btn btn-primary" id="dictamenesButton1"
                                                data-toggle="modal" data-target="#dictamenesModalinformatico"
                                                style="margin-top: 10px;">
                                                Generar
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tarjeta 3 - Dictámenes de Medición -->
                                <div class="col-md-4 dictamenes-card" style="display: none;">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Dictámenes de Medición</h5>
                                            <ol class="list-group list-group-numbered" style="text-align: left;">
                                                <li class="list-group-item">DICTAMEN TÉCNICO DE MEDICIÓN</li>
                                            </ol>
                                            <button type="button" class="btn btn-primary" id="dictamenesButton2"
                                                data-toggle="modal" data-target="#dictamenesModalmedicion"
                                                style="margin-top: 10px;">
                                                Generar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- Modal para Dictámenes Informático -->
                            <div class="modal fade" id="dictamenesModalinformatico" tabindex="-1" role="dialog"
                                aria-labelledby="dictamenesModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                            <h5 class="modal-title" id="dictamenesModalLabel">TABLA DE CUMPLIMIENTO
                                                SISTEMA INFORMATICO</h5>
                                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="generateWordDicForm" action="{{ route('guardar.dictamenes') }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" id="nomenclatura" name="nomenclatura"
                                                    value="{{ strtoupper($servicioAnexo->nomenclatura) }}">
                                                <input type="hidden" id="idestacion" name="idestacion"
                                                    value="{{ strtoupper($estacion->id) }}">
                                                <input type="hidden" id="id_servicio" name="id_servicio"
                                                    value="{{ $servicioAnexo->id }}">
                                                <input type="hidden" name="id_usuario"
                                                    value="{{ $estacion->usuario->id }}">
                                                <input type="hidden" name="fecha_actual" value="{{ date('d/m/Y') }}">
                                                <input type="hidden" type="text" name="numestacion" id="numestacion"
                                                    class="form-control" value="{{ $estacion->num_estacion }}">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Especificación o requerimiento</th>
                                                            <th>Opinión de cumplimiento</th>
                                                            <th>Detalle de la opinión (Hallazgos)</th>
                                                            <th>Recomendaciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Requerimientos Generales</td>
                                                            <td>
                                                                <!-- Opción 1 -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="opcion1" id="opcion1_cumple"
                                                                        value="cumple" checked>
                                                                    <label class="form-check-label"
                                                                        for="opcion1_cumple">Cumple</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="opcion1" id="opcion1_no_cumple"
                                                                        value="no_cumple">
                                                                    <label class="form-check-label"
                                                                        for="opcion1_no_cumple">No cumple</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="opcion1" id="opcion1_no_aplica"
                                                                        value="no_aplica">
                                                                    <label class="form-check-label"
                                                                        for="opcion1_no_aplica">No aplica</label>
                                                                </div>
                                                            </td>
                                                            <td><input class="form-control" name="detalleOpinion1"
                                                                    rows="1"></input></td>
                                                            <td><input class="form-control" name="recomendaciones1"
                                                                    rows="1"></input></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Requerimientos de almacenaje de información</td>
                                                            <td>
                                                                <!-- Opción 2 -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="opcion2" id="opcion2_cumple"
                                                                        value="cumple" checked>
                                                                    <label class="form-check-label"
                                                                        for="opcion2_cumple">Cumple</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="opcion2" id="opcion2_no_cumple"
                                                                        value="no_cumple">
                                                                    <label class="form-check-label"
                                                                        for="opcion2_no_cumple">No cumple</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="opcion2" id="opcion2_no_aplica"
                                                                        value="no_aplica">
                                                                    <label class="form-check-label"
                                                                        for="opcion2_no_aplica">No aplica</label>
                                                                </div>
                                                            </td>
                                                            <td><input class="form-control" name="detalleOpinion2"
                                                                    rows="1"></input></td>
                                                            <td><input class="form-control" name="recomendaciones2"
                                                                    rows="1"></input></td>
                                                        </tr>
                                                        <!-- Agregar más filas según sea necesario -->
                                                    </tbody>
                                                </table>

                                                <div class="col-xs-12 col-sm-12 col-md-12 text-center"
                                                    style="padding-top: 10px;">
                                                    <button type="submitButtonDictamenes"
                                                        class="btn btn-primary btn-generar">Generar</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>




                            <!-- Modal para generar expediente -->
                            <div class="modal fade" id="generarExpedienteModal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Generar
                                                Expediente de ({{$servicioAnexo->nomenclatura}})</h5>
                                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Formulario de generación de expediente con soporte AJAX -->
                                            <form id="generateWordForm" action="{{ route('generate.word.operacion') }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <input type="hidden" id="nomenclatura" name="nomenclatura"
                                                        value="{{ strtoupper($servicioAnexo->nomenclatura) }}">
                                                    <input type="hidden" id="idestacion" name="idestacion"
                                                        value="{{ strtoupper($estacion->id) }}">
                                                    <input type="hidden" id="id_servicio" name="id_servicio"
                                                        value="{{ $servicioAnexo->id }}">
                                                    <input type="hidden" name="id_usuario"
                                                        value="{{ $estacion->usuario->id }}">
                                                    <input type="hidden" name="fecha_actual"
                                                        value="{{ date('d/m/Y') }}">
                                                    <input type="hidden" type="text" name="numestacion" id="numestacion"
                                                        class="form-control" value="{{ $estacion->num_estacion }}">

                                                    <div class="col-md-6">
                                                        <!-- Campos del formulario que se llenarán automáticamente -->

                                                        <div class="form-group">
                                                            <label for="razonsocial">Razón Social</label>
                                                            <input type="text" name="razonsocial" id="razonsocial"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="rfc">RFC</label>
                                                            <input type="text" name="rfc" id="rfc" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="domicilio_fiscal">Domicilio Fiscal</label>
                                                            <input type="text" name="domicilio_fiscal"
                                                                id="domicilio_fiscal" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="telefono">Teléfono</label>
                                                            <input type="text" name="telefono" id="telefono"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="correo">Correo Electrónico</label>
                                                            <input type="email" name="correo" id="correo"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="fecha_recepcion">Fecha de Recepción de
                                                                Solicitud</label>
                                                            <input type="date" name="fecha_recepcion"
                                                                id="fecha_recepcion" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="cre">Num. de Permiso de la Comisión Reguladora
                                                                de Energía</label>
                                                            <input type="text" name="cre" id="cre" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="constancia">Num. de Constancia de Trámite o
                                                                Estación de Servicio</label>
                                                            <input type="text" name="constancia" id="constancia"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="domicilio_estacion">Domicilio de la Estación de
                                                                Servicio</label>
                                                            <input type="text" name="domicilio_estacion"
                                                                id="domicilio_estacion" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="estado">Estado</label>
                                                            <select name="estado" id="estado" class="form-select">
                                                                @foreach($estados as $estado)
                                                                    <option value="{{ $estado }}">{{ $estado }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="contacto">Contacto</label>
                                                            <input type="text" name="contacto" id="contacto"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nom_repre">Nombre del Representante
                                                                Legal</label>
                                                            <input type="text" name="nom_repre" id="nom_repre"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="fecha_inspeccion">Fecha Programada de
                                                                Inspección</label>
                                                            <input type="date" name="fecha_inspeccion"
                                                                id="fecha_inspeccion" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center"
                                                        style="padding-top: 10px;">
                                                        <button type="submitButton"
                                                            class="btn btn-primary btn-generar">Generar</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Contenedor para la tabla de archivos generados -->
                            <div id="generatedFilesTable" style="margin-top: 30px;">
                                <!-- Spinner de carga -->
                                <div id="loadingSpinner" class="spinner-border text-primary d-none" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>

                                <!-- Incluir la estructura HTML de tu vista actual para archivos existentes -->
                                @if(!empty($existingFiles))
                                    <h4>Archivos Existentes:</h4>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Nombre del Archivo</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($existingFiles as $file)
                                                <tr>
                                                    <td>{{ basename($file['name']) }}</td>
                                                    <!-- Mostrar solo el nombre del archivo -->
                                                    <td><a href="{{ route('descargar.archivo.operacion', ['archivo' => basename($file['name'])]) }}"
                                                            class="btn btn-info" download>Descargar</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <!-- Mensaje o contenido alternativo si no hay archivos existentes -->
                                    <p>No hay archivos existentes.</p>
                                @endif
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<!-- Incluir jQuery y Bootstrap desde un CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>

<!-- Script optimizado -->
<script>
    $(document).ready(function () {
        // Función para cargar los archivos generados
        function loadGeneratedFiles() {
            const nomenclatura = $('#nomenclatura').val();
            fetch(`/list-generated-files-operacion/${encodeURIComponent(nomenclatura)}`)  // Codificar la nomenclatura
                .then(response => response.json())
                .then(data => {
                    const generatedFilesTable = $('#generatedFilesTable');
                    if (data && data.generatedFiles && data.generatedFiles.length > 0) {
                        // Construir el HTML para la tabla de archivos generados
                        let tableHtml = '<h4>Documentos Generados:</h4><table class="table table-bordered"><thead><tr><th>Nombre del Archivo</th><th>Acción</th></tr></thead><tbody>';
                        data.generatedFiles.forEach(file => {
                            // Modificar la línea donde se genera el enlace de descarga
                            tableHtml += `<tr><td>${file.name}</td><td><a href="/descargar-archivo-operacion/${encodeURIComponent(file.name)}/${encodeURIComponent(nomenclatura)}" class="btn btn-info" download>Descargar</a></td></tr>`;
                        });
                        tableHtml += '</tbody></table>';

                        // Actualizar el contenedor de la tabla de archivos generados y mostrarla
                        generatedFilesTable.html(tableHtml).show();

                        // Después de cargar los archivos, verificar el registro
                        checkRegistro();
                    } else {
                        // Mostrar un mensaje si no se encontraron archivos generados
                        generatedFilesTable.html('<p>No se encontraron archivos generados.</p>').show();

                        // Después de mostrar el mensaje, verificar el registro
                        checkRegistro();
                    }
                })
                .catch(error => {
                    console.error('Error al cargar los documentos generados:', error);
                    alert('Ocurrió un error al cargar los documentos generados.');
                });
        }

        // Función para manejar el envío del formulario
        function handleFormSubmit(event) {
            event.preventDefault(); // Evitar la recarga de la página

            // Mostrar el spinner de carga
            const loadingSpinner = $('#loadingSpinner');
            loadingSpinner.removeClass('d-none').addClass('d-flex');

            const formData = new FormData(event.target);

            fetch(event.target.action, {
                method: event.target.method,
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data && data.generatedFiles && data.generatedFiles.length > 0) {
                        // Recargar la página después de enviar el formulario
                        location.reload();
                    } else {
                        // Mostrar un mensaje si no se generaron archivos
                        alert('No se generaron archivos. Por favor, revise los datos ingresados.');
                    }
                })
                .catch(error => {
                    console.error('Error al generar los documentos:', error);
                    alert('Ocurrió un error al generar los documentos.');
                })
                .finally(() => {
                    // Ocultar el spinner de carga después de completar la solicitud (éxito o error)
                    loadingSpinner.removeClass('d-flex').addClass('d-none');
                });
        }

        // Función para cargar los datos de la estación al cargar la página
        function cargarDatosEstacion() {
            var estacionId = $('#idestacion').val();
            if (estacionId) {
                $.ajax({
                    url: '/obtener-datos-estacion/' + estacionId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        // Iterar sobre cada input y select del formulario
                        $('#generateWordForm input, #generateWordForm select').each(function () {
                            var nombreCampo = $(this).attr('name');
                            var valor = data[nombreCampo];

                            // Verificar si el campo tiene un valor asignado
                            if (valor !== undefined && valor !== null) {
                                // Rellenar el campo con el valor y desactivarlo
                                $(this).val(valor).prop('disabled', true);
                                // Cambiar el estilo del borde del campo
                                $(this).addClass(valor ? 'border-success' : 'border-danger');
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error('Error al obtener los datos de la estación:', error);
                    }
                });
            } else {
                console.error('No se ha proporcionado un ID de estación válido.');
            }
        }

        // Función para verificar si existe un registro y mostrar u ocultar cards
        function checkRegistro() {
            const id = $('#id_servicio').val();
          
            fetch(`/api/consulta/operacion/${id}`)
                .then(response => response.json())
                .then(data => {
                    const cards = $('.dictamenes-card');
                    if (data.exists) {
                        // Mostrar las cards si el registro existe
                        cards.show();
                    } else {
                        // Ocultar las cards si no existe el registro
                        cards.hide();
                        // alert('Registro no encontrado');
                    }
                })
                .catch(error => console.error('Error en la solicitud AJAX:', error));
        }

        // Llamar a la función para cargar los archivos generados al cargar la página
        loadGeneratedFiles();

        // Asignar el manejador de eventos para el botón de envío del formulario
        $('#submitButtonDictamenes').on('click', function () {
            $('#generateWordDicForm').submit();
        });

        // Asignar el manejador de eventos para el botón de envío del formulario
        $('#submitButton').on('click', function () {
            $('#generateWordForm').submit();
        });

        // Llamar a la función para cargar los datos de la estación al cargar la página
        cargarDatosEstacion();
    });
</script>
@endsection