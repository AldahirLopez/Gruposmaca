@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Generar Listas de Inspeccion de ({{$estacion->nomenclatura}})</h3>
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

                        <!-- Formulario con soporte AJAX -->
                        <form id="generateWordForm"
                            action="{{ route('generate.word', ['servicio_anexo_id' => $servicio_anexo_id]) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="nomenclatura"
                                    value="{{ strtoupper($estacion->nomenclatura) }}">
                                <input type="hidden" name="id_servicio" value="{{ strtoupper($estacion->id) }}">
                                <input type="hidden" name="id_usuario"
                                    value="{{ strtoupper($estacion->usuario->name) }}">
                                <input type="hidden" name="fecha_actual" value="{{ date('d/m/Y') }}">

                                <!-- Input fields aquí -->
                                <div class="col-md-6">
                                    <!-- Campos del formulario -->
                                    <div class="form-group">
                                        <label for="tipo_instalacion">Tipo de Instalación</label>
                                        <select name="tipo_instalacion" id="tipo_instalacion" class="form-control">
                                            <option value="">Seleccione...</option>
                                            <option value="estacion_servicio">Estación de Servicio</option>
                                            <option value="pozo">Pozo</option>
                                            <option value="estacion_proceso">Estaciones de Proceso</option>
                                            <option value="produccion_petroliferos">Producción de Petrolíferos</option>
                                            <option value="terminal_almacenamiento">Terminales de Almacenamiento
                                            </option>
                                            <option value="transporte_distribucion">Transporte y Distribución</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Formulario dinámico para Estación de Servicio -->
                                <div id="form_estacion_servicio" class="form-group" style="display: none;">
                                    <div class="form-group">
                                        <label for="numero_tanques">Número de Tanques</label>
                                        <input type="number" name="numero_tanques" id="numero_tanques"
                                            class="form-control" min="1" max="10"
                                            placeholder="Ingrese el número de tanques">
                                    </div>
                                    <!-- Aquí se generarán dinámicamente los formularios para los tanques -->

                                </div>
                                <div id="tanques_container"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tipoInstalacionSelect = document.getElementById('tipo_instalacion');
        const formEstacionServicio = document.getElementById('form_estacion_servicio');
        const numeroTanquesInput = document.getElementById('numero_tanques');
        const tanquesContainer = document.getElementById('tanques_container');

        // Mostrar el formulario adicional para "Estación de Servicio"
        tipoInstalacionSelect.addEventListener('change', function () {
            if (this.value === 'estacion_servicio') {
                formEstacionServicio.style.display = 'block';
            } else {
                formEstacionServicio.style.display = 'none';
                tanquesContainer.innerHTML = ''; // Limpiar los formularios de tanques si se cambia de opción
            }
        });

        // Generar formularios de tanques basados en el número ingresado
        numeroTanquesInput.addEventListener('input', function () {
            tanquesContainer.innerHTML = ''; // Limpiar el contenedor de tanques

            const numeroTanques = parseInt(this.value);
            if (numeroTanques > 0 && numeroTanques <= 10) {
                for (let i = 1; i <= numeroTanques; i++) {
                    // Crear un div para cada tanque
                    const tanqueDiv = document.createElement('div');
                    tanqueDiv.classList.add('form-group'); // Clase de fila para Bootstrap

                    // Crear el campo de texto para el nombre del tanque
                    const divNombre = document.createElement('div');
                    divNombre.classList.add('col-sm-2'); // Columna de 6
                    const labelNombre = document.createElement('label');
                    labelNombre.textContent = `Tanque ${i} Nombre:`;
                    labelNombre.classList.add('sr-only'); // Ocultar visualmente la etiqueta (accesibilidad)
                    const inputNombre = document.createElement('input');
                    inputNombre.type = 'text';
                    inputNombre.name = `tanque_nombre_${i}`;
                    inputNombre.classList.add('form-control'); // Clase de control de formulario y margen inferior

                    // Crear el campo de texto para la capacidad del tanque
                    const divCapacidad = document.createElement('div');
                    divCapacidad.classList.add('col-md-6'); // Columna de 6
                    const labelCapacidad = document.createElement('label');
                    labelCapacidad.textContent = `Tanque ${i} Capacidad (en litros):`;
                    labelCapacidad.classList.add('sr-only'); // Ocultar visualmente la etiqueta (accesibilidad)
                    const inputCapacidad = document.createElement('input');
                    inputCapacidad.type = 'number';
                    inputCapacidad.name = `tanque_capacidad_${i}`;
                    inputCapacidad.classList.add('form-control'); // Clase de control de formulario y margen inferior

                    // Agregar los elementos al div de nombre y capacidad
                    divNombre.appendChild(labelNombre);
                    divNombre.appendChild(inputNombre);
                    divCapacidad.appendChild(labelCapacidad);
                    divCapacidad.appendChild(inputCapacidad);

                    // Agregar los divs de nombre y capacidad al div del tanque
                    tanqueDiv.appendChild(divNombre);
                    tanqueDiv.appendChild(divCapacidad);

                    // Agregar el div del tanque al contenedor
                    tanquesContainer.appendChild(tanqueDiv);
                }
            }
        });
    });

</script>
@endsection