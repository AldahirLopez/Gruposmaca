@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Estaciones de Servicio</h3>
    </div>
    <div class="section-body">
        <div class="row">
            @if(auth()->check() && auth()->user()->hasAnyRole(['Verificador Anexo 30', 'Operacion y Mantenimiento']))
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title1" style="margin-top: 20px;">Tus Estaciones de Servicio</h5>
                        <div class="d-flex justify-content-between">
                            <h2 class="text-right"><i class="bi bi-check-circle-fill"></i></h2>
                            <div class="d-flex flex-column justify-content-between align-items-center">
                                <h2><span></span></h2>
                                <p class="m-b-0 text-right"><a href="{{ route('estaciones.usuario') }}">Ver
                                        más...</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title1" style="margin-top: 20px;">Estaciones de Servicio Disponibles</h5>
                        <div class="d-flex justify-content-between">
                            <h2 class="text-right"><i class="bi bi-gear"></i></h2>
                            <div class="d-flex flex-column justify-content-between align-items-center">
                                <h2><span></span></h2>
                                <p class="m-b-0 text-right"><a href="{{ route('estaciones.generales') }}">Ver
                                        más...</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(auth()->check() && auth()->user()->hasAnyRole(['Administrador']))
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title1" style="margin-top: 20px;">Estaciones de Servicio</h5>
                        <div class="d-flex justify-content-between">
                            <h2 class="text-right"><i class="bi bi-check-circle-fill"></i></h2>
                            <div class="d-flex flex-column justify-content-between align-items-center">
                                <h2><span></span></h2>
                                <p class="m-b-0 text-right"><a href="{{ route('estaciones.usuario') }}">Ver
                                        más...</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</section>

@endsection