@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Agregar una Servicio</h3>
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

                        <form action="{{ route('servicio_anexo.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">

                                    <div class="form-group">
                                        <label for="nombre">Nombre de la estacion</label>
                                        <input type="text" name="nombre" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="direccion">Direccion (Permiso de la CREE)</label>
                                        <input type="text" name="direccion" class="form-control">
                                    </div>
                                </div>
                                <div style="margin-top: 15px;">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                        <a href="{{ route('servicio_anexo.index') }}" class="btn btn-danger">Regresar</a>
                                    </div>
                                </div>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection