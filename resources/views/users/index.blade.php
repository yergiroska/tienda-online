@extends('layouts.app')

@section('content')
    <h1>Lista de Usuarios</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">Crear Usuario</a>
    <table class="table table-striped">
        <tr>
            <th>ID</th>
            <th>Nombre y Apellido</th>
            <th>Email</th>
            <th>Rol</th>
            @if(auth()->user()->role === 'admin')
                <th>Acciones</th>
            @endif
        </tr>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                @if(auth()->user()->role === 'admin')
                    <td>
                        <!-- Botón para editar el rol -->
                        <a href="{{ route('users.edit-role', $user->id) }}" class="btn btn-warning">Editar Rol</a>
                    </td>
                @endif
                <td>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Editar</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                    <a href="{{ route('users.assign-products', $user->id) }}" class="btn btn-info">Asignar Productos</a>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
