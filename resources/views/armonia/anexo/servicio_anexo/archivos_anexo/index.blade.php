@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Agregar una Datos Servicio</h3>
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

                        <form action="{{ route('archivos_anexo.store', ['servicio_anexo_id' => $servicio_anexo_id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <input type="hidden" name="servicio_anexo_id" value="{{ $servicio_anexo_id }}">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="razon_social">Razon Social</label>
                                        <input type="text" name="razon_social" class="form-control" value="{{ $archivoAnexo ? $archivoAnexo->Razon_Social : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="rfc">RFC</label>
                                        <input type="text" name="rfc" class="form-control" value="{{ $archivoAnexo ? $archivoAnexo->RFC : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="domicilio_fiscal">Domicilio Fiscal</label>
                                        <input type="text" name="domicilio_fiscal" class="form-control" value="{{ $archivoAnexo ? $archivoAnexo->Domicilio_Fiscal : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="telefono">Telefono</label>
                                        <input type="text" name="telefono" class="form-control" value="{{ $archivoAnexo ? $archivoAnexo->Telefono : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="correo">Correo Electronico</label>
                                        <input type="text" name="correo" class="form-control" value="{{ $archivoAnexo ? $archivoAnexo->Correo : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_recepcion">Fecha de Recepcion de Solicitud</label>
                                        <input type="text" name="fecha_recepcion" class="form-control" value="{{ $archivoAnexo ? $archivoAnexo->Fecha_Recepcion_Solicitud : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cre">Num. de Permiso de la comision reguladora de energia</label>
                                        <input type="text" name="cre" class="form-control" value="{{ $archivoAnexo ? $archivoAnexo->Num_CRE : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="constancia">Num. de la Constancia de tramite o estacion de servicio</label>
                                        <input type="text" name="constancia" class="form-control" value="{{ $archivoAnexo ? $archivoAnexo->Num_Constancia : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="domicilio_estacion">Domicilio de la estacion de servicio</label>
                                        <input type="text" name="domicilio_estacion" class="form-control" value="{{ $archivoAnexo ? $archivoAnexo->Domicilio_Estacion_Servicio : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="estado">Estado</label>
                                        <select name="estado" class="form-select" id="estado" aria-label="Default select example">
                                            @foreach($estados as $estado)
                                            <option value="{{ $estado }}" {{ $archivoAnexo && $archivoAnexo->Direccion_Estado == $estado ? 'selected' : '' }}>
                                                {{ $estado }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="contacto">Contacto</label>
                                        <input type="text" name="contacto" class="form-control" value="{{ $archivoAnexo ? $archivoAnexo->Contacto : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="nom_repre">Nombre del representante legal</label>
                                        <input type="text" name="nom_repre" class="form-control" value="{{ $archivoAnexo ? $archivoAnexo->Nombre_Representante_Legal : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_inspeccion">Fecha Programada de la Inspeccion</label>
                                        <input type="text" name="fecha_inspeccion" class="form-control" value="{{ $archivoAnexo ? $archivoAnexo->Fecha_Inspeccion : '' }}">
                                    </div>
                                </div>
                            </div>
                            <center>
                                <div style="margin-top: 15px;">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                        <a href="{{ route('servicio_anexo.index') }}" class="btn btn-danger">Regresar</a>
                                    </div>
                                </div>
                            </center>
                        </form>
                    </div>
                    <table class="table table-striped">
                        <thead style="text-align: center;">
                            <tr>
                                <th scope="col">Documento</th>
                                <th scope="col">Archivos</th>
                            </tr>
                        </thead>
                        <tbody style="text-align: center;">

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection