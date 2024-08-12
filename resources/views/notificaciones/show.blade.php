@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Solicitud de eliminación {{ $tipo_servicio }}
        </div>
        <div class="card-body">
            <h5 class="card-title">{{ $variable->nomenclatura }}</h5>
            <p class="card-text">¿Estás seguro de que deseas eliminar?</p>
            <p>Fecha de solicitud: {{ $variable->date_eliminated_at }}</p>



            @if($tipo_servicio=="Anexo 30")
            <form action="{{ route('approve.servicio.deletion', $variable->id) }}" method="POST">
                @csrf
                @method('DELETE') <!-- Agregar este campo oculto para enviar una solicitud DELETE -->
                <button type="submit" class="btn btn-danger">Eliminar</button>
                <a href="{{ route('notificaciones.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
            @endif


            @if($tipo_servicio=="Operacion y Mantenimiento")
            <form action="{{ route('approve.dictamen.deletion', $variable->id) }}" method="POST">
                @csrf
                @method('DELETE') <!-- Agregar este campo oculto para enviar una solicitud DELETE -->
                <button type="submit" class="btn btn-danger">Eliminar</button>
                <a href="{{ route('notificaciones.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>

            <!-- Manejar el caso en que ninguna de las variables esté definida -->
            @endif
        </div>
    </div>
</div>
@endsection