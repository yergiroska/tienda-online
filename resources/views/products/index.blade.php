@extends('layouts.app')

@section('title', 'Lista de Productos')

@section('content')
    <h1>Lista de Productos</h1>
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('products.create') }}" class="btn btn-primary">Registrar Producto</a>
    @endif
    <!--<a href="{{ route('products.create') }}" class="btn btn-primary">Crear Producto</a>-->
    <table class="table table-striped">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            @if(auth()->user()->role === 'admin') <!-- Solo admins pueden editar/eliminar -->
                <th>Acciones</th>
            @endif
        </tr>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>€{{ number_format($product->price, 2) }}</td>
                @if(auth()->user()->role === 'admin') <!-- Solo admins pueden ver botones -->
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                @endif
            </tr>
        @endforeach
    </table>
@endsection
