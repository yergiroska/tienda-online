@extends('layouts.app')

@section('title', 'Editar Rol de Usuario')

@section('content')
    <h1>Editar Rol de {{ $user->name }}</h1>

    <!-- Mostrar errores si existen -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update-role', $user->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Asegurar que Laravel reconoce la solicitud como PUT -->

        <label for="role">Seleccionar Rol:</label>
        <select name="role" class="form-control" required>
            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Usuario</option>
            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrador</option>
        </select>

        <button type="submit" class="btn btn-success mt-3">Actualizar Rol</button>
    </form>

    <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
@endsection
