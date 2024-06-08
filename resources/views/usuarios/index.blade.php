@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Usuarios </h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-top: 15px;">
                            <a href="{{ route('home') }}" class="btn btn-danger">Home</a>
                            @can('crear-usuarios')
                                <a class="btn btn-warning" href="{{ route('usuarios.create') }}">Nuevo</a>
                            @endcan
                        </div>

                        <!-- Table with stripped rows -->
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" style="display: none;">ID</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Rol</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuarios as $usuario)
                                    <tr>
                                        <td style="display: none;">{{$usuario->id}}</td>
                                        <td scope="row">{{$usuario->name}}</td>
                                        <td scope="row">{{$usuario->email}}</td>
                                        <td scope="row">
                                            @if(!empty($usuario->getRoleNames()))
                                                @foreach($usuario->getRoleNames() as $rolname)
                                                    {{$rolname}}
                                                @endforeach
                                            @endif
                                        </td>
                                        <td scope="row">
                                            @can('editar-usuarios')
                                                <a class="btn btn-info"
                                                    href="{{ route('usuarios.edit', $usuario->id) }}">Editar</a>
                                            @endcan

                                            @can('borrar-usuarios')
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['usuarios.destroy', $usuario->id], 'style' => 'display:inline']) !!}
                                                {!! Form::submit('Borrar', ['class' => 'btn btn-danger']) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                        {!! $usuarios->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection