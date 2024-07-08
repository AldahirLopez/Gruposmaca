@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Asignacion de estaciones por usuarios</h3>
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
                                    data-target="#generarEstacionModal">
                                    Generar Nueva Relacion
                                </button>
                            @endcan
                        </div>

                        <input style="margin-top: 15px;" type="text" id="buscarEstacion" class="form-control mb-3"
                            placeholder="Buscar estación...">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">ID Usuario</th>
                                    <th scope="col">ID Estacion</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaEstaciones">
                                @foreach($UsuarioEstaciones as $UsuarioEstacion)
                                    <tr>
                                        <td>{{ $UsuarioEstacion->usuario_id }}</td>
                                        <td>{{ $UsuarioEstacion->estacion_id }}</td>
                                        <td scope="row">
                                            @can('borrar-servicio_anexo_30')

                                                {!! Form::open(['method' => 'DELETE', 'route' => ['usuario_estacion.destroy', $UsuarioEstacion->id], 'style' => 'display:inline']) !!}
                                                {!! Form::button('<i class="bi bi-trash-fill"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'title' => 'Eliminar']) !!}
                                                {!! Form::close() !!}

                                            @endcan
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
</section>

<!-- Modal para generar nueva estación -->
<div class="modal fade" id="generarEstacionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #005503  ; color: #ffffff;">
                <h5 class="modal-title" id="exampleModalLabel">Generar Nueva Estación</h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario de generación de expediente -->
                <form action="{{ route('asignar-usuarios.AsignarEstacion') }}" method="POST">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="id_usuario" value="{{ strtoupper($usuario->id) }}">
                        <input type="hidden" name="fecha_actual" value="{{ date('d/m/Y') }}">

                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select name="id_estacion" class="form-select" id="id_estacion"
                                aria-label="Default select example">
                                @foreach($estaciones as $estacion)
                                    <option value="{{ $estacion->id }}">{{ $estacion->razon_social }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select name="id_usuario" class="form-select" id="id_usuario"
                                aria-label="Default select example">
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                @endforeach
                            </select>
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
@endsection