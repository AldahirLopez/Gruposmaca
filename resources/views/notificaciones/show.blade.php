@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Solicitud de eliminación de dictamen
        </div>
        <div class="card-body">
            <h5 class="card-title">{{ $dictamen->nombre }}</h5>
            <p class="card-text">¿Estás seguro de que deseas eliminar este dictamen?</p>
            <p>Fecha de solicitud: {{ $dictamen->updated_at }}</p>

            <form action="{{ route('approval.approve', $dictamen->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Eliminar</button>
                <a href="{{ route('notificaciones.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
