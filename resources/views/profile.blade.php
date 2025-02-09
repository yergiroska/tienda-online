@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@section('content')
    <h1>Perfil de {{ $user->name }}</h1>

    <table class="table">
        <tr>
            <th>Nombre:</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email:</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Rol:</th>
            <td>{{ ucfirst($user->role) }}</td>
        </tr>
    </table>

    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Editar Perfil</a>
    
    <!-- Botón para asignar productos -->
    <a href="{{ route('users.assign-products', $user->id) }}" class="btn btn-info">Asignar Productos</a>

    <!-- Formulario para eliminar cuenta -->
    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar tu cuenta?')">Eliminar Cuenta</button>
    </form>

    <!--<a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver</a>-->
@endsection
