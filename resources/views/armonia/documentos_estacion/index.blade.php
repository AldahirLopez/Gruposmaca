@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Documentacion de la estacion -> {{$estacion->razon_social}} </h3>
    </div>
    <div class="section-body">

        <div class="row">
            <div style="margin: 15px 15px 15px 0;">
                <a href="{{ route('estacion.index') }}" class="btn btn-danger"><i class="bi bi-arrow-return-left"></i>
                    Volver</a>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title1" style="margin-top: 20px;">Documentos Anexo 30</h5>
                        <div class="d-flex justify-content-between">
                            <h2 class="text-right"><i class="bi bi-gear"></i></h2>
                            <div class="d-flex flex-column justify-content-between align-items-center">
                                <h2><span></span></h2>
                                <p class="m-b-0 text-right"><a href="#">Ver más...</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title1" style="margin-top: 20px;">Documentos Operacion y Mantenimiento</h5>
                        <div class="d-flex justify-content-between">
                            <h2 class="text-right"><i class="bi bi-check-circle-fill"></i></h2>
                            <div class="d-flex flex-column justify-content-between align-items-center">
                                <h2><span></span></h2>
                                <a href="{{ route('documentacion_operacion', ['id' => $estacion->id]) }}">Ver más...</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection