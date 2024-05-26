@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Solicitud de eliminación
        </div>
        <div class="card-body">
            @if(isset($variable->nomenclatura))
            <h5 class="card-title">{{ $variable->nomenclatura }}</h5>
            @elseif(isset($variable->nombre))
            <h5 class="card-title">{{ $variable->nombre }}</h5>
            @else
            <!-- Manejar el caso en que ninguna de las variables esté definida -->
            @endif
            <p class="card-text">¿Estás seguro de que deseas eliminar?</p>
            <p>Fecha de solicitud: {{ $variable->eliminated_at }}</p>

            @if(isset($variable->nomenclatura))
            <form action="{{ route('approval.approve', $variable->nomenclatura) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Eliminar</button>
                <a href="{{ route('notificaciones.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
            @elseif(isset($variable->nombre))
            <form action="{{ route('approval.approve', $variable->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Eliminar</button>
                <a href="{{ route('notificaciones.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
            @else
            <!-- Manejar el caso en que ninguna de las variables esté definida -->
            @endif
        </div>
    </div>
</div>
@endsection