@extends('layouts.app')

@section('content')
@can('Generar-expediente-operacion')
<section class="section">

    <div class="section-header">

        <h3 class="page__heading">Generar Expediente de ({{$servicioAnexo->nomenclatura}})</h3>
    </div>
    <div class="section-header" style="margin: 5px 5px 15px 5px;">
        <a href="{{ route('servicio_operacion.index') }}" class="btn btn-danger">
            <i class="bi bi-arrow-return-left"></i> Volver
        </a>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                @foreach ($errors->all() as $error)
                                <i class="bi bi-exclamation-octagon me-1"></i>
                                        {{$error}}
                                @endforeach  
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>                        
                            </div>

                           
                            </div>
                        @endif

                        <div class="container">

                        <div class="row">
                                <!-- Tarjeta 1 -->
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Expediente</h5>
                                            <ol class="list-group list-group-numbered" style="text-align: left;">                                           
                                                <li class="list-group-item">CONTRATO</li>
                                                <li class="list-group-item">DETEC. R.I</li>
                                                <li class="list-group-item">PLAN DE INSPECCIÓN OPERACIÓN Y MANTENIMIENTO
                                                </li>
                                                <li class="list-group-item">PROCEDIMIENTO P REVISION V 3</li>
                                                <li class="list-group-item">ORDEN DE TRABAJO</li>
                                                <li class="list-group-item">REPORTE FOTOGRAFICO</li>

                                            </ol>
                                            @can('Generar-expediente-operacion')                                                                                      
                                                <a href="#" class="btn btn-primary" id="generateExpedienteButton"
                                                 data-toggle="modal" data-target="#generarExpedienteOperacionModal"
                                                    style="margin-top: 10px;">Generar</a>
                                            @endcan

                                        </div>
                                    </div>
                                </div>

                                  <!-- Tarjeta 4 - Comprobantes -->
                            <div class="col-md-4 dictamenes-card" style="display: none;">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Comprobantes</h5>
                                            <ol class="list-group list-group-numbered" style="text-align: left;">
                                                <li class="list-group-item">COMPROBANTE DE TRASLADO
                                                </li>
                                            </ol>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal"  style="margin-top: 10px;">
                                                Generar
                                            </button>
                                        </div>
                                    </div>
                            </div>

                            

                                <!-- Tarjeta 2 - Dictámenes Informáticos 
                                <div class="col-md-4 dictamenes-card" style="display: none;">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Lista inspeccion operacion y mantenimiento</h5>
                                            <ol class="list-group list-group-numbered" style="text-align: left;">
                                                <li class="list-group-item">FORMATO LISTA INSPECCION OPERACION Y MANTENIMIENTO-V3
                                                </li>
                                            </ol>
                                            <button type="button" class="btn btn-primary" id="dictamenesButton1"
                                                data-toggle="modal" data-target="#dictamenesModalinformatico"
                                                style="margin-top: 10px;">
                                                Generar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                -->
                                <!-- Tarjeta 3 - Dictámenes de Medición -->
                                <div class="col-md-4 dictamenes-card" style="display: none;">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Acta de verificación</h5>
                                            <ol class="list-group list-group-numbered" style="text-align: left;">
                                                <li class="list-group-item">ACTA VERIFICACIÓN O.M. V3</li>
                                            </ol>             
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#actaVerificacion"  style="margin-top: 10px;">
                                                Generar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                          


            <!-- End Acta Modal-->
            <div class="modal fade" id="actaVerificacion" tabindex="-1">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                      <h5 class="modal-title">ACTA VERIFICACIÓN O.M. V3</h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('generate.acta.operacion')}}" class="row g-3" method="POST">
                            @csrf
                        <input type="hidden" id="nomenclatura" name="nomenclatura"
                                             value="{{ strtoupper($servicioAnexo->nomenclatura) }}">
                                        <input type="hidden" id="idestacion" name="idestacion"
                                            value="{{ strtoupper($estacion->id) }}">
                                        <input type="hidden" id="id_servicio" name="id_servicio"
                                            value="{{ $servicioAnexo->id }}">
                                        <input type="hidden" name="id_usuario"
                                            value="{{ $estacion->usuario->id }}">
                                            
                            <div class="col-md-6">
                                <label for="fecha_actual"class="form-label">Fecha</label>
                                <input type="date" name="fecha_actual" class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label for="" class="form-label">Hora</label>
                                <input type="time" name="hora" class="form-control" required>
                            </div>
                                
                            <div class="col-md-3">
                                <label for="" class="form-label">Hora fin</label>
                                <input type="time" name="hora_fin" class="form-control" required>
                            </div>
                            

                                <h5>Datos del encargado</h5>
                                <div class="col-md-6">
                                    <label for="recepcion" class="form-label">Nombre del encargardo de recepcion</label>
                                    <input type="text" name="recepcion"class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="cargo" class="form-label" >Cargo</label>
                                    <input type="text" name="cargo" class="form-control"required>
                                </div>

                                <div class="col-md-2">
                                    <label for="exten" class="form-label">Extension</label>
                                    <input type="text" name="exten" class="form-control" required>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="num_telefono"class="form-label">Numero telefonico</label>
                                    <input type="text" name="num_telefono" class="form-control" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="correo"class="form-label">Correo electronico</label>
                                    <input type="email" name="correo" class="form-control" required>
                                </div>

                                <h5>Testigos</h5>

                                <div class="col-md-6">
                                    <label for="folio_testigo1" class="form-label">Folio del testigo 1</label>
                                    <input type="text" name="folio_testigo1" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="nom_testigo1"class="form-label">Nombre del testigo 1</label>
                                    <input type="text" name="nom_testigo1" class="form-control" required>
                                </div>
                                
                                <div class="col-12">
                                    <label for="domicilio_testigo1"class="form-label" >Domicilio del testigo 1</label>
                                    <input type="text" name="domicilio_testigo1"class="form-control" placeholder="1234 Main St" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="folio_testigo2" class="form-label">Folio del testigo 2</label>
                                    <input type="text" name="folio_testigo2" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="nom_testigo2"class="form-label" >Nombre del testigo 2</label>
                                    <input type="text" name="nom_testigo2"  class="form-control" required>
                                </div>

                                <div class="col-12">
                                    <label for="domicilio_testigo2" class="form-label">Domicilio del testigo 2</label>
                                    <input type="text" name="domicilio_testigo2"class="form-control" placeholder="1234 Main St" required>
                                </div>

                                


                                <h5>Informacion general de la instalacion</h5>
                                <div class="col-md-12">
                                    <label for="" class="form-label">Tipo de vialidad de la estacion</label>
                                    <input type="text" name="tipo_vialidad" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">Capacidad total almacenamiento(Litros) </label>
                                    <input type="number" name="suma_tanques" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">No.Tanques de almacenamiento de doble pared</label>
                                    <input type="number" name="num_tanques" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">No.Tanques de diesel</label>
                                    <input type="number" name="num_tanques_diesel" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">Litros de diesel</label>
                                    <input type="number" name="litros_diesel" class="form-control" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">No.Tanques de gasolina</label>
                                    <input type="number" name="num_tanques_gaso" class="form-control" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Litros de gasolina</label>
                                    <input type="number" name="litros_gasolina" class="form-control" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Marca de tanque</label>
                                    <input type="text" name="marca_tanque" class="form-control" required>
                                </div>

                                <h5>Pozos</h5>
                                <div class="col-md-6">
                                    <label for="" class="form-label">No.Pozos</label>
                                    <input type="number" name="num_pozos" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">No.Pozos monitoriados</label>
                                    <input type="number" name="num_pozos_moni" class="form-control" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="num_techunbre" class="form-label">No.Techumbre(s)</label>
                                    <input type="number" name="num_techunbre" class="form-control" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="num_columnas" class="form-label">No.Columnas</label>
                                    <input type="number" name="num_columnas" class="form-control" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="tipo_material" class="form-label">Tipo de material</label>
                                    <input type="text" name="tipo_material" class="form-control" required>
                                </div>

                                <h5>Despachos</h5>
                                <div class="col-md-4">
                                    <label for="num_despachos" class="form-label">No.Despachos módulos despachadores</label>
                                    <input type="number" name="num_despachos" class="form-control" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="num_pro_diesel" class="form-label">No.Despachos para despacho de diesel</label>
                                    <input type="number" name="num_pro_diesel" class="form-control" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="num_pro_gaso" class="form-label">No.Despachos para el despacho de gasolina</label>
                                    <input type="number" name="num_pro_gaso" class="form-control" required>
                                </div>

                                <h5>Cuartos</h5>

                                <div class="col-md-4">
                                    <label for="cuarto_sucios" class="form-label">Cuarto de sucios</label>
                                    <input type="number" name="cuarto_sucios" class="form-control" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="cuarto_maquinas" class="form-label">Cuarto de maquinas</label>
                                    <input type="number" name="cuarto_maquinas" class="form-control" required>
                                </div>

                                
                                <div class="col-md-4">
                                    <label for="cuarto_electrico" class="form-label">Cuarto de electrico</label>
                                    <input type="number" name="cuarto_electrico" class="form-control" required>
                                </div> 

                                <h5>Almacen</h5>
                            
                                <div class="col-md-12">
                                    <label for="almacen" class="form-label">Almacen de residuos peligrosos</label>
                                    <input type="text" name="almacen" class="form-control" required>
                                </div> 

                                <div class="col-md-6">
                                    <label for="trampas_sucios" class="form-label">Trampas de combustible</label>
                                    <input type="number" name="trampas_sucios" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="num_fases_sucios" class="form-label">Fases de trampa de combustible</label>
                                    <input type="number" name="num_fases_sucios" class="form-control" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="tubos_veteo" class="form-label">Tubos de venteo</label>
                                    <input type="number" name="tubos_veteo" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="lado_tubos" class="form-label">Al Lado de la instalacion</label>
                                    <input type="text" name="lado_tubos" class="form-control" required>
                                </div>

                                
                                <label for="si_no_anuncion" class="form-label">Anuncio independiente(si/no)</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="si_no_anuncion" id="si_no_anuncion" value="si" required>
                                    <label class="form-check-label" for="gridRadios2">
                                        Si
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="si_no_anuncion" id="si_no_anuncion" value="No">
                                    <label class="form-check-label" for="gridRadios2">
                                        No
                                    </label>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12 text-center"
                                                        style="padding-top: 10px;">
                                                        <button type="submit" class="btn btn-primary">Generar</button>
                                                    </div>

                             
                        </form>
                    </div>
                  </div>
                </div>
              </div><!-- End Acta Modal-->






                                 
              
                    <!--  Comprobantes  Modal -->
                        <div class="modal fade" id="largeModal" tabindex="-1">
                            <div class="modal-dialog modal-fullscreen">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                <h5 class="modal-title">COMPROBANTE DE TRASLADO</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">


                            <h5 class="modal-title">Traslados</h5>


                                <form action="{{route('generate.comprobante.operacion')}}" method="POST" id="generateWordForm">
                                    @csrf
                                        <input type="hidden" id="nomenclatura" name="nomenclatura"
                                             value="{{ strtoupper($servicioAnexo->nomenclatura) }}">
                                        <input type="hidden" id="idestacion" name="idestacion"
                                            value="{{ strtoupper($estacion->id) }}">
                                        <input type="hidden" id="id_servicio" name="id_servicio"
                                            value="{{ $servicioAnexo->id }}">
                                        <input type="hidden" name="id_usuario"
                                            value="{{ $estacion->usuario->id }}">
                                            <div class="row">
                                                <div class="col-2">
                                                    <label for="fecha_inspeccion">Fecha Programada de
                                                                    Inspección</label>
                                                    <input type="date" name="fecha_inspeccion"
                                                        id="fecha_inspeccion" class="form-control" required>
                                                </div>
                                            </div>
                                            <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Origen</th>
                                                            <th>Destino</th>
                                                            <th>Transporte utilizado</th>
                                                            <th>Tipo comprobante</th>
                                                            <th>Concepto</th>
                                                            <th>Fecha de emisión</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            
                                                            <td>                                                              
                                                                    <textarea id="origen1" name="origen1" rows="5" cols="33" class="form-control" required>
                                                                        
                                                                    </textarea>            
                                                            </td>

                                                            <td>   
                                                                
                                                                    <textarea id="destino_1" name="destino_1" rows="5" cols="33" class="form-control" required>
                                                                            
                                                                    </textarea>
                                                                
                                                            </td>


                                                            <td>
                                                                <!-- TRASNSPORTE UTILZIADO -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="transporte1" id="avion1"
                                                                        value="avion" required>
                                                                    <label class="form-check-label"
                                                                        for="avion1">Avión</label>
                                                                </div>

                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="transporte1" id="autobus1"
                                                                        value="autobus">
                                                                    <label class="form-check-label"
                                                                        for="opcion1_no_cumple">Autobús</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="transporte1" id="taxi1"
                                                                        value="taxi">
                                                                    <label class="form-check-label"
                                                                        for="opcion1_no_aplica">Taxi</label>
                                                                </div>

                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="transporte1" id="oficial1"
                                                                        value="oficial">
                                                                    <label class="form-check-label"
                                                                        for="opcion1_no_aplica">Oficial</label>
                                                                </div>

                                                                <div class="form-check d-flex align-items-center">
                                                                    <input class="form-check-input" type="radio" name="transporte1" id="otro_transporte1" value="otro">
                                                                    <label class="form-check-label me-2" for="otro_transporte1">Otro</label>
                                                                    <input type="text" class="form-control w-auto" name="otro_transporte_text1" id="other_option">
                                                                </div>
                                                                
                                                            </td>


                                                            <td>
                                                                <!-- TIPO COMPROBANTE -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="comprobante1" id="factura1"
                                                                        value="factura" required>
                                                                    <label class="form-check-label"
                                                                        for="opcion1_cumple">Factura</label>
                                                                </div>

                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="comprobante1" id="boleto1"
                                                                        value="boleto">
                                                                    <label class="form-check-label"
                                                                        for="opcion1_no_cumple">Boleto</label>
                                                                </div>


                                                                <div class="form-check d-flex align-items-center">
                                                                    <input class="form-check-input" type="radio" name="comprobante1" id="opcion1_no_aplica" value="otro">
                                                                    <label class="form-check-label me-2" for="opcion1_no_aplica">Otro</label>
                                                                    <input type="text" class="form-control w-auto" name="otro_comprobante_text1" id="other_option">
                                                                </div>

                                                            </td>



                                                            <td>
                                                               <!-- CONCEPTO -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="concepto1" id="opcion1_cumple"
                                                                        value="pasaje" required>
                                                                    <label class="form-check-label"
                                                                        for="opcion1_cumple">Pasaje</label>
                                                                </div>

                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="concepto1" id="opcion1_no_cumple"
                                                                        value="caseta">
                                                                    <label class="form-check-label"
                                                                        for="opcion1_no_cumple">Caseta</label>
                                                                </div>

                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="concepto1" id="opcion1_no_cumple"
                                                                        value="combustible">
                                                                    <label class="form-check-label"
                                                                        for="opcion1_no_cumple">Combustible</label>
                                                                </div>


                                                                <div class="form-check d-flex align-items-center">
                                                                    <input class="form-check-input" type="radio" name="concepto1" id="opcion1_no_aplica" value="otro">
                                                                    <label class="form-check-label me-2" for="opcion1_no_aplica">Otro</label>
                                                                    <input type="text" class="form-control w-auto" name="otro_concepto_text1" id="other_option">
                                                                </div>
                                                            </td>

                                           
                                                            <td>
                                                                <!-- FECHA DE EMISION -->
                                                                <input type="date" name="fecha_emision1"
                                                                id="fecha_inspeccion" class="form-control" required>
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>2</td>
                                                            
                                                            <td>
                                                            <textarea id="origen2" name="origen2" rows="5" cols="33" class="form-control" required>
                                                                        
                                                                        </textarea>      
                                                            </td>

                                                            <td>
                                                            <textarea id="destino_2" name="destino_2" rows="5" cols="33" class="form-control" required>
                                                                        
                                                                        </textarea>      
                                                            </td>


                                                            <td>
                                                                <!-- Opción 1 -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="transporte2" id="opcion1_cumple"
                                                                        value="avion" required>
                                                                    <label class="form-check-label"
                                                                        for="opcion1_cumple">Avión</label>
                                                                </div>

                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="transporte2" id="opcion1_no_cumple"
                                                                        value="autobus">
                                                                    <label class="form-check-label"
                                                                        for="opcion1_no_cumple">Autobús</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="transporte2" id="opcion1_no_aplica"
                                                                        value="taxi">
                                                                    <label class="form-check-label"
                                                                        for="opcion1_no_aplica">Taxi</label>
                                                                </div>

                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="transporte2" id="opcion1_no_aplica"
                                                                        value="oficial">
                                                                    <label class="form-check-label"
                                                                        for="opcion1_no_aplica">Oficial</label>
                                                                </div>

                                                                <div class="form-check d-flex align-items-center">
                                                                    <input class="form-check-input" type="radio" name="transporte2" id="opcion1_no_aplica" value="otro">
                                                                    <label class="form-check-label me-2" for="opcion1_no_aplica">Otro</label>
                                                                    <input type="text" class="form-control w-auto" name="otro_trasnporte2_text" id="other_option">
                                                                </div>
                                                                
                                                            </td>


                                                            <td>
                                                                <!-- Opción 1 -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="comprobante2" id="opcion1_cumple"
                                                                        value="factura" required>
                                                                    <label class="form-check-label"
                                                                        for="opcion1_cumple">Factura</label>
                                                                </div>

                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="comprobante2" id="opcion1_no_cumple"
                                                                        value="boleto">
                                                                    <label class="form-check-label"
                                                                        for="opcion1_no_cumple">Boleto</label>
                                                                </div>


                                                                <div class="form-check d-flex align-items-center">
                                                                    <input class="form-check-input" type="radio" name="comprobante2" id="opcion1_no_aplica" value="otro">
                                                                    <label class="form-check-label me-2" for="opcion1_no_aplica">Otro</label>
                                                                    <input type="text" class="form-control w-auto" name="otro_comprobante2_text" id="other_option">
                                                                </div>

                                                            </td>


                                                            <td>
                                                                <!-- Opción 1 -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="concepto2" id="opcion1_cumple"
                                                                        value="pasaje" required>
                                                                    <label class="form-check-label"
                                                                        for="opcion1_cumple">Pasaje</label>
                                                                </div>

                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="concepto2" id="opcion1_no_cumple"
                                                                        value="caseta">
                                                                    <label class="form-check-label"
                                                                        for="opcion1_no_cumple">Caseta</label>
                                                                </div>

                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="concepto2" id="opcion1_no_cumple"
                                                                        value="combustible">
                                                                    <label class="form-check-label"
                                                                        for="opcion1_no_cumple">Combustible</label>
                                                                </div>


                                                                <div class="form-check d-flex align-items-center">
                                                                    <input class="form-check-input" type="radio" name="concepto2" id="opcion1_no_aplica" value="otro">
                                                                    <label class="form-check-label me-2" for="opcion1_no_aplica">Otro</label>
                                                                    <input type="text" class="form-control w-auto" name="otro_concepto2_text" id="other_option">
                                                                </div>
                                                            </td>

                                           
                                                            <td>
                                                                <input type="date" name="fecha_emision2"
                                                                id="fecha_inspeccion" class="form-control" required>
                                                            </td>
                                                        </tr>

                                                        <!-- Agregar más filas según sea necesario -->
                                                    </tbody>
                                                </table>
                                                <div class="col-xs-12 col-sm-12 col-md-12 text-center"
                                                        style="padding-top: 10px;">
                                                        <button type="submit" class="btn btn-primary">Generar</button>
                                                    </div>
                                            </form>                               
                                </div>
                            </div>
                            </div>
                        </div><!-- End  Comprobantes Modal-->


                           

                            <!-- Modal para Dictámenes Informático -->
                            <div class="modal fade" id="dictamenesModalinformatico" tabindex="-1" role="dialog"
                                aria-labelledby="dictamenesModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                            <h5 class="modal-title" id="dictamenesModalLabel">TABLA DE CUMPLIMIENTO
                                                SISTEMA INFORMATICO</h5>
                                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="generateWordDicForm" action="{{ route('guardar.dictamenes') }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" id="nomenclatura" name="nomenclatura"
                                                    value="{{ strtoupper($servicioAnexo->nomenclatura) }}">
                                                <input type="hidden" id="idestacion" name="idestacion"
                                                    value="{{ strtoupper($estacion->id) }}">
                                                <input type="hidden" id="id_servicio" name="id_servicio"
                                                    value="{{ $servicioAnexo->id }}">
                                                <input type="hidden" name="id_usuario"
                                                    value="{{ $estacion->usuario->id }}">
                                                <input type="hidden" name="fecha_actual" value="{{ date('d/m/Y') }}">
                                                <input type="hidden" type="text" name="numestacion" id="numestacion"
                                                    class="form-control" value="{{ $estacion->num_estacion }}">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Especificación o requerimiento</th>
                                                            <th>Opinión de cumplimiento</th>
                                                            <th>Detalle de la opinión (Hallazgos)</th>
                                                            <th>Recomendaciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Requerimientos Generales</td>
                                                            <td>
                                                                <!-- Opción 1 -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="opcion1" id="opcion1_cumple"
                                                                        value="cumple" checked>
                                                                    <label class="form-check-label"
                                                                        for="opcion1_cumple">Cumple</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="opcion1" id="opcion1_no_cumple"
                                                                        value="no_cumple">
                                                                    <label class="form-check-label"
                                                                        for="opcion1_no_cumple">No cumple</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="opcion1" id="opcion1_no_aplica"
                                                                        value="no_aplica">
                                                                    <label class="form-check-label"
                                                                        for="opcion1_no_aplica">No aplica</label>
                                                                </div>
                                                            </td>
                                                            <td><input class="form-control" name="detalleOpinion1"
                                                                    rows="1"></input></td>
                                                            <td><input class="form-control" name="recomendaciones1"
                                                                    rows="1"></input></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Requerimientos de almacenaje de información</td>
                                                            <td>
                                                                <!-- Opción 2 -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="opcion2" id="opcion2_cumple"
                                                                        value="cumple" checked>
                                                                    <label class="form-check-label"
                                                                        for="opcion2_cumple">Cumple</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="opcion2" id="opcion2_no_cumple"
                                                                        value="no_cumple">
                                                                    <label class="form-check-label"
                                                                        for="opcion2_no_cumple">No cumple</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="opcion2" id="opcion2_no_aplica"
                                                                        value="no_aplica">
                                                                    <label class="form-check-label"
                                                                        for="opcion2_no_aplica">No aplica</label>
                                                                </div>
                                                            </td>
                                                            <td><input class="form-control" name="detalleOpinion2"
                                                                    rows="1"></input></td>
                                                            <td><input class="form-control" name="recomendaciones2"
                                                                    rows="1"></input></td>
                                                        </tr>
                                                        <!-- Agregar más filas según sea necesario -->
                                                    </tbody>
                                                </table>

                                                <div class="col-xs-12 col-sm-12 col-md-12 text-center"
                                                    style="padding-top: 10px;">
                                                    <button type="submitButtonDictamenes"
                                                        class="btn btn-primary btn-generar">Generar</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            @can('Generar-expediente-operacion')                                                     
                            <!-- Modal para generar expediente -->
                            <div class="modal fade" id="generarExpedienteOperacionModal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Generar
                                                Expediente operacion de  ({{$servicioAnexo->nomenclatura}})</h5>
                                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Formulario de generación de expediente con soporte AJAX -->
                                            <form id="generateWordForm" action="{{ route('generate.expedientes.operacion') }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <input type="hidden" id="nomenclatura" name="nomenclatura"
                                                        value="{{ strtoupper($servicioAnexo->nomenclatura) }}">
                                                    <input type="hidden" id="idestacion" name="idestacion"
                                                        value="{{ strtoupper($estacion->id) }}">
                                                    <input type="hidden" id="id_servicio" name="id_servicio"
                                                        value="{{ $servicioAnexo->id }}">
                                                    <input type="hidden" name="id_usuario"
                                                        value="{{ $estacion->usuario->id }}">
                                                    <input type="hidden" name="fecha_actual"
                                                        value="{{ date('d/m/Y') }}">
                                                    <input type="hidden" type="text" name="numestacion" id="numestacion"
                                                        class="form-control" value="{{ $estacion->num_estacion }}">

                                                    <div class="col-md-6">
                                                        <!-- Campos del formulario que se llenarán automáticamente -->

                                                        <div class="form-group">
                                                            <label for="razonsocial">Razón Social</label>
                                                            <input type="text" name="razonsocial" id="razonsocial"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="rfc">RFC</label>
                                                            <input type="text" name="rfc" id="rfc" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="domicilio_fiscal">Domicilio Fiscal</label>
                                                            <input type="text" name="domicilio_fiscal"
                                                                id="domicilio_fiscal" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="telefono">Teléfono</label>
                                                            <input type="text" name="telefono" id="telefono"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="correo">Correo Electrónico</label>
                                                            <input type="email" name="correo" id="correo"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="fecha_recepcion">Fecha de Recepción de
                                                                Solicitud</label>
                                                            <input type="date" name="fecha_recepcion"
                                                                id="fecha_recepcion" class="form-control" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="cre">Num. de Permiso de la Comisión Reguladora
                                                                de Energía</label>
                                                            <input type="text" name="cre" id="cre" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="constancia">Num. de Constancia de Trámite o
                                                                Estación de Servicio</label>
                                                            <input type="text" name="constancia" id="constancia"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="domicilio_estacion">Domicilio de la Estación de
                                                                Servicio</label>
                                                            <input type="text" name="domicilio_estacion"
                                                                id="domicilio_estacion" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="estado">Estado</label>
                                                            <select name="estado" id="estado" class="form-select">
                                                                @foreach($estados as $estado)
                                                                    <option value="{{ $estado }}">{{ $estado }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="contacto">Contacto</label>
                                                            <input type="text" name="contacto" id="contacto"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nom_repre">Nombre del Representante
                                                                Legal</label>
                                                            <input type="text" name="nom_repre" id="nom_repre"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="fecha_inspeccion">Fecha Programada de
                                                                Inspección</label>
                                                            <input type="date" name="fecha_inspeccion"
                                                                id="fecha_inspeccion" class="form-control" required>
                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <h2 class="card-title">Datos generales del contrato</h2>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                    <label for="cantidad">Precio a pagar por el servicio de inspeccion</label>
                                                                    <input type="float" name="cantidad"
                                                                        id="cantidad" class="form-control" required>
                                                            </div>
                                                        </div>

                                                        
                                                    </div>


                                                    <div class="row">
                                                        <h2 class="card-title">Datos necesarios del plan de inspección operación y mantenimiento</h2>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="observaciones">Observaciones</label>
                                                                <textarea name="observaciones" id="observaciones" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <h2 class="card-title">Fotografias para el reporte fotografico</h2>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                                                               
                                                                <input type="file" id="images" name="images[]" alt="Login" multiple required/>

                                                            </div>
                                                        </div>
                                                    </div>



                                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center"
                                                        style="padding-top: 10px;">
                                                        <button type="submitButton"
                                                            class="btn btn-primary btn-generar">Generar</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- FIN Modal para generar expediente -->
                            @endcan

                            <!-- Modal para generar Acta de verificación  -->
                            <div class="modal fade" id="generarExpedienteModal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Generar
                                                Expediente de ({{$servicioAnexo->nomenclatura}})</h5>
                                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Formulario de generación de expediente con soporte AJAX -->
                                            <form id="generateWordForm" action="{{ route('generate.word.operacion') }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <input type="hidden" id="nomenclatura" name="nomenclatura"
                                                        value="{{ strtoupper($servicioAnexo->nomenclatura) }}">
                                                    <input type="hidden" id="idestacion" name="idestacion"
                                                        value="{{ strtoupper($estacion->id) }}">
                                                    <input type="hidden" id="id_servicio" name="id_servicio"
                                                        value="{{ $servicioAnexo->id }}">
                                                    <input type="hidden" name="id_usuario"
                                                        value="{{ $estacion->usuario->id }}">
                                                    <input type="hidden" name="fecha_actual"
                                                        value="{{ date('d/m/Y') }}">
                                                    <input type="hidden" type="text" name="numestacion" id="numestacion"
                                                        class="form-control" value="{{ $estacion->num_estacion }}">

                                                    <div class="col-md-6">
                                                        <!-- Campos del formulario que se llenarán automáticamente -->

                                                        <div class="form-group">
                                                            <label for="razonsocial">Razón Social</label>
                                                            <input type="text" name="razonsocial" id="razonsocial"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="rfc">RFC</label>
                                                            <input type="text" name="rfc" id="rfc" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="domicilio_fiscal">Domicilio Fiscal</label>
                                                            <input type="text" name="domicilio_fiscal"
                                                                id="domicilio_fiscal" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="telefono">Teléfono</label>
                                                            <input type="text" name="telefono" id="telefono"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="correo">Correo Electrónico</label>
                                                            <input type="email" name="correo" id="correo"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="fecha_recepcion">Fecha de Recepción de
                                                                Solicitud</label>
                                                            <input type="date" name="fecha_recepcion"
                                                                id="fecha_recepcion" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="cre">Num. de Permiso de la Comisión Reguladora
                                                                de Energía</label>
                                                            <input type="text" name="cre" id="cre" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="constancia">Num. de Constancia de Trámite o
                                                                Estación de Servicio</label>
                                                            <input type="text" name="constancia" id="constancia"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="domicilio_estacion">Domicilio de la Estación de
                                                                Servicio</label>
                                                            <input type="text" name="domicilio_estacion"
                                                                id="domicilio_estacion" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="estado">Estado</label>
                                                            <select name="estado" id="estado" class="form-select">
                                                                @foreach($estados as $estado)
                                                                    <option value="{{ $estado }}">{{ $estado }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="contacto">Contacto</label>
                                                            <input type="text" name="contacto" id="contacto"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nom_repre">Nombre del Representante
                                                                Legal</label>
                                                            <input type="text" name="nom_repre" id="nom_repre"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="fecha_inspeccion">Fecha Programada de
                                                                Inspección</label>
                                                            <input type="date" name="fecha_inspeccion"
                                                                id="fecha_inspeccion" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center"
                                                        style="padding-top: 10px;">
                                                        <button type="submitButton"
                                                            class="btn btn-primary btn-generar">Generar</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Contenedor para la tabla de archivos generados -->
                            <div id="generatedFilesTable" style="margin-top: 30px;">
                                <!-- Spinner de carga -->
                                <div id="loadingSpinner" class="spinner-border text-primary d-none" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>

                                <!-- Incluir la estructura HTML de tu vista actual para archivos existenFtes -->
                                @if(!empty($existingFiles))
                                    <h4>Archivos Existentes:</h4>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Nombre del Archivo</th>
                                                @can('Descargar-documentos-expediente-operacion')
                                                <th>Acción</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($existingFiles as $file)
                                            @can('Descargar-documentos-expediente-operacion')  
                                                <tr>
                                               
                                                    <td>{{ basename($file['name']) }}</td>
                                                    <!-- Mostrar solo el nombre del archivo -->
                                                                                                                                                        
                                                        <td><a href="{{ route('descargar.archivo.operacion', ['archivo' => basename($file['name'])]) }}"
                                                             class="btn btn-info" download>Descargar</a></td>
                                                    
                                                </tr>
                                                @endcan
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <!-- Mensaje o contenido alternativo si no hay archivos existentes -->
                                    <p>No hay archivos existentes.</p>
                                @endif
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<!-- Incluir jQuery y Bootstrap desde un CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>

<!-- Script optimizado -->
<script>
    $(document).ready(function () {
        // Función para cargar los archivos generados
        function loadGeneratedFiles() {
            const nomenclatura = $('#nomenclatura').val();
            fetch(`/list-generated-files-operacion/${encodeURIComponent(nomenclatura)}`)  // Codificar la nomenclatura
                .then(response => response.json())
                .then(data => {
                    const generatedFilesTable = $('#generatedFilesTable');
                    if (data && data.generatedFiles && data.generatedFiles.length > 0) {
                        // Construir el HTML para la tabla de archivos generados
                        let tableHtml = '<h4>Documentos Generados:</h4><table class="table table-bordered"><thead><tr><th>Nombre del Archivo</th><th>Acción</th></tr></thead><tbody>';
                        data.generatedFiles.forEach(file => {
                            // Modificar la línea donde se genera el enlace de descarga
                            tableHtml += `<tr><td>${file.name}</td><td><a href="/descargar-archivo-operacion/${encodeURIComponent(file.name)}/${encodeURIComponent(nomenclatura)}" class="btn btn-info" download>Descargar</a></td></tr>`;
                        });
                        tableHtml += '</tbody></table>';

                        // Actualizar el contenedor de la tabla de archivos generados y mostrarla
                        generatedFilesTable.html(tableHtml).show();

                        // Después de cargar los archivos, verificar el registro
                        checkRegistro();
                    } else {
                        // Mostrar un mensaje si no se encontraron archivos generados
                        generatedFilesTable.html('<p>No se encontraron archivos generados.</p>').show();

                        // Después de mostrar el mensaje, verificar el registro
                        checkRegistro();
                    }
                })
                .catch(error => {
                    console.error('Error al cargar los documentos generados:', error);
                    alert('Ocurrió un error al cargar los documentos generados.');
                });
        }

        // Función para manejar el envío del formulario
        function handleFormSubmit(event) {
            event.preventDefault(); // Evitar la recarga de la página

            // Mostrar el spinner de carga
            const loadingSpinner = $('#loadingSpinner');
            loadingSpinner.removeClass('d-none').addClass('d-flex');

            const formData = new FormData(event.target);

            fetch(event.target.action, {
                method: event.target.method,
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data && data.generatedFiles && data.generatedFiles.length > 0) {
                        // Recargar la página después de enviar el formulario
                        location.reload();
                    } else {
                        // Mostrar un mensaje si no se generaron archivos
                        alert('No se generaron archivos. Por favor, revise los datos ingresados.');
                    }
                })
                .catch(error => {
                    console.error('Error al generar los documentos:', error);
                    alert('Ocurrió un error al generar los documentos.');
                })
                .finally(() => {
                    // Ocultar el spinner de carga después de completar la solicitud (éxito o error)
                    loadingSpinner.removeClass('d-flex').addClass('d-none');
                });
        }

        // Función para cargar los datos de la estación al cargar la página
        function cargarDatosEstacion() {
            var estacionId = $('#idestacion').val();
            if (estacionId) {
                $.ajax({
                    url: '/obtener-datos-estacion/' + estacionId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        // Iterar sobre cada input y select del formulario
                        $('#generateWordForm input, #generateWordForm select').each(function () {
                            var nombreCampo = $(this).attr('name');
                            var valor = data[nombreCampo];

                            // Verificar si el campo tiene un valor asignado
                            if (valor !== undefined && valor !== null) {
                                // Rellenar el campo con el valor y desactivarlo
                                $(this).val(valor).prop('disabled', true);
                                // Cambiar el estilo del borde del campo
                                $(this).addClass(valor ? 'border-success' : 'border-danger');
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error('Error al obtener los datos de la estación:', error);
                    }
                });
            } else {
                console.error('No se ha proporcionado un ID de estación válido.');
            }
        }

        // Función para verificar si existe un registro y mostrar u ocultar cards
        function checkRegistro() {
            const id = $('#id_servicio').val();
          
            fetch(`/api/consulta/operacion/${id}`)
                .then(response => response.json())
                .then(data => {
                    const cards = $('.dictamenes-card');
                    if (data.exists) {
                        // Mostrar las cards si el registro existe
                        cards.show();
                    } else {
                        // Ocultar las cards si no existe el registro
                        cards.hide();
                        // alert('Registro no encontrado');
                    }
                })
                .catch(error => console.error('Error en la solicitud AJAX:', error));
        }

        // Llamar a la función para cargar los archivos generados al cargar la página
        loadGeneratedFiles();

        // Asignar el manejador de eventos para el botón de envío del formulario
        $('#submitButtonDictamenes').on('click', function () {
            $('#generateWordDicForm').submit();
        });

        // Asignar el manejador de eventos para el botón de envío del formulario
        $('#submitButton').on('click', function () {
            $('#generateWordForm').submit();
        });

        // Llamar a la función para cargar los datos de la estación al cargar la página
        cargarDatosEstacion();
    });
</script>
@endcan
@endsection