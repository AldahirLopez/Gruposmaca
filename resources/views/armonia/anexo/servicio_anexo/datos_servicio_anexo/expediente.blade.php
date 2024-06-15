@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Generar Expediente de ({{$estacion->nomenclatura}})</h3>
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

                        <!-- Formulario con soporte AJAX -->
                        <form id="generateWordForm"
                            action="{{ route('generate.word', ['servicio_anexo_id' => $servicio_anexo_id]) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="nomenclatura"
                                    value="{{ strtoupper($estacion->nomenclatura) }}">
                                    <input type="hidden" name="id_servicio"
                                    value="{{ strtoupper($estacion->id) }}">
                                <input type="hidden" name="id_usuario"
                                    value="{{ strtoupper($estacion->usuario->name) }}">
                                <input type="hidden" name="fecha_actual" value="{{ date('d/m/Y') }}">

                                <!-- Input fields aquí -->
                                <div class="col-md-6">
                                    <!-- Campos del formulario -->
                                    <div class="form-group">
                                        <label for="razonsocial">Razon Social</label>
                                        <input type="text" name="razonsocial" class="form-control"
                                            value="{{ $archivoAnexo ? $archivoAnexo->Razon_Social : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="rfc">RFC</label>
                                        <input type="text" name="rfc" class="form-control"
                                            value="{{ $archivoAnexo ? $archivoAnexo->RFC : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="domicilio_fiscal">Domicilio Fiscal</label>
                                        <input type="text" name="domicilio_fiscal" class="form-control"
                                            value="{{ $archivoAnexo ? $archivoAnexo->Domicilio_Fiscal : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="telefono">Telefono</label>
                                        <input type="text" name="telefono" class="form-control"
                                            value="{{ $archivoAnexo ? $archivoAnexo->Telefono : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="correo">Correo Electronico</label>
                                        <input type="text" name="correo" class="form-control"
                                            value="{{ $archivoAnexo ? $archivoAnexo->Correo : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_recepcion">Fecha de Recepcion de Solicitud</label>
                                        <input type="text" name="fecha_recepcion" class="form-control"
                                            value="{{ $archivoAnexo ? $archivoAnexo->Fecha_Recepcion_Solicitud : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cre">Num. de Permiso de la comision reguladora de energia</label>
                                        <input type="text" name="cre" class="form-control"
                                            value="{{ $archivoAnexo ? $archivoAnexo->Num_CRE : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="constancia">Num. de la Constancia de tramite o estacion de
                                            servicio</label>
                                        <input type="text" name="constancia" class="form-control"
                                            value="{{ $archivoAnexo ? $archivoAnexo->Num_Constancia : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="domicilio_estacion">Domicilio de la estacion de servicio</label>
                                        <input type="text" name="domicilio_estacion" class="form-control"
                                            value="{{ $archivoAnexo ? $archivoAnexo->Domicilio_Estacion_Servicio : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="estado">Estado</label>
                                        <select name="estado" class="form-select" id="estado"
                                            aria-label="Default select example">
                                            @foreach($estados as $estado)
                                                <option value="{{ $estado }}" {{ $archivoAnexo && $archivoAnexo->Direccion_Estado == $estado ? 'selected' : '' }}>
                                                    {{ $estado }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="contacto">Contacto</label>
                                        <input type="text" name="contacto" class="form-control"
                                            value="{{ $archivoAnexo ? $archivoAnexo->Contacto : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="nom_repre">Nombre del representante legal</label>
                                        <input type="text" name="nom_repre" class="form-control"
                                            value="{{ $archivoAnexo ? $archivoAnexo->Nombre_Representante_Legal : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_inspeccion">Fecha Programada de la Inspeccion</label>
                                        <input type="text" name="fecha_inspeccion" class="form-control"
                                            value="{{ $archivoAnexo ? $archivoAnexo->Fecha_Inspeccion : '' }}">
                                    </div>
                                </div>
                                <!-- Asegúrate de que todos los campos estén aquí -->
                                <center>
                                    <div style="margin-top: 15px;">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <button type="submit" class="btn btn-primary">Generar</button>
                                            <a href="{{ route('servicio_anexo.index') }}"
                                                class="btn btn-danger">Cancelar</a>
                                        </div>
                                    </div>
                                </center>
                            </div>
                        </form>

                        <!-- Contenedor para la tabla de archivos generados -->
                        <div id="generatedFilesTable" style="margin-top: 30px;">
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
                                                <td>{{ $file['name'] }}</td>
                                                <td><a href="{{ $file['url'] }}" class="btn btn-info" download>Descargar</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No se encontraron archivos en la carpeta especificada.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Incluir jQuery y el script de AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#generateWordForm').on('submit', function (e) {
            e.preventDefault(); // Evitar la recarga de la página

            // Ocultar la tabla
            $('#generatedFilesTable').hide();

            // Mostrar el spinner de carga dentro de la tabla
            $('#loadingSpinner').addClass('d-flex');

            // Deshabilitar el botón para evitar múltiples envíos
            $('#generateWordForm button[type="submit"]').prop('disabled', true);

            // Enviar los datos del formulario usando AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response && response.generatedFiles && Array.isArray(response.generatedFiles)) {
                        // Crear el HTML para la tabla
                        let tableHtml = '<h4>Documentos Generados:</h4><table class="table table-bordered"><thead><tr><th>Nombre del Archivo</th><th>Acción</th></tr></thead><tbody>';
                        response.generatedFiles.forEach(file => {
                            tableHtml += `<tr><td>${file.name}</td><td><a href="${file.url}" class="btn btn-info" download>Descargar</a></td></tr>`;
                        });
                        tableHtml += '</tbody></table>';
                        // Actualizar el contenedor de la tabla y mostrarla
                        $('#generatedFilesTable').html(tableHtml).show();
                    } else {
                        alert('No se generaron archivos. Por favor, revise los datos ingresados.');
                    }
                },
                error: function (xhr, status, error) {
                    alert('Ocurrió un error al generar los documentos.');
                },
                complete: function () {
                    // Ocultar el spinner de carga después de que se complete la solicitud (ya sea con éxito o error)
                    $('#loadingSpinner').removeClass('d-flex');
                    // Rehabilitar el botón
                    $('#generateWordForm button[type="submit"]').prop('disabled', false);
                }
            });
        });
    });
</script>
@endsection