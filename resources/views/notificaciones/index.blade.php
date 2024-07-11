@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-top: 15px;">
                            <a href="{{ route('home') }}" class="btn btn-danger">Home</a>
                        </div>
                        <div class="section-header" style="margin-top: 15px;">
                            <h3 class="page__heading">Pendientes Dictámenes operación y mantenimiento</h3>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Identificador</th>
                                    <th scope="col">Detalles</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dictamenes as $dictamen)
                                    <tr>
                                        <td scope="row">{{$dictamen->nomenclatura}}</td>
                                        <td scope="row">
                                            <a href="{{ route('archivos.index', ['dictamen_id' => $dictamen->id]) }}"
                                                class="btn btn-info">Listar Archivos</a>
                                        </td>
                                        <td scope="row">
                                            @can('editar-operacion')
                                                <a class="btn btn-primary"
                                                    href="{{ route('operacion.edit', $dictamen->id) }}">Editar</a>
                                            @endcan
                                            @can('borrar-operacion')
                                                <form action="{{ route('approval.cancel', $dictamen->id) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning">Cancelar Eliminacion</button>
                                                </form>
                                                <form action="{{ route('approve.dictamen.deletion', $dictamen->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <!-- Agregar este campo oculto para enviar una solicitud DELETE -->
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        <div class="section-header" style="margin-top: 15px;">
                            <h3 class="page__heading">Pendientes anexo 30</h3>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Identificador</th>
                                    <th scope="col">Archivos</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($servicios as $servicio)
                                    <tr>
                                        <td scope="row">{{$servicio->nomenclatura}}</td>
                                        <td scope="row">
                                            <a href="{{ route('archivos.index', ['servicio_nomenclatura' => $servicio->nomenclatura]) }}"
                                                class="btn btn-info">Listar Archivos</a>
                                        </td>
                                        <td scope="row">
                                            @can('borrar-servicio')
                                                <form action="{{ route('approval.cancel', $servicio->nomenclatura) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning">Cancelar Eliminacion</button>
                                                </form>
                                                <form action="{{ route('approve.servicio.deletion', $servicio->nomenclatura) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <!-- Agregar este campo oculto para enviar una solicitud DELETE -->
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection