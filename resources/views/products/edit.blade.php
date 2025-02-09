@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
    <h1>Editar Producto</h1>
    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ $product->name }}" required class="form-control">
        <textarea name="description" class="form-control">{{ $product->description }}</textarea>
        <input type="number" name="price" value="{{ $product->price }}" step="0.01" required class="form-control">
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
@endsection
