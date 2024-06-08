@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">{{ isset($formato) ? 'Editar' : 'Nuevo' }} Formato</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {!! Form::model(isset($formato) ? $formato : null, ['route' => ['archivos.save', isset($formato) ? $formato->id : null], 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                        <div class="form-group">
                            {!! Form::label('nombre', 'Nombre') !!}
                            {!! Form::text('nombre', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('archivo', 'Archivo (dejar en blanco para mantener el actual)') !!}
                            {!! Form::file('archivo', ['class' => 'form-control']) !!}
                        </div>
                        <div style="margin-top: 15px;">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                                <a href="{{ route('listar.anexo30') }}" class="btn btn-danger">Cancelar</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection