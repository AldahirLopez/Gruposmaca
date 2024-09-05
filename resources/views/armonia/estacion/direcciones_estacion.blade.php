@extends('layouts.app')

@section('content')
<!-- Tablas para Direcciones -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('estaciones.usuario') }}" class="btn btn-danger"><i class="bi bi-arrow-return-left"></i></a>
    <!-- Botón que abre el modal para generar nueva estación -->
</div>
<div class="container mt-4">
    <!-- Dirección Fiscal -->
    @if ($direccionFiscal)
    <h4>Dirección Fiscal</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Calle</th>
                <th>Número Exterior</th>
                <th>Número Interior</th>
                <th>Colonia</th>
                <th>Código Postal</th>
                <th>Localidad</th>
                <th>Municipio</th>
                <th>Entidad Federativa</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $direccionFiscal->calle }}</td>
                <td>{{ $direccionFiscal->numero }}</td>
                <td>{{ $direccionFiscal->numero_interior }}</td>
                <td>{{ $direccionFiscal->colonia }}</td>
                <td>{{ $direccionFiscal->codigo_postal }}</td>
                <td>{{ $direccionFiscal->localidad }}</td>
                <td>{{ $direccionFiscal->municipio }}</td>
                <td>{{ $direccionFiscal->entidad_federativa}}</td>
            </tr>
        </tbody>
    </table>
    @else
    <p>No hay dirección fiscal registrada.</p>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFiscalModal">Agregar Dirección Fiscal</button>
    @endif

    <!-- Dirección de Estación -->
    @if ($direccionEstacion)
    <h4 class="mt-4">Dirección de Estación</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Calle</th>
                <th>Número Exterior</th>
                <th>Número Interior</th>
                <th>Colonia</th>
                <th>Código Postal</th>
                <th>Localidad</th>
                <th>Municipio</th>
                <th>Entidad Federativa</th>
             
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $direccionEstacion->calle }}</td>
                <td>{{ $direccionEstacion->numero }}</td>
                <td>{{ $direccionEstacion->numero_interior }}</td>
                <td>{{ $direccionEstacion->colonia }}</td>
                <td>{{ $direccionEstacion->codigo_postal }}</td>
                <td>{{ $direccionEstacion->localidad }}</td>
                <td>{{ $direccionEstacion->municipio }}</td>
                <td>{{ $direccionEstacion->entidad_federativa }}</td>
            </tr>
        </tbody>
    </table>
    @else
    <p>No hay dirección de estación registrada.</p>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEstacionModal">Agregar Dirección de Estación</button>
    @endif
</div>

