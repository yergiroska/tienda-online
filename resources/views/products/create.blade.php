@extends('layouts.app')

@section('title', 'Crear Producto')

@section('content')
    <h1>Crear Producto</h1>
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Nombre" required class="form-control">
        <textarea name="description" placeholder="Descripción" class="form-control"></textarea>
        <input type="number" name="price" placeholder="Precio" step="0.01" required class="form-control">
        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
@endsection
