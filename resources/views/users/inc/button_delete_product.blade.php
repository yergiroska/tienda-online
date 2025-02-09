<form action="{{ route('users.detach-product', $user->id) }}" method="POST" style="display:inline;">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <button type="submit" class="btn btn-danger">Eliminar</button>
</form>