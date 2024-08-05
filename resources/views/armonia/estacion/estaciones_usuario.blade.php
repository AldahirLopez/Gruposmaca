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
                            <a href="{{ route('estacion.selecccion') }}" class="btn btn-danger"><i class="bi bi-arrow-return-left"></i></a>

                            <!-- Botón que abre el modal para generar nueva estación -->
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#generarEstacionModal">
                                Generar Nueva Estacion
                            </button>

                        </div>

                        <input style="margin-top: 15px;" type="text" id="buscarEstacion" class="form-control mb-3" placeholder="Buscar estación...">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Numero de estacion</th>
                                    <th scope="col">Razon Social</th>
                                    <th scope="col">Direccion</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Servicios</th>
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
                                        <!-- Botón para editar estación -->
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editarEstacionModal-{{ $estacion->id }}">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        @if(auth()->check() && auth()->user()->hasRole('Administrador'))
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['estacion.destroy', $estacion->id], 'style' => 'display:inline']) !!}
                                        {!! Form::button('<i class="bi bi-trash-fill"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'title' => 'Eliminar']) !!}
                                        {!! Form::close() !!}
                                        @endif

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
    <div class="modal fade" id="editarEstacionModal-{{ $estacion->id }}" tabindex="-1" role="dialog" aria-labelledby="editarEstacionLabel-{{ $estacion->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #007bff; color: #ffffff;">
                    <h5 class="modal-title" id="editarEstacionLabel-{{ $estacion->id }}">Editar Estación</h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
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
                                    <input type="text" name="numestacion" class="form-control" value="{{ $estacion->num_estacion }}">
                                </div>
                                <div class="form-group">
                                    <label for="razonsocial">Razon Social</label>
                                    <input type="text" name="razonsocial" class="form-control" value="{{ $estacion->razon_social }}">
                                </div>
                                <div class="form-group">
                                    <label for="rfc">RFC</label>
                                    <input type="text" name="rfc" class="form-control" value="{{ $estacion->rfc }}">
                                </div>
                                <div class="form-group">
                                    <label for="domicilio_fiscal">Domicilio Fiscal</label>
                                    <input type="text" name="domicilio_fiscal" class="form-control" value="{{ $estacion->domicilio_fiscal }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefono">Telefono</label>
                                    <input type="text" name="telefono" class="form-control" value="{{ $estacion->telefono }}">
                                </div>
                                <div class="form-group">
                                    <label for="correo">Correo Electronico</label>
                                    <input type="text" name="correo" class="form-control" value="{{ $estacion->correo_electronico }}">
                                </div>
                                <div class="form-group">
                                    <label for="repre">Representante Legal</label>
                                    <input type="text" name="repre" class="form-control" value="{{ $estacion->nombre_representante_legal }}">
                                </div>
                                <div class="form-group">
                                    <label for="domicilio_estacion">Domicilio de la Estacion de Servicio</label>
                                    <input type="text" name="domicilio_estacion" class="form-control" value="{{ $estacion->domicilio_estacion_servicio }}">
                                </div>
                                <div class="form-group">
                                    <label for="estado">Estado</label>
                                    <select name="estado" class="form-select" id="estado" aria-label="Default select example">
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
<div class="modal fade" id="generarEstacionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #005503; color: #ffffff;">
                <h5 class="modal-title" id="exampleModalLabel">Generar Nueva Estación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="generarEstacionForm" action="{{ route('estacion.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_usuario" value="{{ strtoupper($usuario->id) }}">
                    <input type="hidden" name="fecha_actual" value="{{ date('d/m/Y') }}">

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Campos de estación -->
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
                        </div>
                        <!-- Select para elegir el tipo de dirección -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipo_direccion">Dirección(Fiscal y/o Instalacion)</label>
                                <select id="tipo_direccion" class="form-select" aria-label="Tipo de Dirección" required>
                                    <option value="" disabled selected>Seleccionar opción</option>
                                    <option value="direccionUnicaModal">Dirección Única</option>
                                    <option value="direccionesSeparadasModal">Direcciones Separadas</option>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center" style="padding-top: 20px;">
                <button type="submit" class="btn btn-primary btn-actualizar">Guardar</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>

<!-- Modal para dirección única -->
<div class="modal fade" id="direccionUnicaModal" tabindex="-1" aria-labelledby="direccionUnicaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #005503; color: #ffffff;">
                <h5 class="modal-title" id="direccionUnicaModalLabel">Dirección Única</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="direccionUnicaForm">
                    <div class="form-group">
                        <label for="calle_direccion_unica">Calle</label>
                        <input type="text" class="form-control" id="calle_direccion_unica" name="calle_direccion_unica" required>
                    </div>
                    <div class="form-group">
                        <label for="numero_direccion_unica">Número</label>
                        <input type="text" class="form-control" id="numero_direccion_unica" name="numero_direccion_unica" required>
                    </div>
                    <div class="form-group">
                        <label for="codigo_postal_direccion_unica">Código Postal</label>
                        <input type="text" class="form-control" id="codigo_postal_direccion_unica" name="codigo_postal_direccion_unica" required>
                    </div>
                    <div class="form-group">
                        <label for="colonia_direccion_unica">Colonia</label>
                        <input type="text" class="form-control" id="colonia_direccion_unica" name="colonia_direccion_unica" required>
                    </div>
                    <div class="form-group">
                        <label for="municipio_direccion_unica">Municipio</label>
                        <input type="text" class="form-control" id="municipio_direccion_unica" name="municipio_direccion_unica" required>
                    </div>
                    <div class="form-group">
                        <label for="entidad_federativa_direccion_unica">Entidad Federativa</label>
                        <select name="entidad_federativa_fiscal" class="form-select" id="entidad_federativa_fiscal" aria-label="Default select example">
                            @foreach($estados as $estado)
                            <option value="{{ $estado }}">
                                {{ $estado }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para direcciones separadas -->
<div class="modal fade" id="direccionesSeparadasModal" tabindex="-1" aria-labelledby="direccionesSeparadasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #005503; color: #ffffff;">
                <h5 class="modal-title" id="direccionesSeparadasModalLabel">Direcciones Separadas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="direccionesSeparadasForm">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Dirección Fiscal</h6>
                            <div class="form-group">
                                <label for="calle_fiscal">Calle</label>
                                <input type="text" class="form-control" id="calle_fiscal" name="calle_fiscal" required>
                            </div>
                            <div class="form-group">
                                <label for="numero_fiscal">Número</label>
                                <input type="text" class="form-control" id="numero_fiscal" name="numero_fiscal" required>
                            </div>
                            <div class="form-group">
                                <label for="codigo_postal_fiscal">Código Postal</label>
                                <input type="text" class="form-control" id="codigo_postal_fiscal" name="codigo_postal_fiscal" required>
                            </div>
                            <div class="form-group">
                                <label for="colonia_fiscal">Colonia</label>
                                <input type="text" class="form-control" id="colonia_fiscal" name="colonia_fiscal" required>
                            </div>
                            <div class="form-group">
                                <label for="municipio_fiscal">Municipio</label>
                                <input type="text" class="form-control" id="municipio_fiscal" name="municipio_fiscal" required>
                            </div>
                            <div class="form-group">
                                <label for="entidad_federativa_fiscal">Entidad Federativa</label>
                                <select name="entidad_federativa_fiscal" class="form-select" id="entidad_federativa_fiscal" aria-label="Default select example">
                                    @foreach($estados as $estado)
                                    <option value="{{ $estado }}">
                                        {{ $estado }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6>Dirección de Estación</h6>
                            <div class="form-group">
                                <label for="calle_estacion">Calle</label>
                                <input type="text" class="form-control" id="calle_estacion" name="calle_estacion" required>
                            </div>
                            <div class="form-group">
                                <label for="numero_estacion">Número</label>
                                <input type="text" class="form-control" id="numero_estacion" name="numero_estacion" required>
                            </div>
                            <div class="form-group">
                                <label for="codigo_postal_estacion">Código Postal</label>
                                <input type="text" class="form-control" id="codigo_postal_estacion" name="codigo_postal_estacion" required>
                            </div>
                            <div class="form-group">
                                <label for="colonia_estacion">Colonia</label>
                                <input type="text" class="form-control" id="colonia_estacion" name="colonia_estacion" required>
                            </div>
                            <div class="form-group">
                                <label for="municipio_estacion">Municipio</label>
                                <input type="text" class="form-control" id="municipio_estacion" name="municipio_estacion" required>
                            </div>
                            <div class="form-group">
                                <label for="entidad_federativa_estacion">Entidad Federativa</label>
                                <select name="entidad_federativa_fiscal" class="form-select" id="entidad_federativa_fiscal" aria-label="Default select example">
                                    @foreach($estados as $estado)
                                    <option value="{{ $estado }}">
                                        {{ $estado }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
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
    $(document).ready(function() {
        $('#buscarEstacion').keyup(function() {
            var searchText = $(this).val().toLowerCase();
            $('#tablaEstaciones tr').each(function() {
                var found = false;
                $(this).each(function() {
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipoDireccionSelect = document.getElementById('tipo_direccion');

        tipoDireccionSelect.addEventListener('change', function() {
            const selectedValue = this.value;

            // Oculta ambos modales
            $('#direccionUnicaModal').modal('hide');
            $('#direccionesSeparadasModal').modal('hide');

            // Muestra el modal correspondiente
            if (selectedValue === 'direccionUnicaModal') {
                $('#direccionUnicaModal').modal('show');
            } else if (selectedValue === 'direccionesSeparadasModal') {
                $('#direccionesSeparadasModal').modal('show');
            }
        });
    });

    // Función para guardar datos en localStorage
    function saveFormData(formId) {
        const formData = new FormData(document.getElementById(formId));
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        localStorage.setItem(formId, JSON.stringify(data));
    }

    // Función para cargar datos desde localStorage
    function loadFormData(formId) {
        const data = localStorage.getItem(formId);
        if (data) {
            const formData = JSON.parse(data);
            const form = document.getElementById(formId);
            for (const [key, value] of Object.entries(formData)) {
                const input = form.querySelector(`[name=${key}]`);
                if (input) {
                    input.value = value;
                }
            }
        }
    }

    // Función para limpiar datos de localStorage
    function clearFormData(formId) {
        localStorage.removeItem(formId);
    }

    // Asignar eventos a los modales
    document.addEventListener('DOMContentLoaded', () => {
        // Cuando se abre un modal, cargar los datos guardados
        $('#generarEstacionModal').on('show.bs.modal', () => loadFormData('generarEstacionForm'));
        $('#direccionUnicaModal').on('show.bs.modal', () => loadFormData('direccionUnicaForm'));
        $('#direccionesSeparadasModal').on('show.bs.modal', () => loadFormData('direccionesSeparadasForm'));

        // Cuando se cierra un modal, guardar los datos
        $('#generarEstacionModal').on('hide.bs.modal', () => saveFormData('generarEstacionForm'));
        $('#direccionUnicaModal').on('hide.bs.modal', () => saveFormData('direccionUnicaForm'));
        $('#direccionesSeparadasModal').on('hide.bs.modal', () => saveFormData('direccionesSeparadasForm'));
    });

    // Limpiar datos al enviar el formulario
    document.getElementById('generarEstacionForm').addEventListener('submit', () => clearFormData('generarEstacionForm'));
    document.getElementById('direccionUnicaForm').addEventListener('submit', () => clearFormData('direccionUnicaForm'));
    document.getElementById('direccionesSeparadasForm').addEventListener('submit', () => clearFormData('direccionesSeparadasForm'));
</script>

@endsection