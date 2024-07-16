@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Dictámenes de Diseño</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-top: 15px;">
                            <a href="{{ route('home') }}" class="btn btn-danger"><i
                                    class="bi bi-arrow-return-left"></i></a>
                            <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#generarServicioModal">
                                Generar Nuevo Dictamen
                            </button>
                        </div>
                        <table class="table table-striped">
                            <thead style="text-align: center;">
                                <tr>
                                    <th scope="col">Folio</th>
                                    <th scope="col">Razón Social</th>
                                    <th scope="col">Documento</th>
                                    <th scope="col">Sustento</th>
                                    @if(auth()->check() && auth()->user()->hasRole('Administrador'))
                                        <th scope="col">Acciones</th>
                                    @endif
                                    <th scope="col">Fecha de Emisión</th>
                                </tr>
                            </thead>
                            <tbody style="text-align: center;">
                                @foreach ($dictamenes as $dictamen)
                                    <tr>
                                        <td>{{ $dictamen->nomenclatura }}</td>
                                        <td>{{ $dictamen->estacion->razon_social }}</td>
                                        <td>
                                            <button class="btn btn-primary"
                                                href="{{ route('diseño.download', $dictamen->id) }}">
                                                <i class="bi bi-download"></i>
                                            </button>
                                        </td>
                                        <td>
                                            @if ($dictamen->rutadoc_sustento_diseño)
                                                <a href="{{ Storage::url($dictamen->rutadoc_sustento_diseño) }}"
                                                    class="btn btn-success" target="_blank">
                                                    Ver sustento
                                                </a>
                                            @else
                                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                                    data-target="#subirSustentoModal{{ $dictamen->id }}">
                                                    Subir Sustento
                                                </button>
                                            @endif
                                        </td>
                                        @if(auth()->check() && auth()->user()->hasRole('Administrador'))
                                            <td>
                                                <!-- Acciones para administradores -->
                                                <!-- Por ejemplo: eliminar dictamen -->
                                            </td>
                                        @endif
                                        <td>{{ $dictamen->fecha_emision }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal para generar nuevo dictamen -->
<div class="modal fade" id="generarServicioModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Generar Dictamen de Diseño</h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario de generación de dictamen de diseño -->
                <form id="generateWordForm" action="{{ route('diseño.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <!-- Campos del formulario para generar dictamen de diseño -->
                    <h5 class="modal-title" style="padding-top: 10px;">Seleccione una estación</h5>
                    <div class="row">
                        <div class="form-group" style="padding-top: 10px;">
                            <select name="estacion_id" class="form-select" id="estacion_id">
                                <option value="">Selecciona una estación</option>
                                @foreach ($estaciones as $estacion)
                                    <option value="{{ $estacion->id }}">{{ $estacion->razon_social }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 10px;">
                        <div class="form-group col-md-6">
                            <label for="fecha_inicio">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" id="fecha_inicio" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="fecha_emision">Fecha de Emisión</label>
                            <input type="date" name="fecha_emision" class="form-control" id="fecha_emision" required>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 10px;">
                        <div class="form-group col-md-6">
                            <label for="usuario_operacion_mantenimiento">Verificador</label>
                            <select name="usuario_operacion_mantenimiento" class="form-select"
                                id="usuario_operacion_mantenimiento">
                                <option value="">Selecciona un usuario</option>
                                @foreach ($usuariosOperacionMantenimiento as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="todos_los_usuarios">Gerente Técnico</label>
                            <select name="todos_los_usuarios" class="form-select" id="todos_los_usuarios">
                                <option value="">Selecciona un usuario</option>
                                @foreach ($todosLosUsuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center" style="padding-top: 10px;">
                        <button type="submit" class="btn btn-primary btn-generar">Generar Dictamen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal para subir sustento -->
@foreach ($dictamenes as $dictamen)
    <div class="modal fade" id="subirSustentoModal{{ $dictamen->id }}" tabindex="-1" role="dialog"
        aria-labelledby="subirSustentoModalLabel{{ $dictamen->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subirSustentoModalLabel{{ $dictamen->id }}">Subir Sustento para
                        {{ $dictamen->nomenclatura }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('diseño.subirSustento', $dictamen->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="sustento">Selecciona un archivo PDF como sustento:</label>
                            <input type="file" class="form-control-file" id="sustento" name="sustento" accept=".pdf">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Subir Sustento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
<script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>
@endsection