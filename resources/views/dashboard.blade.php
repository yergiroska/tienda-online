@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Bienvenido, {{ $user->name }}</h1>

    <!-- Selección de productos NO asignados -->
    <h2>Asignar un Nuevo Producto</h2>
    @include('users.inc.products_table', ['products' => $user->products])
@endsection
