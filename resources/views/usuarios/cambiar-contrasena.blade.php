@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Cambiar Contraseña') }}</div>

                <div class="card-body">

                    <form method="POST" action="{{ route('usuarios.cambiar-contrasena', ['id' => Auth::user()->id]) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Nueva Contraseña') }}</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation"
                                class="form-label">{{ __('Confirmar Nueva Contraseña') }}</label>
                            <input id="password_confirmation" type="password" class="form-control"
                                name="password_confirmation" required autocomplete="new-password">
                        </div>
                        <a href="{{ route('home') }}" class="btn btn-danger">Calcelar</a>
                        <button type="submit" class="btn btn-primary">{{ __('Actualizar Contraseña') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection