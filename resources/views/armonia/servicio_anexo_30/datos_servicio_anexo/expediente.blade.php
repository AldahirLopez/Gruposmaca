@extends('layouts.app')

@section('content')
<section class="section">

    <div class="section-header">

        <h3 class="page__heading">Generar Expediente de ({{$estacion->nomenclatura}})</h3>
    </div>
    <div class="section-header" style="margin: 5px 5px 15px 5px;">
        <a href="{{ route('servicio_inspector_anexo_30.index') }}" class="btn btn-danger">
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
                                    <!-- Cambiado de modal-xl a modal-lg -->
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                            <h5 class="modal-title" id="dictamenesModalLabel">TABLA DE CUMPLIMIENTO
                                                SISTEMA INFORMATICO</h5>
                                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-sm">
                                                <!-- Agregada la clase table-sm para hacer la tabla más compacta -->
                                                <thead>
                                                    <tr>
                                                        <th scope="col" style="font-size: 15px;">Especificación o
                                                            requerimiento</th>
                                                        <th scope="col" style="font-size: 15px;">Opinión de cumplimiento
                                                        </th>
                                                        <th scope="col" style="font-size: 15px;">Detalle de la opinión
                                                            (Hallazgos)</th>
                                                        <th scope="col" style="font-size: 15px;">Recomendaciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <h6>Requerimientos Generales</h6>
                                                        </td>
                                                        <td>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios1" id="gridRadios1a" value="cumple"
                                                                    checked>
                                                                <label class="form-check-label"
                                                                    for="gridRadios1a">Cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios1" id="gridRadios1b"
                                                                    value="no_cumple">
                                                                <label class="form-check-label" for="gridRadios1b">No
                                                                    cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios1" id="gridRadios1c"
                                                                    value="no_aplica">
                                                                <label class="form-check-label" for="gridRadios1c">No
                                                                    aplica</label>
                                                            </div>
                                                        </td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h6>Requerimientos Generales</h6>
                                                            <ul class="list-group"
                                                                style="padding-left: 25px; list-style-type: none;">
                                                                <li style="font-size: 12px;">a) Datos Generales</li>
                                                                <li style="font-size: 12px;">b) Registro del volumen
                                                                </li>
                                                                <li style="font-size: 12px;">c) Tipo de hidrocarburo o
                                                                    petrolífero</li>
                                                                <li style="font-size: 12px;">d) Información fiscal</li>
                                                            </ul>
                                                        </td>
                                                        <td>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios2" id="gridRadios2a" value="cumple"
                                                                    checked>
                                                                <label class="form-check-label"
                                                                    for="gridRadios2a">Cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios2" id="gridRadios2b"
                                                                    value="no_cumple">
                                                                <label class="form-check-label" for="gridRadios2b">No
                                                                    cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios2" id="gridRadios2c"
                                                                    value="no_aplica">
                                                                <label class="form-check-label" for="gridRadios2c">No
                                                                    aplica</label>
                                                            </div>
                                                        </td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h6>Requerimientos de almacenaje de información</h6>
                                                        </td>
                                                        <td>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios3" id="gridRadios3a" value="cumple"
                                                                    checked>
                                                                <label class="form-check-label"
                                                                    for="gridRadios3a">Cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios3" id="gridRadios3b"
                                                                    value="no_cumple">
                                                                <label class="form-check-label" for="gridRadios3b">No
                                                                    cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios3" id="gridRadios3c"
                                                                    value="no_aplica">
                                                                <label class="form-check-label" for="gridRadios3c">No
                                                                    aplica</label>
                                                            </div>
                                                        </td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h6>Requerimientos del procesamiento de la información y de
                                                                la generación de
                                                                reportes</h6>
                                                        </td>
                                                        <td>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios4" id="gridRadios4a" value="cumple"
                                                                    checked>
                                                                <label class="form-check-label"
                                                                    for="gridRadios4a">Cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios4" id="gridRadios4b"
                                                                    value="no_cumple">
                                                                <label class="form-check-label" for="gridRadios4b">No
                                                                    cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios4" id="gridRadios4c"
                                                                    value="no_aplica">
                                                                <label class="form-check-label" for="gridRadios4c">No
                                                                    aplica</label>
                                                            </div>
                                                        </td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h6>Requerimientos de seguridad</h6>
                                                        </td>
                                                        <td>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios5" id="gridRadios5a" value="cumple"
                                                                    checked>
                                                                <label class="form-check-label"
                                                                    for="gridRadios5a">Cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios5" id="gridRadios5b"
                                                                    value="no_cumple">
                                                                <label class="form-check-label" for="gridRadios5b">No
                                                                    cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios5" id="gridRadios5c"
                                                                    value="no_aplica">
                                                                <label class="form-check-label" for="gridRadios5c">No
                                                                    aplica</label>
                                                            </div>
                                                        </td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                    </tr>
                                                    <!-- Puedes agregar más filas según sea necesario -->
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cerrar</button>
                                            <button type="button" class="btn btn-primary">Guardar cambios</button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Modal para Dictámenes Medicion -->
                            <div class="modal fade" id="dictamenesModalmedicion" tabindex="-1" role="dialog"
                                aria-labelledby="dictamenesModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <!-- Cambiado de modal-xl a modal-lg -->
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                            <h5 class="modal-title" id="dictamenesModalLabel">TABLA DE CUMPLIMIENTO
                                                SISTEMA
                                                DE MEDICION</h5>

                                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-sm">
                                                <!-- Agregada la clase table-sm para hacer la tabla más compacta -->

                                                <thead>
                                                    <tr>
                                                        <th scope="col" style="font-size: 15px;">Especificación o
                                                            requerimiento</th>
                                                        <th scope="col" style="font-size: 15px;">Opinión de
                                                            cumplimiento
                                                        </th>
                                                        <th scope="col" style="font-size: 15px;">Detalle de la
                                                            opinión
                                                            (Hallazgos)</th>
                                                        <th scope="col" style="font-size: 15px;">Recomendaciones
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <h6>Requerimientos Generales</h6>
                                                        </td>
                                                        <td>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios1" id="gridRadios1" value="cumple"
                                                                    checked>
                                                                <label class="form-check-label"
                                                                    for="gridRadios1">Cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios1" id="gridRadios2"
                                                                    value="no_cumple">
                                                                <label class="form-check-label" for="gridRadios2">No
                                                                    cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios1" id="gridRadios3"
                                                                    value="no_aplica">
                                                                <label class="form-check-label" for="gridRadios3">No
                                                                    aplica</label>
                                                            </div>
                                                        </td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h6>Requerimientos Generales</h6>
                                                            <ul class="list-group"
                                                                style="padding-left: 25px; list-style-type: none;">
                                                                <li style="font-size: 12px;">a) Datos Generales</li>
                                                                <li style="font-size: 12px;">b) Registro del volumen
                                                                </li>
                                                                <li style="font-size: 12px;">c) Tipo de hidrocarburo
                                                                    o
                                                                    petrolífero</li>
                                                                <li style="font-size: 12px;">d) Información fiscal
                                                                </li>
                                                            </ul>
                                                        </td>
                                                        <td>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios2" id="gridRadios4" value="cumple"
                                                                    checked>
                                                                <label class="form-check-label"
                                                                    for="gridRadios4">Cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios2" id="gridRadios5"
                                                                    value="no_cumple">
                                                                <label class="form-check-label" for="gridRadios5">No
                                                                    cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios2" id="gridRadios6"
                                                                    value="no_aplica">
                                                                <label class="form-check-label" for="gridRadios6">No
                                                                    aplica</label>
                                                            </div>
                                                        </td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h6>Requerimientos de almacenaje de información</h6>
                                                        </td>
                                                        <td>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios3" id="gridRadios7" value="cumple"
                                                                    checked>
                                                                <label class="form-check-label"
                                                                    for="gridRadios7">Cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios3" id="gridRadios8"
                                                                    value="no_cumple">
                                                                <label class="form-check-label" for="gridRadios8">No
                                                                    cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios3" id="gridRadios9"
                                                                    value="no_aplica">
                                                                <label class="form-check-label" for="gridRadios9">No
                                                                    aplica</label>
                                                            </div>
                                                        </td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h6>Requerimientos del procesamiento de la información y
                                                                de la
                                                                generación de reportes</h6>
                                                        </td>
                                                        <td>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios4" id="gridRadios10" value="cumple"
                                                                    checked>
                                                                <label class="form-check-label"
                                                                    for="gridRadios10">Cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios4" id="gridRadios11"
                                                                    value="no_cumple">
                                                                <label class="form-check-label" for="gridRadios11">No
                                                                    cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios4" id="gridRadios13"
                                                                    value="no_aplica">
                                                                <label class="form-check-label" for="gridRadios13">No
                                                                    aplica</label>
                                                            </div>
                                                        </td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h6>Requerimientos de seguridad</h6>
                                                        </td>
                                                        <td>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios5" id="gridRadios14" value="cumple"
                                                                    checked>
                                                                <label class="form-check-label"
                                                                    for="gridRadios14">Cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios5" id="gridRadios15"
                                                                    value="no_cumple">
                                                                <label class="form-check-label" for="gridRadios15">No
                                                                    cumple</label>
                                                            </div>
                                                            <div class="form-check" style="font-size: 13px;">
                                                                <input class="form-check-input" type="radio"
                                                                    name="gridRadios5" id="gridRadios16"
                                                                    value="no_aplica">
                                                                <label class="form-check-label" for="gridRadios16">No
                                                                    aplica</label>
                                                            </div>
                                                        </td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                        <td><textarea class="form-control" rows="1"></textarea></td>
                                                    </tr>
                                                    <!-- Puedes agregar más filas según sea necesario -->
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cerrar</button>
                                            <button type="button" class="btn btn-primary">Guardar cambios</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Formulario con soporte AJAX -->
                            <div class="modal fade" id="generarExpedienteModal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Generar
                                                Expediente de
                                                ({{$estacion->nomenclatura}})</h5>
                                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                                                aria-label="Close"></button>

                                        </div>


                                        <div class="modal-body">
                                            <!-- Formulario de generación de expediente -->
                                            <form id="generateWordForm" action="{{ route('generate.word') }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <input type="hidden" id="nomenclatura" name="nomenclatura"
                                                        value="{{ strtoupper($estacion->nomenclatura) }}">
                                                    <input type="hidden" id="id_servicio" name="id_servicio"
                                                        value="{{ $estacion->id }}">
                                                    <input type="hidden" name="id_usuario"
                                                        value="{{ $estacion->usuario->id }}">
                                                    <input type="hidden" name="fecha_actual"
                                                        value="{{ date('d/m/Y') }}">
                                                    <!-- Select dentro del formulario -->
                                                    <div class="form-group">
                                                        <label for="selectEstaciones">Selecciona una estación:</label>
                                                        <select class="form-select" id="selectEstaciones">
                                                            <option value="">Selecciona una estación</option>
                                                            @foreach ($estaciones as $estacion)
                                                                <option value="{{ $estacion->id }}">
                                                                    {{ $estacion->Razon_Social }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">

                                                        <div class="form-group">
                                                            <label for="numestacion">Numero de estacion </label>
                                                            <input type="text" name="numestacion" class="form-control"
                                                                value="{{ $archivoAnexo ? $archivoAnexo->Num_Estacion : '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="razonsocial">Razón Social</label>
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
                                                            <input type="text" name="domicilio_fiscal"
                                                                class="form-control"
                                                                value="{{ $archivoAnexo ? $archivoAnexo->Domicilio_Fiscal : '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="telefono">Teléfono</label>
                                                            <input type="text" name="telefono" class="form-control"
                                                                value="{{ $archivoAnexo ? $archivoAnexo->Telefono : '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="correo">Correo Electrónico</label>
                                                            <input type="text" name="correo" class="form-control"
                                                                value="{{ $archivoAnexo ? $archivoAnexo->Correo : '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="fecha_recepcion">Fecha de Recepción de
                                                                Solicitud</label>
                                                            <input type="date" name="fecha_recepcion"
                                                                class="form-control"
                                                                value="{{ $archivoAnexo ? $archivoAnexo->Fecha_Recepcion_Solicitud : '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="cre">Num. de Permiso de la Comisión Reguladora
                                                                de Energía</label>
                                                            <input type="text" name="cre" class="form-control"
                                                                value="{{ $archivoAnexo ? $archivoAnexo->Num_CRE : '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="constancia">Num. de Constancia de Trámite o
                                                                Estación de Servicio</label>
                                                            <input type="text" name="constancia" class="form-control"
                                                                value="{{ $archivoAnexo ? $archivoAnexo->Num_Constancia : '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="domicilio_estacion">Domicilio de la Estación de
                                                                Servicio</label>
                                                            <input type="text" name="domicilio_estacion"
                                                                class="form-control"
                                                                value="{{ $archivoAnexo ? $archivoAnexo->Domicilio_Estacion_Servicio : '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="estado">Estado</label>
                                                            <select name="estado" class="form-select" id="estado"
                                                                aria-label="Default select example">
                                                                @foreach($estados as $estado)
                                                                    <option value="{{ $estado }}" {{ $archivoAnexo && $archivoAnexo->Estado_Republica_Estacion == $estado ? 'selected' : '' }}>
                                                                        {{ $estado }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="contacto">Contacto</label>
                                                            <input type="text" name="contacto" class="form-control"
                                                                value="{{ $archivoAnexo ? $archivoAnexo->Contacto : '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nom_repre">Nombre del Representante
                                                                Legal</label>
                                                            <input type="text" name="nom_repre" class="form-control"
                                                                value="{{ $archivoAnexo ? $archivoAnexo->Nombre_Representante_Legal : '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="fecha_inspeccion">Fecha Programada de
                                                                Inspección</label>
                                                            <input type="date" name="fecha_inspeccion"
                                                                class="form-control"
                                                                value="{{ $archivoAnexo ? \Carbon\Carbon::parse($archivoAnexo->Fecha_Inspeccion)->format('Y-m-d') : '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center"
                                                        style="padding-top: 10px;">
                                                        <button type="submit"
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
                                                    <td><a href="{{ route('descargar.archivo', ['archivo' => basename($file['name'])]) }}"
                                                            class="btn btn-info" download>Descargar</a>
                                                    </td>
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

<!-- Incluir jQuery y Bootstrap, preferiblemente desde un CDN para aprovechar el caché del navegador -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>

<!-- Script optimizado -->
<script>
    $(document).ready(function () {
        $('#selectEstaciones').change(function () {
            var estacionId = $(this).val();
            if (estacionId) {
                $.ajax({
                    url: '/obtener-datos-estacion/' + estacionId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        console.log(data); // Muestra la respuesta JSON en la consola
                        $('input[name="numestacion"]').val(data.numestacion);
                        $('input[name="razonsocial"]').val(data.razonsocial);
                        $('input[name="rfc"]').val(data.rfc);
                        $('input[name="domicilio_fiscal"]').val(data.domicilio_fiscal);
                        $('input[name="telefono"]').val(data.telefono);
                        $('input[name="correo"]').val(data.correo);
                        $('input[name="fecha_recepcion"]').val(data.fecha_recepcion);
                        $('input[name="cre"]').val(data.cre);
                        $('input[name="constancia"]').val(data.constancia);
                        $('input[name="domicilio_estacion"]').val(data.domicilio_estacion);
                        $('select[name="estado"]').val(data.estado).change();
                        $('input[name="contacto"]').val(data.contacto);
                        $('input[name="nom_repre"]').val(data.nom_repre);
                        $('input[name="fecha_inspeccion"]').val(data.fecha_inspeccion);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error al obtener los datos de la estación:', error);
                    }
                });
            } else {
                // Limpiar todos los campos del formulario si no se selecciona ninguna estación
                $('input[name="id_servicio"]').val('');
                $('input[name="numestacion"]').val('');
                $('input[name="razonsocial"]').val('');
                $('input[name="rfc"]').val('');
                $('input[name="domicilio_fiscal"]').val('');
                $('input[name="telefono"]').val('');
                $('input[name="correo"]').val('');
                $('input[name="fecha_recepcion"]').val('');
                $('input[name="cre"]').val('');
                $('input[name="constancia"]').val('');
                $('input[name="domicilio_estacion"]').val('');
                $('select[name="estado"]').val('').change();
                $('input[name="contacto"]').val('');
                $('input[name="nom_repre"]').val('');
                $('input[name="fecha_inspeccion"]').val('');
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        // Obtener el ID del servicio desde el campo oculto
        const id = document.getElementById('id_servicio').value;
        const nomenclatura = document.getElementById('nomenclatura').value;
        const generatedFilesTable = document.getElementById('generatedFilesTable');
        const loadingSpinner = document.getElementById('loadingSpinner');

        // Función para mostrar u ocultar las cards
        function checkRegistro() {
            fetch(`/api/consulta/${id}`)
                .then(response => response.json())
                .then(data => {
                    const cards = document.querySelectorAll('.dictamenes-card');
                    if (data.exists) {
                        // Mostrar las cards si el registro existe
                        cards.forEach(card => card.style.display = 'block');
                    } else {
                        // Ocultar las cards si no existe el registro
                        cards.forEach(card => card.style.display = 'none');
                        // alert('Registro no encontrado');
                    }
                })
                .catch(error => console.error('Error en la solicitud AJAX:', error));
        }

        // Función para cargar los archivos generados
        function loadGeneratedFiles() {
            fetch(`/list-generated-files/${nomenclatura}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.generatedFiles && data.generatedFiles.length > 0) {
                        // Construir el HTML para la tabla de archivos generados
                        let tableHtml = '<h4>Documentos Generados:</h4><table class="table table-bordered"><thead><tr><th>Nombre del Archivo</th><th>Acción</th></tr></thead><tbody>';
                        data.generatedFiles.forEach(file => {
                            // Modificar la línea donde se genera el enlace de descarga
                            tableHtml += `<tr><td>${file.name}</td><td><a href="/descargar-archivo/${encodeURIComponent(file.name)}/${encodeURIComponent(nomenclatura)}" class="btn btn-info" download>Descargar</a></td></tr>`;
                        });
                        tableHtml += '</tbody></table>';

                        // Actualizar el contenedor de la tabla y mostrarla
                        generatedFilesTable.innerHTML = tableHtml;
                        generatedFilesTable.style.display = 'block';

                        // Después de cargar los archivos, verificar el registro
                        checkRegistro();
                    } else {
                        // Mostrar un mensaje si no se encontraron archivos generados
                        generatedFilesTable.innerHTML = '<p>No se encontraron archivos generados.</p>';
                        generatedFilesTable.style.display = 'block';

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
            loadingSpinner.classList.remove('d-none');
            loadingSpinner.classList.add('d-flex');

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
                    loadingSpinner.classList.remove('d-flex');
                    loadingSpinner.classList.add('d-none');
                });
        }

        // Ejecutar las funciones al cargar la página
        loadGeneratedFiles();

        // Asignar el manejador de eventos para el formulario
        document.getElementById('generateWordForm').addEventListener('submit', handleFormSubmit);
    });

</script>
@endsection