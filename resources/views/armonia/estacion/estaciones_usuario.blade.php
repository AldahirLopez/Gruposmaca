@extends('layouts.app')

@section('content')
<div class="section-header">
    <h3 class="page__heading">Estaciones de servicio</h3>
</div>
<div class="section-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="{{ route('estacion.selecccion') }}" class="btn btn-danger"><i class="bi bi-arrow-return-left"></i></a>
                        <!-- Botón que abre el modal para generar nueva estación -->
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#generarEstacionModal">
                            Generar Nueva Estacion
                        </button>
                    </div>

                    <input type="text" id="buscarEstacion" class="form-control mb-3" placeholder="Buscar estación...">

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Numero de estación</th>
                                <th>Razón Social</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                                <th>Direcciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaEstaciones">
                            @foreach($estaciones as $estacion)
                            <tr>
                                <td>{{ $estacion->num_estacion }}</td>
                                <td>{{ $estacion->razon_social }}</td>
                                <td>{{ $estacion->estado_republica_estacion }}</td>
                                <td>
                                    <!-- Botón para editar estación -->
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarEstacionModal-{{ $estacion->id }}">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    @if(auth()->check() && auth()->user()->hasRole('Administrador'))
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['estacion.destroy', $estacion->id], 'style' => 'display:inline']) !!}
                                    {!! Form::button('<i class="bi bi-trash-fill"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'title' => 'Eliminar']) !!}
                                    {!! Form::close() !!}
                                    @endif
                                </td>
                                <td>
                                    <!-- Botón de Direcciones -->
                                    <a href="{{ route('estacion.direcciones', ['id' => $estacion->id]) }}" class="btn btn-secondary">
                                        Direcciones
                                    </a>

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

@foreach($estaciones as $estacion)
<!-- Modal para editar estación -->
<div class="modal fade" id="editarEstacionModal-{{ $estacion->id }}" tabindex="-1" role="dialog" aria-labelledby="editarEstacionLabel-{{ $estacion->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="editarEstacionLabel-{{ $estacion->id }}">Editar Estación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('estacion.update', $estacion->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <input type="hidden" name="id_usuario" value="{{ strtoupper($usuario->id) }}">
                        <input type="hidden" name="fecha_actual" value="{{ date('d/m/Y') }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numestacion">Número de estación</label>
                                <input type="text" name="numestacion" class="form-control" value="{{ $estacion->num_estacion }}" required>
                            </div>
                            <div class="form-group">
                                <label for="razonsocial">Razón Social</label>
                                <input type="text" name="razonsocial" class="form-control" value="{{ $estacion->razon_social }}" required>
                            </div>
                            <div class="form-group">
                                <label for="rfc">RFC</label>
                                <input type="text" name="rfc" class="form-control" value="{{ $estacion->rfc }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" name="telefono" class="form-control" value="{{ $estacion->telefono }}" required>
                            </div>
                            <div class="form-group">
                                <label for="correo">Correo Electrónico</label>
                                <input type="email" name="correo" class="form-control" value="{{ $estacion->correo_electronico }}" required>
                            </div>
                            <div class="form-group">
                                <label for="repre">Representante Legal</label>
                                <input type="text" name="repre" class="form-control" value="{{ $estacion->nombre_representante_legal }}" required>
                            </div>
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <select name="estado" class="form-select" id="estado" required>
                                    @foreach($estados as $estado)
                                    <option value="{{ $estado->description }}" {{ $estacion->estado_republica_estacion == $estado->description ? 'selected' : '' }}>
                                        {{ $estado->description }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 text-center mt-3">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal para generar nueva estación -->
<div class="modal fade" id="generarEstacionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="exampleModalLabel">Generar Nueva Estación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario de generación de expediente -->
                <form id="generarEstacionForm" action="{{ route('estacion.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="id_usuario" value="{{ strtoupper($usuario->id) }}">
                        <input type="hidden" name="fecha_actual" value="{{ date('d/m/Y') }}">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numestacion">Número de estación</label>
                                <input type="text" name="numestacion" class="form-control" required value="{{ old('numestacion') }}">
                                @error('numestacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="razonsocial">Razón Social</label>
                                <input type="text" name="razonsocial" class="form-control" required value="{{ old('razonsocial') }}">
                                @error('razonsocial')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="rfc">RFC</label>
                                <input type="text" name="rfc" class="form-control" required value="{{ old('rfc') }}">
                                @error('rfc')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" name="telefono" class="form-control" required value="{{ old('telefono') }}">
                                @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="correo">Correo Electrónico</label>
                                <input type="email" name="correo" class="form-control" required value="{{ old('correo') }}">
                                @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="repre">Representante Legal</label>
                                <input type="text" name="repre" class="form-control" required value="{{ old('repre') }}">
                                @error('repre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <select name="estado" class="form-select" id="estado" required>
                                    <option value="" selected disabled>Selecciona un estado</option>
                                    @foreach($estados as $estado)
                                    <option value="{{ $estado->description }}">{{ $estado->description }}</option>
                                    @endforeach
                                </select>
                                @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center mt-3">
                        <button type="submit" class="btn btn-primary">Generar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('buscarEstacion').addEventListener('input', function() {
        const value = this.value.toLowerCase();
        document.querySelectorAll('#tablaEstaciones tr').forEach(row => {
            const visible = row.textContent.toLowerCase().includes(value);
            row.style.display = visible ? '' : 'none';
        });
    });
</script>

@endsection