<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        // Obtener el usuario autenticado con sus productos asignados
        $user = Auth::user();
        // Obtener productos asignados al usuario
        $assignedProducts = $user->products;

        // Obtener productos que NO están asignados al usuario
        $assignedProductIds = $assignedProducts->pluck('id'); // Obtener IDs de los asignados
        $unassignedProducts = Product::whereNotIn('id', $assignedProductIds)->get();

    return view('dashboard', compact('user', 'assignedProducts', 'unassignedProducts'));
    }

    public function profile()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        return view('profile', compact('user'));
    }
}
