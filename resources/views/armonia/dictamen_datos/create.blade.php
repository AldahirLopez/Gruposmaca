@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Generar dictamen de datos</h3>
    </div>
        <div class="section-header" style="margin: 5px 5px 15px 5px;">
            <a href="/" class="btn btn-danger">
                <i class="bi bi-arrow-return-left"></i> Volver
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#basicModal">
               Crear dictamen de datos
              </button>
        </div>




        <div class="modal fade" id="basicModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                      <h5 class="modal-title">Dictamen de datos</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" class="btn-close btn-close-white"></button>
                    </div>
                    <div class="modal-body">
                    <form action="{{route('dictamen_datos.store')}}" method="POST" class="row g-3">
                            @csrf
                                <div class="col-md-6">
                                    <label for="" class="form-label">Numero de estacion</label>
                                    <input type="text" name="num_estacion" class="form-control" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="" class="form-label">Nombre o Razón Social</label>
                                    <input type="text" name="razon_social" class="form-control" required>
                                </div>

                                <div class="col-md-8">
                                    <label for="" class="form-label">RFC</label>
                                    <input type="text" name="rfc" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="form-label">Teléfono</label>
                                    <input type="text" name="telefono" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">Correo electronico</label>
                                    <input type="email" name="correo" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">Tipo de contribuyente</label>
                                    <input type="text" name="tipo_contribuyente" class="form-control" required>
                                </div>

                                <div class="col-md-12">
                                    <label for="" class="form-label">Domicilio de la instalalción</label>
                                    <input type="text" name="domicilio_instalacion" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">No. de permiso de la CRE</label>
                                    <input type="text" name="cre" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">Nombre del Responsable de SGM</label>
                                    <input type="text" name="responsable_sgm" class="form-control" required>
                                </div>

                                <div class="col-md-12">
                                    <label for="" class="form-label">Nombre de personal responsable de la instalación</label>
                                    <input type="text" name="responsable_sgm" class="form-control" required>
                                </div>

                                <div id="dispensarios-container">
                                    <!-- Aquí se agregarán los dispensarios -->
                                </div>

                                <div class="col-md-4">
                                    <button id="add-dispensario" type="button" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i> Agregar dispensario </button>
                                </div>

                                <div class="col-md-12">
                                    <label for="" class="form-label">Diesel</label>
                                    <input type="float" name="Diesel" class="form-control" required>
                                </div>

                                <div class="col-md-12">
                                    <label for="" class="form-label">Premium</label>
                                    <input type="float" name="Premium" class="form-control" required>
                                </div>

                                <div class="col-md-12">
                                    <label for="" class="form-label">Magna</label>
                                    <input type="float" name="Magna" class="form-control" required>
                                </div>

                                <div class="col-md-12">
                                    <label for="" class="form-label">Sondas</label>
                                    <input type="text" name="sonda" class="form-control" required>
                                </div>

                                <div id="sondas-container">
                                    <!-- Aquí se agregarán las sondas-->
                                </div>
                                <div class="col-md-4">
                                    <button id="add-sondas" type="button"  class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i> Agregar sondas</button>
                                </div>

                                <h5>Control volumetrico</h5>
                                <div class="col-md-12">
                                    <label for="" class="form-label">Nombre</label>
                                    <input type="float" name="nombre_control" class="form-control" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="" class="form-label">Version</label>
                                    <input type="float" name="version_control" class="form-control" required>
                                </div>

                            <button type="submit" class="btn btn-primary">Guardar</button>

                        </form>
                    </div>
                 
                  </div>
                </div>
              </div><!-- End Basic Modal-->








</section>
<script>

document.addEventListener('DOMContentLoaded', () => {
    let dispensarioCount = 0;
    let SondasCount = 0;
    
    document.getElementById('add-dispensario').addEventListener('click', () => {
        dispensarioCount++;
        addDispensario(dispensarioCount);
    });


    document.getElementById('add-sondas').addEventListener('click', () => {
        SondasCount++;
        addSondas(SondasCount);
    });

    function addSondas(count) {
        const container = document.getElementById('sondas-container');
        
        const dispensarioDiv = document.createElement('div');
        dispensarioDiv.className = 'sondas';
        dispensarioDiv.id = `sondas-${count}`;  // Use backticks for template literals

        const dispensarioLabel = document.createElement('h3');
        dispensarioLabel.textContent = `Sonda ${count}`;  // Use backticks for template literals
        dispensarioDiv.appendChild(dispensarioLabel);

        const marcaInput = createInputField('Marca', `sondas[${count}][marca]`);  // Use backticks for template literals
        const modeloInput = createInputField('Modelo', `sondas[${count}][modelo]`);  // Use backticks for template literals
        const numeroSerieInput = createInputField('Número de Serie', `sondas[${count}][numero_serie]`);  // Use backticks for template literals

        dispensarioDiv.appendChild(marcaInput);
        dispensarioDiv.appendChild(modeloInput);
        dispensarioDiv.appendChild(numeroSerieInput);

        container.appendChild(dispensarioDiv);

    }



    function addDispensario(count) {
        const container = document.getElementById('dispensarios-container');

        const dispensarioDiv = document.createElement('div');
        dispensarioDiv.className = 'dispensario';
        dispensarioDiv.id = `dispensario-${count}`;  // Use backticks for template literals

        const dispensarioLabel = document.createElement('h3');
        dispensarioLabel.textContent = `Dispensario ${count}`;  // Use backticks for template literals
        dispensarioDiv.appendChild(dispensarioLabel);

        const marcaInput = createInputField('Marca', `dispensarios[${count}][marca]`);  // Use backticks for template literals
        const modeloInput = createInputField('Modelo', `dispensarios[${count}][modelo]`);  // Use backticks for template literals
        const numeroSerieInput = createInputField('Número de Serie', `dispensarios[${count}][numero_serie]`);  // Use backticks for template literals

        dispensarioDiv.appendChild(marcaInput);
        dispensarioDiv.appendChild(modeloInput);
        dispensarioDiv.appendChild(numeroSerieInput);

        container.appendChild(dispensarioDiv);
    }

    function createInputField(labelText, name) {
        const div = document.createElement('div');

        const label = document.createElement('label');
        label.textContent = labelText;

        const input = document.createElement('input');
        input.type = 'text';
        input.name = name;
        input.className = 'form-control';  // Added class for consistency

        div.appendChild(label);
        div.appendChild(input);

        return div;
    }
});


</script>
@endsection>