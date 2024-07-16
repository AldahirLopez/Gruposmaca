@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Anexo 30</h3>
    </div>
    <div class="section-body">
        <div class="row">

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title1" style="margin-top: 20px;">Servicios</h5>
                        <div class="d-flex justify-content-between">
                            <h2 class="text-right"><i class="bi bi-gear"></i></h2>
                            <div class="d-flex flex-column justify-content-between align-items-center">
                                <h2><span></span></h2>
                                <p class="m-b-0 text-right"><a
                                        href="{{ route('servicio_inspector_anexo_30.index') }}">Ver más...</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(auth()->check() && auth()->user()->hasAnyRole(['Administrador']))
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title1" style="margin-top: 20px;">Aprobaciones</h5>
                            <div class="d-flex justify-content-between">
                                <h2 class="text-right"><i class="bi bi-check-circle-fill"></i></h2>
                                <div class="d-flex flex-column justify-content-between align-items-center">
                                    <h2><span></span></h2>
                                    <p class="m-b-0 text-right"><a href="{{ route('apro.anexo') }}">Ver más...</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title1" style="margin-top: 20px;">Pagos</h5>
                            <div class="d-flex justify-content-between">
                                <h2 class="text-right"><i class="bi bi-wallet2"></i></h2>
                                <div class="d-flex flex-column justify-content-between align-items-center">
                                    <h2><span></span></h2>
                                    <p class="m-b-0 text-right"><a href="{{ route('pagosAnexo.index') }}">Ver
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