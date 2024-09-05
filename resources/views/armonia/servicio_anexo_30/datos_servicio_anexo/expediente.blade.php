@extends('layouts.app')

@section('content')
@can('Generar-expediente-anexo_30')
<section class="section">

    <div class="section-header">

        <h3 class="page__heading">Generar Expediente de ({{$servicioAnexo->nomenclatura}})</h3>
    </div>
    <div class="section-header" style="margin: 5px 5px 15px 5px;">
        <a href="{{ route('servicio_inspector_anexo_30.index') }}" class="btn btn-danger">
            <i class="bi bi-arrow-return-left"></i> Volver
        </a>
    </div>

    <div class="section-header" style="margin: 5px 5px 15px 5px;">
        <a href="{{ route('lista_inspeccion_anexo', ['id_servicio' => $servicioAnexo->id]) }}" class="btn btn-primary">
            Lista Inspección
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
                                            @can('Generar-expediente-anexo_30')
                                            <a href="#" class="btn btn-primary" id="generateExpedienteButton" data-toggle="modal" data-target="#generarExpedienteModal" style="margin-top: 10px;">Generar</a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>

                                @can('Generar-dictamenes-anexo')
                                <!-- Tarjeta 2 - Dictámenes Informáticos -->
                                <div class="col-md-4 dictamenes-card" style="display: none;">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Dictámenes Informáticos</h5>
                                            <ol class="list-group list-group-numbered" style="text-align: left;">
                                                <li class="list-group-item">DICTAMEN TÉCNICO DE PROGRAMAS INFORMÁTICOS
                                                </li>
                                            </ol>
                                            <button type="button" class="btn btn-primary" id="dictamenesButton1" data-toggle="modal" data-target="#dictamenesModalinformatico" style="margin-top: 10px;">
                                                Generar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endcan

                                @can('Generar-dictamenes-anexo')
                                <!-- Tarjeta 3 - Dictámenes de Medición -->
                                <div class="col-md-4 dictamenes-card" style="display: none;">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Dictámenes de Medición</h5>
                                            <ol class="list-group list-group-numbered" style="text-align: left;">
                                                <li class="list-group-item">DICTAMEN TÉCNICO DE MEDICIÓN</li>
                                            </ol>
                                            <button type="button" class="btn btn-primary" id="dictamenesButton2" data-toggle="modal" data-target="#dictamenesModalmedicion" style="margin-top: 10px;">
                                                Generar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endcan @can('Generar-dictamenes-anexo')
                                <!-- Tarjeta 4 - Certificado y JSON  -->
                                <div class="col-md-4 dictamenes-card" style="display: none;">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Certificado</h5>
                                            <ol class="list-group list-group-numbered" style="text-align: left;">
                                                <li class="list-group-item">Certificado</li>
                                                <li class="list-group-item">Archivo JSON</li>
                                            </ol>
                                            <button type="button" class="btn btn-primary" id="dictamenesButton2" data-toggle="modal" data-target="#dictamenesModalcertificado" style="margin-top: 10px;">
                                                Generar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endcan

                            @can('Generar-dictamenes-anexo')
                            <!-- Modal para Dictámenes Informático -->
                            <div class="modal fade" id="dictamenesModalinformatico" tabindex="-1" role="dialog" aria-labelledby="dictamenesModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                            <h5 class="modal-title" id="dictamenesModalLabel">TABLA DE CUMPLIMIENTO
                                                SISTEMA INFORMÁTICO</h5>
                                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="generateWordDicForm" action="{{ route('guardar.dictamenesinformatico') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <!-- Campos ocultos -->
                                                <input type="hidden" id="nomenclatura" name="nomenclatura" value="{{ strtoupper($servicioAnexo->nomenclatura) }}">
                                                <input type="hidden" id="nom_repre" name="nom_repre" value="{{ strtoupper($estacion->nombre_representante_legal) }}">
                                                <input type="hidden" id="idestacion" name="idestacion" value="{{ strtoupper($estacion->id) }}">
                                                <input type="hidden" id="id_servicio" name="id_servicio" value="{{ $servicioAnexo->id }}">
                                                <input type="hidden" name="id_usuario" value="{{ $estacion->usuario->id }}">
                                                <input type="hidden" name="fecha_actual" value="{{ date('d/m/Y') }}">
                                                <input type="hidden" id="numestacion" name="numestacion" value="{{ $estacion->num_estacion }}">

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
                                                        @foreach([['Requerimientos Generales', 'opcion1'], ['Requerimientos de la información', 'opcion2'], ['Requerimientos de almacenaje de información', 'opcion3'], ['Requerimientos del procesamiento de la información y de la generación de reportes', 'opcion4'], ['Requerimientos de seguridad', 'opcion5']] as [$requerimiento, $opcion])
                                                        <tr>
                                                            <td>{{ $requerimiento }}</td>
                                                            <td>
                                                                <!-- Opciones de radio -->
                                                                @foreach(['cumple', 'no_cumple', 'no_aplica'] as $value)
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="{{ $opcion }}" id="{{ $opcion }}_{{ $value }}" value="{{ $value }}" {{ $value === 'cumple' ? 'checked' : '' }} required>
                                                                    <label class="form-check-label" for="{{ $opcion }}_{{ $value }}">{{ ucfirst($value) }}</label>
                                                                </div>
                                                                @endforeach
                                                            </td>
                                                            <td><input class="form-control" name="detalleOpinion{{ $loop->index + 1 }}" rows="1" required></td>
                                                            <td><input class="form-control" name="recomendaciones{{ $loop->index + 1 }}" rows="1" required></td>
                                                        </tr>
                                                        @endforeach
                                                        <!-- Resultado de la inspección -->
                                                        <tr>
                                                            <td colspan="4" class="text-center">
                                                                <strong>Resultado de la inspección</strong><br>
                                                                @foreach(['cumple', 'no_cumple'] as $value)
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="opcion6" id="opcion6_{{ $value }}" value="{{ $value }}" {{ $value === 'cumple' ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="opcion6_{{ $value }}">{{ ucfirst($value) }}</label>
                                                                </div>
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <!-- Campos adicionales del formulario -->
                                                @foreach([['proveedor', 'Proveedor de Sistemas Informáticos'], ['rfc_proveedor', 'RFC'], ['software', 'Software'], ['version', 'Versión']] as [$name, $label])
                                                <div class="form-group">
                                                    <label for="{{ $name }}">{{ $label }}</label>
                                                    <input type="text" name="{{ $name }}" id="{{ $name }}" class="form-control" required>
                                                </div>
                                                @endforeach

                                                <div class="text-center mt-3">
                                                    <button type="submit" class="btn btn-primary">Generar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endcan


                            @can('Generar-dictamenes-anexo')
                            <!-- Modal para Dictámenes Informático -->
                            <div class="modal fade" id="dictamenesModalmedicion" tabindex="-1" role="dialog" aria-labelledby="dictamenesModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                            <h5 class="modal-title" id="dictamenesModalLabel">TABLA DE CUMPLIMIENTO
                                                SISTEMAS DE MEDICIÓN</h5>
                                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="generateWordDicForm" action="{{ route('guardar.dictamenesmedicion') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" id="nomenclatura" name="nomenclatura" value="{{ strtoupper($servicioAnexo->nomenclatura) }}">
                                                <input type="hidden" id="nom_repre" name="nom_repre" value="{{ strtoupper($estacion->nombre_representante_legal) }}">
                                                <input type="hidden" id="idestacion" name="idestacion" value="{{ strtoupper($estacion->id) }}">
                                                <input type="hidden" id="id_servicio" name="id_servicio" value="{{ $servicioAnexo->id }}">
                                                <input type="hidden" name="id_usuario" value="{{ $estacion->usuario->id }}">
                                                <input type="hidden" name="fecha_actual" value="{{ date('d/m/Y') }}">
                                                <input type="hidden" name="numestacion" id="numestacion" value="{{ $estacion->num_estacion }}">

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
                                                        @foreach([['Requerimiento general del sistema de medición', 'opcion1'], ['Requerimiento de los sistemas de medición estática', 'opcion2'], ['Requerimiento de los sistemas de medición dinámica en ductos', 'opcion3'], ['Requerimientos de los sistemas de medición dinámica en estaciones de servicio', 'opcion4']] as $index => [$requerimiento, $opcion])
                                                        <tr>
                                                            <td>{{ $requerimiento }}</td>
                                                            <td>
                                                                @foreach(['cumple', 'no_cumple', 'no_aplica'] as $value)
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="{{ $opcion }}" id="{{ $opcion }}_{{ $value }}" value="{{ $value }}" {{ $value === 'cumple' ? 'checked' : '' }} required>
                                                                    <label class="form-check-label" for="{{ $opcion }}_{{ $value }}">{{ ucfirst($value) }}</label>
                                                                </div>
                                                                @endforeach
                                                            </td>
                                                            <td><input class="form-control" name="detalleOpinion{{ $index + 1 }}" id="detalleOpinion{{ $index + 1 }}" rows="1" required></td>
                                                            <td><input class="form-control" name="recomendaciones{{ $index + 1 }}" id="recomendaciones{{ $index + 1 }}" rows="1" required></td>
                                                        </tr>
                                                        @endforeach
                                                        <!-- Resultado de la inspección -->
                                                        <tr>
                                                            <td colspan="4" class="text-center">
                                                                <strong>Resultado de la inspección</strong><br>
                                                                @foreach(['cumple', 'no_cumple'] as $value)
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="opcion6" id="opcion6_{{ $value }}" value="{{ $value }}" {{ $value === 'cumple' ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="opcion6_{{ $value }}">{{ ucfirst($value) }}</label>
                                                                </div>
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <h3 class="mt-4 mb-3">Agregue sus dispensarios</h3>
                                                <p class="mb-3">
                                                    En caso de no contar con:
                                                </p>
                                                <ul class="list-group mb-3">
                                                    <li class="list-group-item">
                                                        <strong>Modelo:</strong> Colocar NM1, NM2, etc., según el número de dispensarios.
                                                    </li>
                                                    <li class="list-group-item">
                                                        <strong>Número de serie:</strong> Colocar NN1, NN2, etc., según el número de dispensarios.
                                                    </li>
                                                </ul>

                                                <div id="dispensarios-container" class="mb-3">
                                                    <!-- Aquí se agregarán los dispensarios -->
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <button id="add-dispensario" type="button" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i>
                                                        Agregar dispensario</button>
                                                </div>

                                                <h3 class="mt-4 mb-3">Agregue sus Tanques</h3>
                                                <div id="combustibles-container" class="mb-3">
                                                    <!-- Aquí se agregarán los combustibles -->
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <button id="add-combustible" type="button" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i>
                                                        Agregar combustible</button>
                                                </div>

                                                <h3 class="mt-4 mb-3">Agregue sus sondas</h3>
                                                <p class="mb-3">
                                                    En caso de no contar con:
                                                </p>
                                                <ul class="list-group mb-3">
                                                    <li class="list-group-item">
                                                        <strong>Marca:</strong> Colocar el producto del tanque.
                                                    </li>
                                                    <li class="list-group-item">
                                                        <strong>Modelo:</strong> Colocar NM1, NM2, etc., según el número de sondas.
                                                    </li>
                                                    <li class="list-group-item">
                                                        <strong>Número de serie:</strong> Colocar NN1, NN2, etc., según el número de sondas.
                                                    </li>
                                                </ul>

                                                <div id="sondas-container" class="mb-3">
                                                    <!-- Aquí se agregarán las sondas -->
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <button id="add-sondas" type="button" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i>
                                                        Agregar sondas</button>
                                                </div>

                                                <div class="text-center mt-3">
                                                    <button type="submit" class="btn btn-primary btn-generar">Generar</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endcan

                            @can('Generar-dictamenes-anexo')
                            <!-- Modal para Generar Certificado -->
                            <div class="modal fade" id="dictamenesModalcertificado" tabindex="-1" role="dialog" aria-labelledby="dictamenesModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                            <h5 class="modal-title" id="dictamenesModalLabel">GENERAR CERTIFICADO</h5>
                                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="generateCertificadoForm" action="{{ route('guardar.certificado') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" id="nomenclatura" name="nomenclatura" value="{{ strtoupper($servicioAnexo->nomenclatura) }}">
                                                <input type="hidden" id="idestacion" name="idestacion" value="{{ strtoupper($estacion->id) }}">
                                                <input type="hidden" id="id_servicio" name="id_servicio" value="{{ $servicioAnexo->id }}">
                                                <input type="hidden" name="id_usuario" value="{{ $estacion->usuario->id }}">

                                                <!-- Campos adicionales para RFC -->
                                                <div class="form-group mt-3">
                                                    <label for="RfcRepresentanteLegal">RFC del Representante Legal:</label>
                                                    <input type="text" class="form-control" id="RfcRepresentanteLegal" name="RfcRepresentanteLegal" required>
                                                    @error('RfcRepresentanteLegal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group mt-3">
                                                    <label for="RfcPersonal">RFC del Personal:</label>
                                                    <input type="text" class="form-control" id="RfcPersonal" name="RfcPersonal" required>
                                                    @error('RfcPersonal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="calle">CALLE</label>
                                                                <input type="text" name="calle" id="calle" class="form-control" required value="{{ old('calle') }}">
                                                                @error('calle')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="numero_exterior">NUMERO Y/O LETRA EXTERIOR</label>
                                                                <input type="text" name="numero_exterior" id="numero_exterior" class="form-control" required value="{{ old('numero_exterior') }}">
                                                                @error('numero_exterior')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="numero_interior">NUMERO Y/O LETRA INTERIOR</label>
                                                                <input type="text" name="numero_interior" id="numero_interior" class="form-control" required value="{{ old('numero_interior') }}">
                                                                @error('numero_interior')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="colonia">COLONIA</label>
                                                                <input type="text" name="colonia" id="colonia" class="form-control" required value="{{ old('colonia') }}">
                                                                @error('colonia')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="codigo_postal">CODIGO POSTAL</label>
                                                                <input type="text" name="codigo_postal" id="codigo_postal" class="form-control" required value="{{ old('codigo_postal') }}">
                                                                @error('codigo_postal')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <!-- Selección de Entidad Federativa (Estado) -->
                                                            <div class="form-group">
                                                                <label for="estado">ENTIDAD FEDERATIVA</label>
                                                                <select name="estado" id="estado" class="form-select" required>
                                                                    <option value="">Seleccione un estado</option>
                                                                    @foreach($estados as $estado)
                                                                    <option value="{{ $estado->id }}">{{ $estado->description }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('estado')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <!-- Selección de Municipio/Alcaldía -->
                                                            <div class="form-group">
                                                                <label for="municipio_alcaldia">MUNICIPIO/ALCALDIA</label>
                                                                <select name="municipio_alcaldia" id="municipio_alcaldia" class="form-select" required>
                                                                    <option value="">Seleccione un municipio</option>
                                                                </select>
                                                                @error('municipio_alcaldia')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="localidad">LOCALIDAD</label>
                                                                <input type="text" name="localidad" id="localidad" class="form-control" required value="{{ old('localidad') }}">
                                                                @error('localidad')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="text-center mt-3">
                                                    <button type="submit" class="btn btn-primary btn-generar">Generar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endcan





                            @can('Generar-expediente-anexo_30')
                            <!-- Modal para generar expediente -->
                            <div class="modal fade" id="generarExpedienteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Generar Expediente de ({{$servicioAnexo->nomenclatura}})</h5>
                                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Formulario de generación de expediente con soporte AJAX -->
                                            <form id="generateWordForm" action="{{ route('generate.word') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <input type="hidden" id="nomenclatura" name="nomenclatura" value="{{ strtoupper($servicioAnexo->nomenclatura) }}">
                                                    <input type="hidden" id="idestacion" name="idestacion" value="{{ strtoupper($estacion->id) }}">
                                                    <input type="hidden" id="id_servicio" name="id_servicio" value="{{ $servicioAnexo->id }}">
                                                    <input type="hidden" name="id_usuario" value="{{ $estacion->usuario->id }}">
                                                    <input type="hidden" name="fecha_actual" value="{{ date('d/m/Y') }}">
                                                    <input type="hidden" type="text" name="numestacion" id="numestacion" class="form-control" value="{{ $estacion->num_estacion }}">

                                                    <div class="col-md-6">
                                                        <!-- Campos del formulario que se llenarán automáticamente -->
                                                        <div class="form-group">
                                                            <label for="razonsocial">Razón Social</label>
                                                            <input type="text" name="razonsocial" id="razonsocial" class="form-control" required value="{{ old('razonsocial') }}">
                                                            @error('razonsocial')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="rfc">RFC</label>
                                                            <input type="text" name="rfc" id="rfc" class="form-control" required value="{{ old('rfc') }}">
                                                            @error('rfc')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <!-- Botones para ver o registrar dirección fiscal -->
                                                        <div class="form-group">
                                                            @if($direccionFiscal)
                                                            <a href="#" class="btn btn-info" data-id="{{ $direccionFiscal->id }}" data-toggle="modal" data-target="#verDireccionFiscalModal" style="margin-top: 10px;">Ver Dirección Fiscal</a>
                                                            @else
                                                            <a href="{{ route('estacion.direcciones', ['id' => $estacion->id]) }}" class="btn btn-warning" style="margin-top: 10px;">
                                                                Registrar Dirección Fiscal
                                                            </a>
                                                            @endif
                                                            @error('domicilio_fiscal')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="telefono">Teléfono</label>
                                                            <input type="text" name="telefono" id="telefono" class="form-control" required value="{{ old('telefono') }}">
                                                            @error('telefono')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="correo">Correo Electrónico</label>
                                                            <input type="email" name="correo" id="correo" class="form-control" required value="{{ old('correo') }}">
                                                            @error('correo')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="fecha_recepcion">Fecha de Recepción de Solicitud</label>
                                                            <input type="date" name="fecha_recepcion" id="fecha_recepcion" class="form-control" required value="{{ old('fecha_recepcion') }}">
                                                            @error('fecha_recepcion')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="cre">Num. de Permiso de la Comisión Reguladora de Energía</label>
                                                            <input type="text" name="cre" id="cre" class="form-control" required value="{{ old('cre') }}">
                                                            @error('cre')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="constancia">Num. de Constancia de Trámite o Estación de Servicio</label>
                                                            <input type="text" name="constancia" id="constancia" class="form-control" required value="{{ old('constancia') }}">
                                                            @error('constancia')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            @if($direccionEstacion)
                                                            <a href="#" class="btn btn-info" data-id="{{ $direccionEstacion->id }}" data-toggle="modal" data-target="#verDireccionFiscalModal" style="margin-top: 10px;">Ver Dirección Estacion</a>
                                                            @else
                                                            <a href="#" class="btn btn-warning" id="registrarDireccionEstacionButton" data-toggle="modal" data-target="#registrarDireccionEstacionModal" style="margin-top: 10px;">Registrar Dirección Estacion</a>
                                                            @endif
                                                            @error('domicilio_fiscal')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="estado">Estado</label>
                                                            <select name="estado" id="estado" class="form-select" required>
                                                                @foreach($estados as $estado)
                                                                <option value="{{ $estado->description }}" {{ old('estado') == $estado->description ? 'selected' : '' }}>{{ $estado->description }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('estado')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="contacto">Contacto</label>
                                                            <input type="text" name="contacto" id="contacto" class="form-control" required value="{{ old('contacto') }}">
                                                            @error('contacto')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nom_repre">Nombre del Representante Legal</label>
                                                            <input type="text" name="nom_repre" id="nom_repre" class="form-control" required value="{{ old('nom_repre') }}">
                                                            @error('nom_repre')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="fecha_inspeccion">Fecha Programada de Inspección</label>
                                                            <input type="date" name="fecha_inspeccion" id="fecha_inspeccion" class="form-control" required value="{{ old('fecha_inspeccion') }}">
                                                            @error('fecha_inspeccion')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <h2 class="card-title">Datos generales del contrato</h2>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="cantidad">Precio a pagar por el servicio de inspección</label>
                                                                <input type="number" step="0.01" name="cantidad" id="cantidad" class="form-control" required value="{{ old('cantidad') }}">
                                                                @error('cantidad')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
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
                            <!-- Modal para Ver Dirección Fiscal -->
                            <div class="modal fade" id="verDireccionFiscalModal" tabindex="-1" role="dialog" aria-labelledby="verDireccionFiscalModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="verDireccionFiscalModalLabel">Dirección Fiscal Registrada</h5>
                                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" id="direccionFiscalDetalles">
                                            <!-- Aquí se mostrarán los detalles de la dirección fiscal -->
                                            <!-- Los detalles se cargarán mediante AJAX -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal para Registrar Dirección Fiscal -->
                            <div class="modal fade" id="registrarDireccionFiscalModal" tabindex="-1" role="dialog" aria-labelledby="registrarDireccionFiscalModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content rounded-3 shadow-lg">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="registrarDireccionFiscalModalLabel">Registrar Dirección Fiscal</h5>
                                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('guardar.direccion') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="direccionSelect" value="fiscal">
                                                <input type="hidden" name="estacion_id" value="{{ $estacion->id }}">

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="calle_fiscal" class="form-label">Calle</label>
                                                            <input type="text" name="calle_fiscal" id="calle_fiscal" class="form-control" placeholder="Calle">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="numero_ext_fiscal" class="form-label">Número Exterior</label>
                                                            <input type="text" name="numero_ext_fiscal" id="numero_ext_fiscal" class="form-control" placeholder="Número Exterior">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="numero_int_fiscal" class="form-label">Número Interior</label>
                                                            <input type="text" name="numero_int_fiscal" id="numero_int_fiscal" class="form-control" placeholder="Número Interior">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="colonia_fiscal" class="form-label">Colonia</label>
                                                            <input type="text" name="colonia_fiscal" id="colonia_fiscal" class="form-control" placeholder="Colonia">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="codigo_postal_fiscal" class="form-label">Código Postal</label>
                                                            <input type="text" name="codigo_postal_fiscal" id="codigo_postal_fiscal" class="form-control" placeholder="Código Postal">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="municipio_id_fiscal" class="form-label">Municipio</label>
                                                            <select name="municipio_id_fiscal" id="municipio_id_fiscal" class="form-select">
                                                                <option value="">Seleccione un municipio</option>
                                                                @foreach($municipios as $municipio)
                                                                <option value="{{ $municipio->description }}">{{ $municipio->description }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="localidad_fiscal" class="form-label">Localidad</label>
                                                            <input type="text" name="localidad_fiscal" id="localidad_fiscal" class="form-control" placeholder="Localidad">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="entidad_federativa_fiscal" class="form-label">Entidad Federativa</label>
                                                            <input type="text" name="entidad_federativa_fiscal" id="entidad_federativa_fiscal" class="form-control" placeholder="Entidad Federativa">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-primary">Registrar Dirección Fiscal</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Modal para Registrar Dirección de la Estación -->
                            <div class="modal fade" id="registrarDireccionEstacionModal" tabindex="-1" role="dialog" aria-labelledby="registrarDireccionEstacionModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content rounded-3 shadow-lg">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="registrarDireccionEstacionModalLabel">Registrar Dirección de la Estación</h5>
                                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('guardar.direccion') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="direccionSelect" value="estacion">
                                                <input type="hidden" name="estacion_id" value="{{ $estacion->id }}">

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="calle_estacion" class="form-label">Calle</label>
                                                            <input type="text" name="calle_estacion" id="calle_estacion" class="form-control" placeholder="Calle">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="numero_ext_estacion" class="form-label">Número Exterior</label>
                                                            <input type="text" name="numero_ext_estacion" id="numero_ext_estacion" class="form-control" placeholder="Número Exterior">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="numero_int_estacion" class="form-label">Número Interior</label>
                                                            <input type="text" name="numero_int_estacion" id="numero_int_estacion" class="form-control" placeholder="Número Interior">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="colonia_estacion" class="form-label">Colonia</label>
                                                            <input type="text" name="colonia_estacion" id="colonia_estacion" class="form-control" placeholder="Colonia">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="codigo_postal_estacion" class="form-label">Código Postal</label>
                                                            <input type="text" name="codigo_postal_estacion" id="codigo_postal_estacion" class="form-control" placeholder="Código Postal">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="municipio_id_estacion" class="form-label">Municipio</label>
                                                            <select name="municipio_id_estacion" id="municipio_id_estacion" class="form-select">
                                                                <option value="">Seleccione un municipio</option>
                                                                @foreach($municipios as $municipio)
                                                                <option value="{{ $municipio->description }}">{{ $municipio->description }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="localidad_estacion" class="form-label">Localidad</label>
                                                            <input type="text" name="localidad_estacion" id="localidad_estacion" class="form-control" placeholder="Localidad">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="entidad_federativa_estacion" class="form-label">Entidad Federativa</label>
                                                            <input type="text" name="entidad_federativa_estacion" id="entidad_federativa_estacion" class="form-control" placeholder="Entidad Federativa">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-primary">Registrar Dirección de la Estación</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            @endcan

                            <!-- Contenedor para la tabla de archivos generados -->
                            <div id="generatedFilesTable" style="margin-top: 30px;">
                                <!-- Spinner de carga -->
                                <div id="loadingSpinner" class="spinner-border text-primary d-none" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>

                                <!-- Estructura HTML para archivos existentes -->
                                @if(!empty($existingFiles))
                                <h4>Archivos Existentes:</h4>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Nombre del Archivo</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($existingFiles as $file)
                                        @can('Descargar-documentos-expediente-anexo_30')
                                        <tr>
                                            <td>
                                                <!-- Mostrar el ícono adecuado según el tipo de archivo -->
                                                @if (str_ends_with($file['name'], '.json'))
                                                <i class="bi bi-filetype-json"></i>
                                                @elseif (str_ends_with($file['name'], '.pdf'))
                                                <i class="bi bi-filetype-pdf"></i>
                                                @elseif (str_ends_with($file['name'], '.docx'))
                                                <i class="bi bi-file-word-fill"></i>
                                                @elseif (str_ends_with($file['name'], '.xlsx'))
                                                <i class="bi bi-filetype-xlsx"></i>
                                                @else
                                                <i class="bi bi-file-earmark"></i>
                                                @endif
                                            </td>
                                            <td>{{ basename($file['name']) }}</td>
                                            <td>
                                                <a href="{{ route('descargar.archivo', ['archivo' => basename($file['name'])]) }}" class="btn btn-info" download>Descargar</a>
                                            </td>
                                        </tr>
                                        @endcan
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                <p>No hay archivos existentes.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Incluir jQuery y Bootstrap 4 desde un CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>

<!-- Script optimizado -->
<script>
    $(document).ready(function() {
        // Evento de clic para abrir el modal y cargar los datos
        $('.btn-info[data-id]').on('click', function() {
            var direccionId = $(this).data('id');

            $.ajax({
                url: '/direccion/' + direccionId,
                method: 'GET',
                success: function(response) {
                    // Actualizar el contenido del modal con los datos de la dirección fiscal
                    $('#direccionFiscalDetalles').html(
                        '<p><strong>Calle:</strong> ' + response.calle + '</p>' +
                        '<p><strong>Número Exterior:</strong> ' + response.numero_ext + '</p>' +
                        '<p><strong>Número Interior:</strong> ' + response.numero_int + '</p>' +
                        '<p><strong>Colonia:</strong> ' + response.colonia + '</p>' +
                        '<p><strong>Código Postal:</strong> ' + response.codigo_postal + '</p>' +
                        '<p><strong>Municipio:</strong> ' + response.municipio + '</p>' +
                        '<p><strong>Localidad:</strong> ' + response.localidad + '</p>' +
                        '<p><strong>Entidad Federativa:</strong> ' + response.entidad_federativa + '</p>'
                    );
                    // Abrir el modal
                    var modal = new bootstrap.Modal(document.getElementById('verDireccionFiscalModal'));
                    modal.show();
                },
                error: function(xhr) {
                    // Manejar errores
                    alert('Error al obtener los datos de la dirección.');
                }
            });
        });
    });

    $(document).ready(function() {
        // Función para cargar los archivos generados
        function loadGeneratedFiles() {
            const nomenclatura = $('#nomenclatura').val();
            fetch(`/list-generated-files/${encodeURIComponent(nomenclatura)}`) // Codificar la nomenclatura
                .then(response => response.json())
                .then(data => {
                    const generatedFilesTable = $('#generatedFilesTable');
                    if (data && data.generatedFiles && data.generatedFiles.length > 0) {
                        // Construir el HTML para la tabla de archivos generados
                        let tableHtml = '<h4>Documentos Generados:</h4><table class="table table-bordered"><thead><tr><th></th><th>Nombre del Archivo</th><th>Acción</th></tr></thead><tbody>';

                        data.generatedFiles.forEach(file => {
                            // Determinar el ícono basado en la extensión del archivo
                            let icon;
                            const fileName = file.name;
                            if (fileName.endsWith('.json')) {
                                icon = '<i class="bx bxs-file-json" style="font-size: 44px; text-align: center; display: inline-block;"></i>'; // Ícono para JSON
                            } else if (fileName.endsWith('.pdf')) {
                                icon = '<i class="fas fa-file-pdf" style="font-size: 44px; text-align: center; display: inline-block;"></i>'; // Ícono para PDF
                            } else if (fileName.endsWith('.docx')) {
                                icon = '<i class="bi bi-file-word-fill" style="font-size: 38px; text-align: center; display: inline-block;"></i>'; // Ícono para DOCX
                            } else if (fileName.endsWith('.xlsx')) {
                                icon = '<i class="fas fa-file-excel" style="font-size: 44px; text-align: center; display: inline-block;"></i>'; // Ícono para XLSX
                            } else {
                                icon = '<i class="fas fa-file"></i>'; // Ícono por defecto
                            }

                            // Modificar la línea donde se genera el enlace de descarga
                            tableHtml += `<tr><td>${icon}</td><td>${fileName}</td><td><a href="/descargar-archivo/${encodeURIComponent(fileName)}/${encodeURIComponent(nomenclatura)}" class="btn btn-info" download>Descargar</a></td></tr>`;
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
                    success: function(data) {
                        // Iterar sobre cada input y select del formulario
                        $('#generateWordForm input, #generateWordForm select').each(function() {
                            var nombreCampo = $(this).attr('name');
                            var valor = data[nombreCampo];

                            // Rellenar el campo con el valor si está disponible
                            if (valor !== undefined && valor !== null) {
                                $(this).val(valor);
                                // Cambiar el estilo del borde del campo
                                $(this).addClass('border-success').removeClass('border-danger');
                                // Desactivar el campo si hay un valor
                                $(this).prop('disabled', valor !== '');
                            } else {
                                // Marcar el borde como peligro si no hay valor
                                $(this).addClass('border-danger').removeClass('border-success');
                                // Permitir la edición del campo
                                $(this).prop('disabled', false);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
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
            fetch(`/api/consulta/${id}`)
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
        $('#submitButtonDictamenes').on('click', function() {
            $('#generateWordDicForm').submit();
        });

        // Asignar el manejador de eventos para el botón de envío del formulario
        $('#submitButton').on('click', function() {
            $('#generateWordForm').submit();
        });

        // Llamar a la función para cargar los datos de la estación al cargar la página
        cargarDatosEstacion();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let dispensarioCount = 0;
        let combustibleCount = 0;
        let sondasCount = 0;

        document.getElementById('add-dispensario').addEventListener('click', () => {
            dispensarioCount++;
            addDispensario(dispensarioCount);
        });

        document.getElementById('add-combustible').addEventListener('click', () => {
            combustibleCount++;
            addCombustible(combustibleCount);
        });

        document.getElementById('add-sondas').addEventListener('click', () => {
            sondasCount++;
            addSondas(sondasCount);
        });

        function addSondas(count) {
            const container = document.getElementById('sondas-container');

            const sondasDiv = document.createElement('div');
            sondasDiv.className = 'sondas';
            sondasDiv.id = `sondas-${count}`;

            const sondasLabel = document.createElement('h4');
            sondasLabel.textContent = `Sonda ${count}`;
            sondasDiv.appendChild(sondasLabel);

            const marcaInput = createInputField('Marca', `sondas[${count}][marca]`);
            const modeloInput = createInputField('Modelo', `sondas[${count}][modelo]`);
            const numeroSerieInput = createInputField('Número de Serie', `sondas[${count}][numero_serie]`);

            sondasDiv.appendChild(marcaInput);
            sondasDiv.appendChild(modeloInput);
            sondasDiv.appendChild(numeroSerieInput);

            container.appendChild(sondasDiv);
        }

        function addDispensario(count) {
            const container = document.getElementById('dispensarios-container');

            const dispensarioDiv = document.createElement('div');
            dispensarioDiv.className = 'dispensario';
            dispensarioDiv.id = `dispensario-${count}`;

            const dispensarioLabel = document.createElement('h4');
            dispensarioLabel.textContent = `Dispensario ${count}`;
            dispensarioDiv.appendChild(dispensarioLabel);

            const marcaInput = createInputField('Marca', `dispensarios[${count}][marca]`);
            const modeloInput = createInputField('Modelo', `dispensarios[${count}][modelo]`);
            const numeroSerieInput = createInputField('Número de Serie', `dispensarios[${count}][numero_serie]`);

            dispensarioDiv.appendChild(marcaInput);
            dispensarioDiv.appendChild(modeloInput);
            dispensarioDiv.appendChild(numeroSerieInput);

            container.appendChild(dispensarioDiv);
        }

        function addCombustible(count) {
            const container = document.getElementById('combustibles-container');

            const combustibleDiv = document.createElement('div');
            combustibleDiv.className = 'combustible';
            combustibleDiv.id = `combustible-${count}`;

            const combustibleLabel = document.createElement('h4');
            combustibleLabel.textContent = `Combustible ${count}`;
            combustibleDiv.appendChild(combustibleLabel);

            const tipoCombustibleSelect = document.createElement('select');
            tipoCombustibleSelect.name = `combustibles[${count}][tipo]`;
            tipoCombustibleSelect.className = 'form-select';
            tipoCombustibleSelect.innerHTML = `
                <option value="Diesel">Diesel</option>
                <option value="Magna">Magna</option>
                <option value="Premium">Premium</option>
            `;
            combustibleDiv.appendChild(tipoCombustibleSelect);

            const cantidadInput = createInputField('Cantidad', `combustibles[${count}][cantidad]`);
            combustibleDiv.appendChild(cantidadInput);

            container.appendChild(combustibleDiv);
        }

        function createInputField(labelText, name) {
            const div = document.createElement('div');
            div.className = 'mb-3';

            const label = document.createElement('label');
            label.textContent = labelText;
            label.className = 'form-label';

            const input = document.createElement('input');
            input.type = 'text'; // Changed from 'number' to 'text'
            input.name = name;
            input.className = 'form-control';

            div.appendChild(label);
            div.appendChild(input);

            return div;
        }
    });
</script>
@endcan
@endsection