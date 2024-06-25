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



                            <!-- Modal para Dictámenes Informatico-->
                            <div class="modal fade" id="dictamenesModalinformatico" tabindex="-1" role="dialog"
                                aria-labelledby="dictamenesModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <!-- Cambiado de modal-xl a modal-lg -->
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                            <h5 class="modal-title" id="dictamenesModalLabel">TABLA DE CUMPLIMIENTO
                                                SISTEMA
                                                INFORMATICO</h5>

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
                                                            <h6>Requerimientos del procesamiento de la información y
                                                                de la
                                                                generación de reportes</h6>
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
                                                            <h6>Requerimientos de seguridad</h6>
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
                                                            <h6>Requerimientos del procesamiento de la información y
                                                                de la
                                                                generación de reportes</h6>
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
                                                            <h6>Requerimientos de seguridad</h6>
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
                                            <form id="generateWordForm"
                                                action="{{ route('generate.word') }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <input type="hidden" id="nomenclatura" name="nomenclatura"
                                                        value="{{ strtoupper($estacion->nomenclatura) }}">
                                                    <input type="hidden" id="id_servicio" name="id_servicio"
                                                        value="{{ strtoupper($estacion->id) }}">
                                                    <input type="hidden" name="id_usuario"
                                                        value="{{ strtoupper($estacion->usuario->name) }}">
                                                    <input type="hidden" name="fecha_actual"
                                                        value="{{ date('d/m/Y') }}">

                                                    <!-- Campos del formulario aquí -->
                                                    <div class="col-md-6">
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
                                                            <input type="text" name="domicilio_fiscal"
                                                                class="form-control"
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
                                                            <label for="fecha_recepcion">Fecha de Recepcion de
                                                                Solicitud</label>
                                                            <input type="date" name="fecha_recepcion"
                                                                class="form-control"
                                                                value="{{ $archivoAnexo ? $archivoAnexo->Fecha_Recepcion_Solicitud : '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="cre">Num. de Permiso de la comision
                                                                reguladora de
                                                                energia</label>
                                                            <input type="text" name="cre" class="form-control"
                                                                value="{{ $archivoAnexo ? $archivoAnexo->Num_CRE : '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="constancia">Num. de la Constancia de tramite
                                                                o
                                                                estacion de servicio</label>
                                                            <input type="text" name="constancia" class="form-control"
                                                                value="{{ $archivoAnexo ? $archivoAnexo->Num_Constancia : '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="domicilio_estacion">Domicilio de la estacion
                                                                de
                                                                servicio</label>
                                                            <input type="text" name="domicilio_estacion"
                                                                class="form-control"
                                                                value="{{ $archivoAnexo ? $archivoAnexo->Domicilio_Estacion_Servicio : '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="estado">Estado</label>
                                                            <select name="estado" class="form-select" id="estado"
                                                                aria-label="Default select example">
                                                                @foreach($estados as $estado)
                                                                    <option value="{{ $estado }}" {{ $archivoAnexo && $archivoAnexo->Direccion_Estado == $estado ? 'selected' : '' }}>
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
                                                            <label for="nom_repre">Nombre del representante
                                                                legal</label>
                                                            <input type="text" name="nom_repre" class="form-control"
                                                                value="{{ $archivoAnexo ? $archivoAnexo->Nombre_Representante_Legal : '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="fecha_inspeccion">Fecha Programada de la
                                                                Inspeccion</label>
                                                            <input type="date" name="fecha_inspeccion"
                                                                class="form-control"
                                                                value="{{ old('fecha_inspeccion', isset($archivoAnexo) ? \Carbon\Carbon::parse($archivoAnexo->Fecha_Inspeccion)->format('Y-m-d') : '') }}">
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
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function () {
        // Función para cargar los archivos generados
        function loadGeneratedFiles() {
            let nomenclatura = $('#nomenclatura').val(); // Obtener la nomenclatura

            // Realizar la petición AJAX para obtener los archivos generados
            $.ajax({
                url: `/list-generated-files/${nomenclatura}`,
                type: 'GET',
                success: function (response) {
                    if (response && response.generatedFiles && response.generatedFiles.length > 0) {
                        // Construir el HTML para la tabla de archivos generados
                        let tableHtml = '<h4>Documentos Generados:</h4><table class="table table-bordered"><thead><tr><th>Nombre del Archivo</th><th>Acción</th></tr></thead><tbody>';
                        response.generatedFiles.forEach(file => {
                            tableHtml += `<tr><td>${file.name}</td><td><a href="${file.url}" class="btn btn-info" download>Descargar</a></td></tr>`;
                        });
                        tableHtml += '</tbody></table>';

                        // Actualizar el contenedor de la tabla y mostrarla
                        $('#generatedFilesTable').html(tableHtml).show();
                    } else {
                        // Mostrar un mensaje si no se encontraron archivos generados
                        $('#generatedFilesTable').html('<p>No se encontraron archivos generados.</p>').show();
                    }
                },
                error: function (xhr, status, error) {
                    // Mostrar un mensaje de error si falla la solicitud
                    alert('Ocurrió un error al cargar los documentos generados.');
                }
            });
        }

        // Llamar a la función para cargar archivos generados al cargar la página
        loadGeneratedFiles();

        // Manejar el envío del formulario para generar el expediente
        $('#generateWordForm').on('submit', function (e) {
            e.preventDefault(); // Evitar la recarga de la página

            // Ocultar la tabla de archivos generados
            $('#generatedFilesTable').hide();

            // Mostrar el spinner de carga
            $('#loadingSpinner').addClass('d-flex').removeClass('d-none');

            // Deshabilitar el botón de enviar para evitar múltiples envíos
            $('#generateWordForm button[type="submit"]').prop('disabled', true);

            // Enviar los datos del formulario usando AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response && response.generatedFiles && response.generatedFiles.length > 0) {
                        // Construir el HTML para la tabla de archivos generados
                        let tableHtml = '<h4>Documentos Generados:</h4><table class="table table-bordered"><thead><tr><th>Nombre del Archivo</th><th>Acción</th></tr></thead><tbody>';
                        response.generatedFiles.forEach(file => {
                            tableHtml += `<tr><td>${file.name}</td><td><a href="${file.url}" class="btn btn-info" download>Descargar</a></td></tr>`;
                        });
                        tableHtml += '</tbody></table>';

                        // Actualizar el contenedor de la tabla y mostrarla
                        $('#generatedFilesTable').html(tableHtml).show();
                    } else {
                        // Mostrar un mensaje si no se generaron archivos
                        alert('No se generaron archivos. Por favor, revise los datos ingresados.');
                    }
                },
                error: function (xhr, status, error) {
                    // Mostrar un mensaje de error si falla la generación de archivos
                    alert('Ocurrió un error al generar los documentos.');
                },
                complete: function () {
                    // Ocultar el spinner de carga después de completar la solicitud (éxito o error)
                    $('#loadingSpinner').removeClass('d-flex').addClass('d-none');

                    // Habilitar el botón de enviar nuevamente
                    $('#generateWordForm button[type="submit"]').prop('disabled', false);

                    // Llamar a la función para cargar archivos generados después de completar la generación
                    loadGeneratedFiles();
                }
            });
        });
    });

</script>


@endsection