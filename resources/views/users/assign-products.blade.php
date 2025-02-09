@extends('layouts.app')

@section('title', 'Asignar Productos')

@section('content')
    <h1>Asignar Productos a {{ $user->name }}</h1>

    <!-- Mensajes de éxito -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @include('users.inc.products_table', ['products' => $user->products])

    <!--<a href="{{ route('users.index') }}" class="btn btn-secondary">Volver a Usuarios</a>-->
@endsection