<!-- Modal para Agregar Dirección Fiscal -->
<div class="modal fade" id="addFiscalModal" tabindex="-1" role="dialog" aria-labelledby="addFiscalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content rounded-3 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addFiscalModalLabel">Agregar Dirección Fiscal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('guardar.direccion') }}" method="POST">
                    @csrf
                    <input type="hidden" name="direccionSelect" value="fiscal">
                    <input type="hidden" name="estacion_id" value="{{ $estacion->id }}">
                    <div class="mb-3">
                        <label for="entidad_federativa_fiscal" class="form-label">Entidad Federativa</label>
                        <select name="entidad_federativa_fiscal" id="entidad_federativa_fiscal" class="form-select">
                            <option value="">Seleccionar estado</option>
                            @foreach($estados as $estado)
                            <option value="{{ $estado->id }}">{{ $estado->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="calle_fiscal" class="form-label">Calle</label>
                        <input type="text" name="calle_fiscal" id="calle_fiscal" class="form-control" placeholder="Calle">
                    </div>
                    <div class="mb-3">
                        <label for="numero_ext_fiscal" class="form-label">Número Exterior</label>
                        <input type="text" name="numero_ext_fiscal" id="numero_ext_fiscal" class="form-control" placeholder="Número Exterior">
                    </div>
                    <div class="mb-3">
                        <label for="numero_int_fiscal" class="form-label">Número Interior</label>
                        <input type="text" name="numero_int_fiscal" id="numero_int_fiscal" class="form-control" placeholder="Número Interior">
                    </div>
                    <div class="mb-3">
                        <label for="colonia_fiscal" class="form-label">Colonia</label>
                        <input type="text" name="colonia_fiscal" id="colonia_fiscal" class="form-control" placeholder="Colonia">
                    </div>
                    <div class="mb-3">
                        <label for="codigo_postal_fiscal" class="form-label">Código Postal</label>
                        <input type="text" name="codigo_postal_fiscal" id="codigo_postal_fiscal" class="form-control" placeholder="Código Postal">
                    </div>
                    <div class="mb-3">
                        <label for="municipio_id_fiscal" class="form-label">Municipio</label>
                        <select name="municipio_id_fiscal" id="municipio_id_fiscal" class="form-select">
                            <option value="">Seleccionar municipio</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="localidad_fiscal" class="form-label">Localidad</label>
                        <input type="text" name="localidad_fiscal" id="localidad_fiscal" class="form-control" placeholder="Localidad">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Dirección Fiscal -->
<div class="modal fade" id="editFiscalModal" tabindex="-1" role="dialog" aria-labelledby="editFiscalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content rounded-3 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editFiscalModalLabel">Editar Dirección Fiscal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('direcciones.update', $direccionFiscal ? $direccionFiscal->id : '#') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="direccion_id" id="direccion_id">
                    <input type="hidden" name="direccionSelect" value="fiscal">
                    <input type="hidden" name="estacion_id" value="{{ $estacion->id }}">

                    <div class="mb-3">
                        <label for="calle_fiscal_edit" class="form-label">Calle</label>
                        <input type="text" name="calle_fiscal" id="calle_fiscal_edit" class="form-control" placeholder="Calle" value="{{ old('calle_fiscal', $direccionFiscal->calle ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="numero_ext_fiscal_edit" class="form-label">Número Exterior</label>
                        <input type="text" name="numero_ext_fiscal" id="numero_ext_fiscal_edit" class="form-control" placeholder="Número Exterior" value="{{ old('numero_ext_fiscal', $direccionFiscal->numero ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="numero_int_fiscal_edit" class="form-label">Número Interior</label>
                        <input type="text" name="numero_int_fiscal" id="numero_int_fiscal_edit" class="form-control" placeholder="Número Interior" value="{{ old('numero_int_fiscal', $direccionFiscal->numero_interior ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="colonia_fiscal_edit" class="form-label">Colonia</label>
                        <input type="text" name="colonia_fiscal" id="colonia_fiscal_edit" class="form-control" placeholder="Colonia" value="{{ old('colonia_fiscal', $direccionFiscal->colonia ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="codigo_postal_fiscal_edit" class="form-label">Código Postal</label>
                        <input type="text" name="codigo_postal_fiscal" id="codigo_postal_fiscal_edit" class="form-control" placeholder="Código Postal" value="{{ old('codigo_postal_fiscal', $direccionFiscal->codigo_postal ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="entidad_federativa_fiscal_edit" class="form-label">Entidad Federativa</label>
                        <select name="entidad_federativa_fiscal" id="entidad_federativa_fiscal_edit" class="form-select">
                            <option value="">Seleccionar estado</option>
                            @foreach($estados as $estado)
                            <option value="{{ $estado->id }}" {{ $estado->id == old('entidad_federativa_fiscal', $direccionFiscal->description ?? '') ? 'selected' : '' }}>
                                {{ $estado->description }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="municipio_id_fiscal_edit" class="form-label">Municipio</label>
                        <select name="municipio_id_fiscal" id="municipio_id_fiscal_edit" class="form-select">
                            <option value="">Seleccionar municipio</option>
                            @foreach($municipios as $municipio)
                            <option value="{{ $municipio->id }}" {{ $municipio->id == old('municipio_id_fiscal', $direccionFiscal->municipio_id ?? '') ? 'selected' : '' }}>
                                {{ $municipio->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="localidad_fiscal_edit" class="form-label">Localidad</label>
                        <input type="text" name="localidad_fiscal" id="localidad_fiscal_edit" class="form-control" placeholder="Localidad" value="{{ old('localidad_fiscal', $direccionFiscal->localidad ?? '') }}">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal para Agregar Dirección de Estación -->
<div class="modal fade" id="addEstacionModal" tabindex="-1" role="dialog" aria-labelledby="addEstacionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content rounded-3 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addEstacionModalLabel">Agregar Dirección de Estación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('guardar.direccion') }}" method="POST">
                    @csrf
                    <input type="hidden" name="direccionSelect" value="estacion">
                    <input type="hidden" name="estacion_id" value="{{ $estacion->id }}">

                    <div class="mb-3">
                        <label for="calle_estacion" class="form-label">Calle</label>
                        <input type="text" name="calle_estacion" id="calle_estacion" class="form-control" placeholder="Calle">
                    </div>
                    <div class="mb-3">
                        <label for="numero_ext_estacion" class="form-label">Número Exterior</label>
                        <input type="text" name="numero_ext_estacion" id="numero_ext_estacion" class="form-control" placeholder="Número Exterior">
                    </div>
                    <div class="mb-3">
                        <label for="numero_int_estacion" class="form-label">Número Interior</label>
                        <input type="text" name="numero_int_estacion" id="numero_int_estacion" class="form-control" placeholder="Número Interior">
                    </div>
                    <div class="mb-3">
                        <label for="colonia_estacion" class="form-label">Colonia</label>
                        <input type="text" name="colonia_estacion" id="colonia_estacion" class="form-control" placeholder="Colonia">
                    </div>
                    <div class="mb-3">
                        <label for="codigo_postal_estacion" class="form-label">Código Postal</label>
                        <input type="text" name="codigo_postal_estacion" id="codigo_postal_estacion" class="form-control" placeholder="Código Postal">
                    </div>
                    <div class="mb-3">
                        <label for="municipio_id_estacion" class="form-label">Localidad</label>
                        <input type="text" name="localidad_estacion" id="localidad_estacion" class="form-control" placeholder="Localidad">
                    </div>
                    <div class="mb-3">
                        <label for="municipio_id_estacion" class="form-label">Municipio</label>
                        <select name="municipio_id_estacion" id="municipio_id_estacion" class="form-select">
                            <option value="">Seleccionar municipio</option>
                            @foreach($municipios as $municipio)
                            <option value="{{ $municipio->description }}" }}>
                                {{ $municipio->description }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="entidad_federativa_id_estacion" class="form-label">Entidad Federativa</label>
                        <input type="text" name="entidad_federativa_estacion" id="entidad_federativa_estacion" class="form-control" placeholder="Entidad Federativa">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Dirección de Estación -->
<div class="modal fade" id="editEstacionModal" tabindex="-1" role="dialog" aria-labelledby="editEstacionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content rounded-3 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editEstacionModalLabel">Editar Dirección de Estación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('direcciones.update', $direccionEstacion ? $direccionEstacion->id : '#') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="direccion_id" id="direccion_id_estacion">
                    <input type="hidden" name="direccionSelect" value="estacion">
                    <input type="hidden" name="estacion_id" value="{{ $estacion->id }}">

                    <div class="mb-3">
                        <label for="calle_estacion_edit" class="form-label">Calle</label>
                        <input type="text" name="calle_estacion" id="calle_estacion_edit" class="form-control" placeholder="Calle">
                    </div>
                    <div class="mb-3">
                        <label for="numero_ext_estacion_edit" class="form-label">Número Exterior</label>
                        <input type="text" name="numero_ext_estacion" id="numero_ext_estacion_edit" class="form-control" placeholder="Número Exterior">
                    </div>
                    <div class="mb-3">
                        <label for="numero_int_estacion_edit" class="form-label">Número Interior</label>
                        <input type="text" name="numero_int_estacion" id="numero_int_estacion_edit" class="form-control" placeholder="Número Interior">
                    </div>
                    <div class="mb-3">
                        <label for="colonia_estacion_edit" class="form-label">Colonia</label>
                        <input type="text" name="colonia_estacion" id="colonia_estacion_edit" class="form-control" placeholder="Colonia">
                    </div>
                    <div class="mb-3">
                        <label for="codigo_postal_estacion_edit" class="form-label">Código Postal</label>
                        <input type="text" name="codigo_postal_estacion" id="codigo_postal_estacion_edit" class="form-control" placeholder="Código Postal">
                    </div>
                    <div class="mb-3">
                        <label for="localidad_estacion_edit" class="form-label">Localidad</label>
                        <input type="text" name="localidad_estacion" id="localidad_estacion_edit" class="form-control" placeholder="Localidad" value="{{ old('localidad_fiscal', $direccionEstacion->localidad ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="municipio_id_estacion_edit" class="form-label">Municipio</label>
                        <select name="municipio_id_estacion" id="municipio_id_estacion_edit" class="form-select">
                            <option value="">Seleccionar municipio</option>
                            @foreach($municipios as $municipio)
                            <option value="{{ $municipio->description }}" {{ $municipio->description == old('municipio_id_estacion', $direccionEstacion->municipio ?? '') ? 'selected' : '' }}>
                                {{ $municipio->description }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="entidad_federativa_estacion_edit" class="form-label">Entidad Federativa</label>
                        <input type="text" name="entidad_federativa_estacion" id="entidad_federativa_estacion_edit" class="form-control" placeholder="Entidad Federativa" value="{{ old('entidad_federativa_fiscal', $direccionEstacion->entidad_federativa ?? '') }}">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Selección de estado y municipios para la creación y edición
        const estadoSelect = document.getElementById('entidad_federativa_fiscal');
        const municipioSelect = document.getElementById('municipio_id_fiscal');

        if (estadoSelect && municipioSelect) {
            estadoSelect.addEventListener('change', function() {
                const estadoId = this.value;

                // Limpiar el select de municipios
                municipioSelect.innerHTML = '<option value="">Seleccionar municipio</option>';

                if (estadoId) {
                    fetch(`/municipios/${estadoId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(municipio => {
                                const option = document.createElement('option');
                                option.value = municipio.description;
                                option.textContent = municipio.description;
                                municipioSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error al cargar municipios:', error));
                }
            });
        }

        // Editar Dirección Fiscal
        document.querySelectorAll('button[data-bs-target="#editFiscalModal"]').forEach(button => {
            button.addEventListener('click', function() {
                const direccionId = this.getAttribute('data-id');
                fetch(`/direccion/${direccionId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('direccion_id').value = data.id;
                        document.getElementById('calle_fiscal_edit').value = data.calle;
                        document.getElementById('numero_ext_fiscal_edit').value = data.numero_ext;
                        document.getElementById('numero_int_fiscal_edit').value = data.numero_int;
                        document.getElementById('colonia_fiscal_edit').value = data.colonia;
                        document.getElementById('codigo_postal_fiscal_edit').value = data.codigo_postal;

                        // Actualizar el estado y cargar los municipios correspondientes
                        const estadoSelectEdit = document.getElementById('entidad_federativa_fiscal_edit');
                        const municipioSelectEdit = document.getElementById('municipio_id_fiscal_edit');

                        estadoSelectEdit.value = data.entidad_federativa_id;
                        fetch(`/municipios/${data.entidad_federativa_id}`)
                            .then(response => response.json())
                            .then(municipios => {
                                municipioSelectEdit.innerHTML = '<option value="">Seleccionar municipio</option>';
                                municipios.forEach(municipio => {
                                    const option = document.createElement('option');
                                    option.value = municipio.description;
                                    option.textContent = municipio.description;
                                    if (municipio.description === data.municipio) {
                                        option.selected = true;
                                    }
                                    municipioSelectEdit.appendChild(option);
                                });
                            });
                    })
                    .catch(error => console.error('Error fetching data:', error));
            });
        });

        // Editar Dirección de Estación
        document.querySelectorAll('button[data-bs-target="#editEstacionModal"]').forEach(button => {
            button.addEventListener('click', function() {
                const direccionId = this.getAttribute('data-id');
                fetch(`/direccion/${direccionId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('direccion_id_estacion').value = data.id;
                        document.getElementById('calle_estacion_edit').value = data.calle;
                        document.getElementById('numero_ext_estacion_edit').value = data.numero_ext;
                        document.getElementById('numero_int_estacion_edit').value = data.numero_int;
                        document.getElementById('colonia_estacion_edit').value = data.colonia;
                        document.getElementById('codigo_postal_estacion_edit').value = data.codigo_postal;

                        // Actualizar el estado y cargar los municipios correspondientes
                        const estadoSelectEdit = document.getElementById('entidad_federativa_estacion_edit');
                        const municipioSelectEdit = document.getElementById('municipio_id_estacion_edit');

                        estadoSelectEdit.value = data.entidad_federativa_id;
                        fetch(`/municipios/${data.entidad_federativa_id}`)
                            .then(response => response.json())
                            .then(municipios => {
                                municipioSelectEdit.innerHTML = '<option value="">Seleccionar municipio</option>';
                                municipios.forEach(municipio => {
                                    const option = document.createElement('option');
                                    option.value = municipio.description;
                                    option.textContent = municipio.description;
                                    if (municipio.description === data.municipio) {
                                        option.selected = true;
                                    }
                                    municipioSelectEdit.appendChild(option);
                                });
                            });
                    })
                    .catch(error => console.error('Error fetching data:', error));
            });
        });
    });
</script>
@endsection