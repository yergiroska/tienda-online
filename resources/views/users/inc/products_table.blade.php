<!-- Formulario para asignar productos -->
    <form action="{{ route('users.attach-product', $user->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="product_id" class="form-label">Seleccionar Producto</label>
            <select name="product_id" class="form-control" required>
                <option value="">-- Seleccionar Producto --</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} - €{{ number_format($product->price, 2) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Asignar Producto</button>
    </form>

    <hr>

    <h2>Productos Asignados</h2>

    <!-- Listado de productos asignados -->
    @if ($user->products->isEmpty())
        <p>Este usuario no tiene productos asignados.</p>
    @else
        <table class="table table-bordered">
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
            @foreach ($user->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>€{{ number_format($product->price, 2) }}</td>
                    <td>
                        @if($product->pivot->accepted)
                            ✅ Aceptado
                        @else
                            ⏳ Pendiente
                        @endif
                    </td>
                    <td>
                        {{-- 
                            Esta sección de la plantilla Blade maneja la visualización y acciones para asignar productos a usuarios.
                            
                            - Si el producto está aceptado:
                                - Si el usuario autenticado es un administrador, muestra un formulario para desvincular (eliminar) el producto del usuario.
                                - De lo contrario, muestra un mensaje indicando que el producto está aceptado.
                            
                            - Si el producto no está aceptado:
                                - Si el usuario autenticado es un administrador, muestra un formulario para aceptar el producto.
                                - Muestra un formulario para desvincular (eliminar) el producto del usuario.
                            
                            Formularios:
                            - Formulario para desvincular producto:
                                - Acción: Ruta a 'users.detach-product' con el ID del usuario.
                                - Método: POST
                                - Input oculto: ID del producto
                                - Botón: "Eliminar" con la clase 'btn btn-danger'.
                            
                            - Formulario para aceptar producto (solo para administradores):
                                - Acción: Ruta a 'users.accept-product' con el ID del usuario y el ID del producto.
                                - Método: POST
                                - Botón: "Aceptar" con la clase 'btn btn-success'.
                        --}}
                        @if($product->pivot->accepted)
                            @if(auth()->user()->role === 'admin')
                                @include('users.inc.button_delete_product')
                            @else
                            <p>Producto aceptado</p>
                            @endif
                        @else  
                            @if(auth()->user()->role === 'admin')
                                <!-- Botón para aceptar producto -->
                                <form action="{{ route('users.accept-product', [$user->id, $product->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Aceptar</button>
                                </form>
                            @endif
                            @include('users.inc.button_delete_product')
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    @endif