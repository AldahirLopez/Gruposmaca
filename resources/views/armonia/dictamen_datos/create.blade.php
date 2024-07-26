@extends('layouts.app2')

@section('content')
<section class="section">
    <center>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h2>Datos dictamen</h2>
                    <form action="{{ route('dictamen_datos.store') }}" method="POST" class="row g-3">
                        @csrf

                        <div class="col-md-6">
                            <label for="num_estacion" class="form-label">Número de estación</label>
                            <input type="text" name="num_estacion" id="num_estacion" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="razon_social" class="form-label">Nombre o Razón Social</label>
                            <input type="text" name="razon_social" id="razon_social" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="domicilio_instalacion" class="form-label">Domicilio de la instalación</label>
                            <input type="text" name="domicilio_instalacion" id="domicilio_instalacion" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="rfc" class="form-label">RFC</label>
                            <input type="text" name="rfc" id="rfc" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <input type="email" name="correo" id="correo" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="tipo_contribuyente" class="form-label">Tipo de contribuyente</label>
                            <input type="text" name="tipo_contribuyente" id="tipo_contribuyente" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="domicilio_fiscal" class="form-label">Domicilio fiscal</label>
                            <input type="text" name="domicilio_fiscal" id="domicilio_fiscal" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="cre" class="form-label">No. de permiso de la CRE</label>
                            <input type="text" name="cre" id="cre" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="responsable_sgm" class="form-label">Nombre del Representante legal</label>
                            <input type="text" name="responsable_sgm" id="responsable_sgm" class="form-control" required>
                        </div>

                        <div class="col-md-12">
                            <label for="responsable_instalacion" class="form-label">Nombre de personal responsable de la
                                instalación</label>
                            <input type="text" name="responsable_instalacion" id="responsable_instalacion" class="form-control" required>
                        </div>

                        <h3 class="mt-4 mb-3">Agregue sus dispensarios</h3>
                        <p class="mb-3">
                            En caso de no contar con:
                        </p>
                        <ul class="list-group mb-3">
                            <li class="list-group-item">
                                <strong>Modelo:</strong> Colocar NM1, NM2, etc., según el número de dispensarios.
                            </li>
                            <li class="list-group-item">
                                <strong>Número de serie:</strong> Colocar NN1, NN2, etc., según el número de dispensarios.
                            </li>
                        </ul>

                        <div id="dispensarios-container" class="mb-3">
                            <!-- Aquí se agregarán los dispensarios -->
                        </div>

                        <div class="col-md-12 mb-3">
                            <button id="add-dispensario" type="button" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i>
                                Agregar dispensario</button>
                        </div>

                        <h3 class="mt-4 mb-3">Agregue sus Tanques</h3>
                        <div id="combustibles-container" class="mb-3">
                            <!-- Aquí se agregarán los combustibles -->
                        </div>

                        <div class="col-md-12 mb-3">
                            <button id="add-combustible" type="button" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i>
                                Agregar combustible</button>
                        </div>

                        <h3 class="mt-4 mb-3">Agregue sus sondas</h3>
                        <p class="mb-3">
                            En caso de no contar con:
                        </p>
                        <ul class="list-group mb-3">
                            <li class="list-group-item">
                                <strong>Marca:</strong> Colocar el producto del tanque.
                            </li>
                            <li class="list-group-item">
                                <strong>Modelo:</strong> Colocar NM1, NM2, etc., según el número de sondas.
                            </li>
                            <li class="list-group-item">
                                <strong>Número de serie:</strong> Colocar NN1, NN2, etc., según el número de sondas.
                            </li>
                        </ul>

                        <div id="sondas-container" class="mb-3">
                            <!-- Aquí se agregarán las sondas -->
                        </div>

                        <div class="col-md-12 mb-3">
                            <button id="add-sondas" type="button" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i>
                                Agregar sondas</button>
                        </div>

                        <h5 class="mt-4">Control volumétrico</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre_control" class="form-label">Nombre</label>
                                <input type="text" name="nombre_control" id="nombre_control" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="version_control" class="form-label">Versión</label>
                                <input type="text" name="version_control" id="version_control" class="form-control" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </center>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let dispensarioCount = 0;
        let combustibleCount = 0;
        let sondasCount = 0;

        document.getElementById('add-dispensario').addEventListener('click', () => {
            dispensarioCount++;
            addDispensario(dispensarioCount);
        });

        document.getElementById('add-combustible').addEventListener('click', () => {
            combustibleCount++;
            addCombustible(combustibleCount);
        });

        document.getElementById('add-sondas').addEventListener('click', () => {
            sondasCount++;
            addSondas(sondasCount);
        });

        function addSondas(count) {
            const container = document.getElementById('sondas-container');

            const sondasDiv = document.createElement('div');
            sondasDiv.className = 'sondas';
            sondasDiv.id = `sondas-${count}`;

            const sondasLabel = document.createElement('h4');
            sondasLabel.textContent = `Sonda ${count}`;
            sondasDiv.appendChild(sondasLabel);

            const marcaInput = createInputField('Marca', `sondas[${count}][marca]`);
            const modeloInput = createInputField('Modelo', `sondas[${count}][modelo]`);
            const numeroSerieInput = createInputField('Número de Serie', `sondas[${count}][numero_serie]`);

            sondasDiv.appendChild(marcaInput);
            sondasDiv.appendChild(modeloInput);
            sondasDiv.appendChild(numeroSerieInput);

            container.appendChild(sondasDiv);
        }

        function addDispensario(count) {
            const container = document.getElementById('dispensarios-container');

            const dispensarioDiv = document.createElement('div');
            dispensarioDiv.className = 'dispensario';
            dispensarioDiv.id = `dispensario-${count}`;

            const dispensarioLabel = document.createElement('h4');
            dispensarioLabel.textContent = `Dispensario ${count}`;
            dispensarioDiv.appendChild(dispensarioLabel);

            const marcaInput = createInputField('Marca', `dispensarios[${count}][marca]`);
            const modeloInput = createInputField('Modelo', `dispensarios[${count}][modelo]`);
            const numeroSerieInput = createInputField('Número de Serie', `dispensarios[${count}][numero_serie]`);

            dispensarioDiv.appendChild(marcaInput);
            dispensarioDiv.appendChild(modeloInput);
            dispensarioDiv.appendChild(numeroSerieInput);

            container.appendChild(dispensarioDiv);
        }

        function addCombustible(count) {
            const container = document.getElementById('combustibles-container');

            const combustibleDiv = document.createElement('div');
            combustibleDiv.className = 'combustible';
            combustibleDiv.id = `combustible-${count}`;

            const combustibleLabel = document.createElement('h4');
            combustibleLabel.textContent = `Combustible ${count}`;
            combustibleDiv.appendChild(combustibleLabel);

            const tipoCombustibleSelect = document.createElement('select');
            tipoCombustibleSelect.name = `combustibles[${count}][tipo]`;
            tipoCombustibleSelect.className = 'form-select';
            tipoCombustibleSelect.innerHTML = `
                <option value="Diesel">Diesel</option>
                <option value="Magna">Magna</option>
                <option value="Premium">Premium</option>
            `;
            combustibleDiv.appendChild(tipoCombustibleSelect);

            const cantidadInput = createInputField('Cantidad', `combustibles[${count}][cantidad]`);
            combustibleDiv.appendChild(cantidadInput);

            container.appendChild(combustibleDiv);
        }

        function createInputField(labelText, name) {
            const div = document.createElement('div');
            div.className = 'mb-3';

            const label = document.createElement('label');
            label.textContent = labelText;
            label.className = 'form-label';

            const input = document.createElement('input');
            input.type = 'text'; // Changed from 'number' to 'text'
            input.name = name;
            input.className = 'form-control';

            div.appendChild(label);
            div.appendChild(input);

            return div;
        }
    });
</script>
@endsection