@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Registro de roles</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                             @if($errors->any())
                             <div class="alert alert-dark alert-dismissible fade show" role="alert">
                                <strong>¡Rellene los campos correctamente!</strong>
                                @foreach($errors->all() as $error)
                                    <span class="badge badge-danger">{{$error}}</span>
                                @endforeach
                                    <button type="button" class="close" data-dismisss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                              </div>
                              @endif
                             {!! Form::open(array('route' => 'roles.store', 'method' => 'POST')) !!}
                             <div class="row">


                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label form="name">Nombre</label>
                                        {!! Form::text('name', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label form="name">Permisos para este rol</label>
                                        <br/>
                                        @foreach($permission as $value)
                                        <label>
                                            {{ Form::checkbox('permission[]', $value->name, false, array('class' => 'name')) }}
                                            {{ $value->name}}
                                        </label>
                                        <br/>
                                        @endforeach                                       
                                    </div>
                                </div>                             
                             </div>                 
                             <div class="col-xs-12 col-sm-12 col-md-12">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                             </div>                     
                         {!! Form::close() !!}      
                       </div>                 
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection