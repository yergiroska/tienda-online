<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 🔹 Mostrar todos los usuarios
    /**
     * Mostrar una lista de los usuarios.
     *
     * Este método recupera todos los usuarios excepto el usuario autenticado actualmente
     * y los pasa a la vista 'users.index'.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
    
        return view('users.index', compact('users'));
    }

    /**
     * Mostrar el formulario para crear un nuevo usuario.
     *
     * @return \Illuminate\View\View
     */
    // 🔹 Mostrar el formulario de creación
    public function create()
    {
        return view('users.create');
    }

    /**
     * Guardar un nuevo usuario en la base de datos.
     *
     * Este método valida los datos de la solicitud entrante, crea un nuevo usuario con la
     * información proporcionada y redirige a la página de índice de usuarios con un mensaje de éxito.
     *
     * @param \Illuminate\Http\Request $request La instancia de solicitud entrante que contiene los datos del usuario.
     * @return \Illuminate\Http\RedirectResponse Una respuesta de redirección a la página de índice de usuarios con un mensaje de éxito.
     */
    // 🔹 Guardar un nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar el formulario para editar el usuario especificado.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    // 🔹 Mostrar el formulario de edición
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Actualizar un usuario.
     *
     * Este método permite actualizar la información de un usuario. Solo el usuario autenticado
     * puede editar su propio perfil, a menos que sea un administrador.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos del usuario.
     * @param \App\Models\User $user El usuario que se va a actualizar.
     * @return \Illuminate\Http\RedirectResponse Redirige a la página correspondiente con un mensaje de éxito o error.
     *
     * Validaciones:
     * - 'name' es requerido, debe ser una cadena de texto y no puede exceder los 255 caracteres.
     * - 'email' es requerido, debe ser una cadena de texto, un email válido, no puede exceder los 255 caracteres y debe ser único en la tabla de usuarios, excluyendo el email del usuario actual.
     * - 'profile_image' es opcional, debe ser una imagen en formato jpg, jpeg o png y no puede exceder los 2048 KB.
     *
     * Comportamiento:
     * - Si el usuario autenticado no tiene permisos para editar el perfil, redirige a la página de perfil con un mensaje de error.
     * - Si se proporciona una nueva imagen de perfil, elimina la imagen anterior y guarda la nueva.
     * - Actualiza los datos del usuario en la base de datos.
     * - Redirige a la página de lista de usuarios si el usuario autenticado es un administrador, de lo contrario, redirige a la página de perfil.
     */
    // 🔹 Actualizar un usuario
    public function update(Request $request, User $user)
    {
        // Verificar que el usuario autenticado solo pueda editar su propio perfil o si es admin
        if (auth()->user()->id !== $user->id && auth()->user()->role !== 'admin') {
            return redirect()->route('profile')->with('error', 'No tienes permisos para editar este perfil.');
        }

        // Validar los datos ingresados
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Manejo de la imagen de perfil
        if ($request->hasFile('profile_image')) 
        {
            // Eliminar la imagen anterior si existe
            if ($user->profile_image) {
                Storage::delete('public/profiles/' . $user->profile_image);
            }

            // Guardar la nueva imagen
            $imagePath = $request->file('profile_image')->store('public/profiles');
            $imageName = basename($imagePath);
            $user->profile_image = $imageName;
        }

        // Actualizar el usuario
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'profile_image' => $user->profile_image,
        ]);

        // Redirigir según el rol del usuario
        if (auth()->user()->role === 'admin') {
            return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
        }

        return redirect()->route('profile')->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Eliminar un usuario.
     *
     * Este método elimina un usuario específico de la base de datos.
     *
     * @param  \App\Models\User  $user  El usuario que se va a eliminar.
     * @return \Illuminate\Http\RedirectResponse  Redirección a la lista de usuarios con un mensaje de éxito.
     */
    // 🔹 Eliminar un usuario
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }

    /**
     * Mostrar formulario para asignar productos a un usuario.
     *
     * Este método obtiene todos los productos disponibles que no están asignados
     * al usuario especificado y retorna una vista con la lista de productos y el usuario.
     *
     * @param \App\Models\User $user El usuario al que se le asignarán los productos.
     * @return \Illuminate\View\View La vista con la lista de productos y el usuario.
     */
    // 🔹 Mostrar formulario para asignar productos a un usuario
    public function assignProductsForm(User $user)
    {
        // Obtener todos los productos disponibles
        $assignedProducts = $user->products()->pluck('products.id'); // Solo obtenemos los IDs asignados

        // Obtener todos los productos que NO están asignados al usuario
        $products = Product::whereNotIn('id', $assignedProducts)->get();

        // Retornar la vista con la lista de productos y el usuario
        return view('users.assign-products', compact('user', 'products', 'assignedProducts'));
    }

    /**
     * Asigna un producto al usuario.
     *
     * Este método valida que el producto seleccionado exista y luego lo asigna al usuario,
     * evitando duplicados. El producto asignado inicialmente no está aceptado.
     *
     * @param User $user El usuario al que se le asignará el producto.
     * @param Request $request La solicitud HTTP que contiene el ID del producto a asignar.
     * @return \Illuminate\Http\RedirectResponse Redirección a la ruta de asignación de productos con un mensaje de éxito.
     */
    // 🔹 Asignar un producto al usuario
    public function attachProduct(User $user, Request $request)
    {
        // Validar que el producto seleccionado exista
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        // Asignar el producto al usuario (evita duplicados)
        $user->products()->attach($request->product_id, ['accepted' => false]);

        return redirect()->route('users.assign-products', $user->id)->with('success', 'Producto asignado correctamente. Ahora debe ser aceptado.');
    }

    /**
     * Desasignar un producto del usuario.
     *
     * Este método elimina la relación entre un usuario y un producto específico.
     *
     * @param \App\Models\User $user El usuario del cual se desasignará el producto.
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene el ID del producto a desasignar.
     * 
     * @return \Illuminate\Http\RedirectResponse Redirige a la ruta de asignación de productos del usuario con un mensaje de éxito.
     * 
     * @throws \Illuminate\Validation\ValidationException Si el ID del producto no es proporcionado o no existe en la base de datos.
     */
    // 🔹 Desasignar un producto del usuario
    public function detachProduct(User $user, Request $request)
    {
        // Validar que el producto seleccionado exista
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        // Eliminar la relación del producto con el usuario
        $user->products()->detach($request->product_id);

        return redirect()->route('users.assign-products', $user->id)->with('success', 'Producto eliminado correctamente.');
    }


    /**
     * Editar roles de usuario.
     *
     * Solo el admin puede acceder a esta función.
     * Redirige a la lista de usuarios con un mensaje de error si el usuario autenticado no es admin.
     *
     * @param  \App\Models\User  $user  El usuario cuyo rol se va a editar.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    //Editar roles de usuario
    public function editRole(User $user)
    {
        // Solo el admin puede acceder a esta función
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('users.index')->with('error', 'No tienes permisos para editar roles.');
        }

        return view('users.edit-role', compact('user'));
    }

    /**
     * Accept a product for a user.
     *
     * This method verifies if the user has the specified product assigned.
     * If the product is not assigned, it redirects back with an error message.
     * If the product is assigned, it updates the pivot table to mark the product as accepted.
     *
     * @param User $user The user who is accepting the product.
     * @param Product $product The product to be accepted.
     * @return \Illuminate\Http\RedirectResponse Redirects to the assign-products route with a success or error message.
     */
    public function acceptProduct(User $user, Product $product)
    {
        // Verificar si el usuario tiene el producto asignado
        if (!$user->products()->where('product_id', $product->id)->exists()) {
            return redirect()->route('users.assign-products', $user->id)->with('error', 'El producto no está asignado.');
        }

        // Actualizar el estado a "Aceptado"
        $user->products()->updateExistingPivot($product->id, ['accepted' => true]);

        return redirect()->route('users.assign-products', $user->id)->with('success', 'Producto aceptado con éxito.');
    }


    /**
     * Update the role of a specified user.
     *
     * This method ensures that only administrators can change user roles.
     * It validates the new role and updates the user's role if the user exists.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing the new role.
     * @param \App\Models\User $user The user whose role is to be updated.
     * @return \Illuminate\Http\RedirectResponse A redirect response to the users index route with a success or error message.
     */
    public function updateRole(Request $request, User $user)
    {
        // Asegurar que solo los administradores pueden cambiar roles
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('users.index')->with('error', 'No tienes permisos para cambiar roles.');
        }

        //dd($request->all());

        // Validamos el nuevo rol
        $request->validate([
            'role' => 'required|in:user,admin', // Solo permitimos estos roles
        ]);

        // Verificar si el usuario existe antes de actualizarlo
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Usuario no encontrado.');
        }

        // Actualizamos el rol del usuario
        $user->role = $request->role;
        $user->save();

        return redirect()->route('users.index')->with('success', 'Rol actualizado correctamente.');
    }
}
