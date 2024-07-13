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

                            <!-- Botón que abre el modal para generar nueva estación -->
                            <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#generarEstacionModal">
                                Generar Nueva Estacion
                            </button>

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
                                    <th scope="col">Servicios</th>
                                    <th scope="col">Documentos</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaEstaciones">
                                @foreach($estaciones as $estacion)
                                    <tr>
                                        <td>{{ $estacion->num_estacion }}</td>
                                        <td>{{ $estacion->razon_social }}</td>
                                        <td>{{ $estacion->domicilio_estacion_servicio }}</td>
                                        <td>{{ $estacion->estado_republica_estacion }}</td>
                                        <td>Boton a servicios</td>
                                        <td>
                                            <form action="{{ route('documentacion_estacion.index') }}" method="GET"
                                                style="display:inline;">
                                                <input type="hidden" name="id" value="{{ $estacion->id }}">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-folder-fill"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <!-- Botón para editar estación -->
                                            <button type="button" class="btn btn-warning" data-toggle="modal"
                                                data-target="#editarEstacionModal-{{ $estacion->id }}">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>

                                            <!-- {!! Form::open(['method' => 'DELETE', 'route' => ['estacion.destroy', $estacion->id], 'style' => 'display:inline']) !!}
                                                                                            {!! Form::button('<i class="bi bi-trash-fill"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'title' => 'Eliminar']) !!}
                                                                                            {!! Form::close() !!}-->
                                        </td>
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

    @foreach($estaciones as $estacion)
        <!-- Modal para editar estación -->
        <div class="modal fade" id="editarEstacionModal-{{ $estacion->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editarEstacionLabel-{{ $estacion->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #007bff; color: #ffffff;">
                        <h5 class="modal-title" id="editarEstacionLabel-{{ $estacion->id }}">Editar Estación</h5>
                        <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('estacion.update', $estacion->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <input type="hidden" name="id_usuario" value="{{ strtoupper($usuario->id) }}">
                                <input type="hidden" name="fecha_actual" value="{{ date('d/m/Y') }}">
                                <!-- Campos del formulario aquí -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="numestacion">Numero de estacion</label>
                                        <input type="text" name="numestacion" class="form-control"
                                            value="{{ $estacion->num_estacion }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="razonsocial">Razon Social</label>
                                        <input type="text" name="razonsocial" class="form-control"
                                            value="{{ $estacion->razon_social }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="rfc">RFC</label>
                                        <input type="text" name="rfc" class="form-control" value="{{ $estacion->rfc }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="domicilio_fiscal">Domicilio Fiscal</label>
                                        <input type="text" name="domicilio_fiscal" class="form-control"
                                            value="{{ $estacion->domicilio_fiscal }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono">Telefono</label>
                                        <input type="text" name="telefono" class="form-control"
                                            value="{{ $estacion->telefono }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="correo">Correo Electronico</label>
                                        <input type="text" name="correo" class="form-control"
                                            value="{{ $estacion->correo_electronico }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="domicilio_estacion">Domicilio de la Estacion de Servicio</label>
                                        <input type="text" name="domicilio_estacion" class="form-control"
                                            value="{{ $estacion->domicilio_estacion_servicio }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="estado">Estado</label>
                                        <select name="estado" class="form-select" id="estado"
                                            aria-label="Default select example">
                                            @foreach($estados as $estado)
                                                <option value="{{ $estado }}" {{ $estacion->estado == $estado ? 'selected' : '' }}>
                                                    {{ $estado }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center" style="padding-top: 20px;">
                                    <button type="submit" class="btn btn-primary btn-actualizar">Actualizar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</section>
<!-- Modal para generar nueva estación -->
<div class="modal fade" id="generarEstacionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #005503; color: #ffffff;">
                <h5 class="modal-title" id="exampleModalLabel">Generar Nueva Estación</h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario de generación de expediente -->
                <form action="{{ route('estacion.store') }}" method="POST">
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

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono">Telefono</label>
                                <input type="text" name="telefono" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="correo">Correo Electronico</label>
                                <input type="text" name="correo" class="form-control">
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
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center" style="padding-top: 20px;">
                            <button type="submit" class="btn btn-success btn-generar">Generar</button>
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