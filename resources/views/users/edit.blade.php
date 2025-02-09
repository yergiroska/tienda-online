@extends('layouts.app')

@section('content')
    <h1>Editar Usuario</h1>

    @if($user->profile_image)
        <img src="{{ asset('storage/profiles/' . $user->profile_image) }}" alt="Imagen de perfil" class="img-thumbnail mb-3" width="150">
    @else
        <p>No tienes una imagen de perfil.</p>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <label for="name">Nombre:</label>
        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>

        <label for="email">Email:</label>
        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>

        <label for="profile_image">Imagen de Perfil:</label>
        <input type="file" name="profile_image" class="form-control">

        <button type="submit" class="btn btn-success mt-3">Actualizar Perfil</button>
    </form>
    <a href="{{ route('profile') }}" class="btn btn-secondary mt-3">Cancelar</a>
@endsection
