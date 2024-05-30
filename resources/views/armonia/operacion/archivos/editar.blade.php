@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Edicion de Archivos</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::model($archivo, ['method' => 'PATCH', 'route' => ['archivos.update', $archivo->id], 'enctype' => 'multipart/form-data']) !!}
                        <div class="row mb-3">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                {!! Form::text('nombre', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-10">
                                <label for="archivo" class="form-label">Archivo</label>
                                <input name="archivo" class="form-control" type="file" id="archivo">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="{{ route('archivos.index', ['dictamen_id' => $dictamen]) }}"
                                class="btn btn-danger">Regresar</a>
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection