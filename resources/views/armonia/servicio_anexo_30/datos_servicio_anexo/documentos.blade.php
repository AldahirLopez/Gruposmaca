@extends('layouts.app')

@section('content')
@can('Generar-documentacion-anexo_30')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Documentación del servicio {{ $servicio->nomenclatura }}</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Documentación General</h5>
                            <form action="{{ route('documentacion_anexo_general') }}" method="GET" class="text-end">
                                <input type="hidden" name="id" value="{{ $servicio->id }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-arrow-up-right-circle"></i> Ir
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Documentación Informática</h5>
                            <form action="{{ route('documentacion_anexo_informaticos') }}" method="GET" class="text-end">
                                <input type="hidden" name="id" value="{{ $servicio->id }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-arrow-up-right-circle"></i> Ir
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Documentación de Medición</h5>
                            <form action="{{ route('documentacion_anexo_medicion') }}" method="GET" class="text-end">
                                <input type="hidden" name="id" value="{{ $servicio->id }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-arrow-up-right-circle"></i> Ir
                                </button>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Documentación inspeccion</h5>
                            <form action="{{ route('documentacion_anexo_inspeccion') }}" method="GET" class="text-end">
                                <input type="hidden" name="id" value="{{ $servicio->id }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-arrow-up-right-circle"></i> Ir
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Incluir jQuery, Popper.js y Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" crossorigin="anonymous" defer></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" crossorigin="anonymous" defer></script>
@endcan
@endsection
