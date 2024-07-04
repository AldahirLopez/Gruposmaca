@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Estaciones de servicio</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-top: 15px;">
                            <a href="#" class="btn btn-danger"><i class="bi bi-arrow-return-left"></i></a>
                            @can('crear-servicio')
                                <!-- Botón que abre el modal -->
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#generarExpedienteModal">
                                    Generar Nueva Estacion
                                </button>
                            @endcan
                        </div>

                        <input style="margin-top: 15px;" type="text" id="buscarEstacion" class="form-control mb-3"
                            placeholder="Buscar estación...">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Numero de estacion</th>
                                    <th scope="col">Razon Social</th>
                                    <th scope="col">Direccion</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Sercicios</th>
                                    <th scope="col">Documentos</th>
                                </tr>
                            </thead>
                            <tbody style="text-align: center;" id="tablaEstaciones">
                                @foreach($estaciones as $estacion)
                                    <tr>
                                        <td>{{ $estacion->Num_Estacion }}</td>
                                        <td>{{ $estacion->Razon_Social }}</td>
                                        <td>{{ $estacion->Domicilio_Estacion_Servicio }}</td>
                                        <td>{{ $estacion->Estado_Republica_Estacion }}</td>
                                        <td><!-- Agregar servicios --></td>
                                        <td><!-- Agregar documentos --></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<!-- Modal para generar nueva estación -->
<div class="modal fade" id="generarExpedienteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                <h5 class="modal-title" id="exampleModalLabel">Generar Nueva Estación</h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario de generación de expediente -->
                <form id="generateWordForm" action="{{ route('estaciones.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="id_usuario" value="{{ strtoupper($usuario->id) }}">
                        <input type="hidden" name="fecha_actual" value="{{ date('d/m/Y') }}">

                        <!-- Campos del formulario aquí -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numestacion">Numero de estacion </label>
                                <input type="text" name="numestacion" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="razonsocial">Razon Social</label>
                                <input type="text" name="razonsocial" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="rfc">RFC</label>
                                <input type="text" name="rfc" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="domicilio_fiscal">Domicilio Fiscal</label>
                                <input type="text" name="domicilio_fiscal" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="telefono">Telefono</label>
                                <input type="text" name="telefono" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="correo">Correo Electronico</label>
                                <input type="text" name="correo" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cre">Num. de Permiso de la Comision Reguladora de Energia</label>
                                <input type="text" name="cre" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="constancia">Num. de la Constancia de tramite o estacion de servicio</label>
                                <input type="text" name="constancia" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="domicilio_estacion">Domicilio de la Estacion de Servicio</label>
                                <input type="text" name="domicilio_estacion" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <select name="estado" class="form-select" id="estado"
                                    aria-label="Default select example">
                                    @foreach($estados as $estado)
                                        <option value="{{ $estado }}">{{ $estado }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="contacto">Contacto</label>
                                <input type="text" name="contacto" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="nom_repre">Nombre del Representante Legal</label>
                                <input type="text" name="nom_repre" class="form-control">
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
<!-- Incluir jQuery y Bootstrap, preferiblemente desde un CDN para aprovechar el caché del navegador -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>
<script>
        $(document).ready(function () {
            $('#buscarEstacion').keyup(function () {
                var searchText = $(this).val().toLowerCase();
                $('#tablaEstaciones tr').each(function () {
                    var found = false;
                    $(this).each(function () {
                        if ($(this).text().toLowerCase().indexOf(searchText) >= 0) {
                            found = true;
                            return false;
                        }
                    });
                    found ? $(this).show() : $(this).hide();
                });
            });
        });
    </script>
@endsection