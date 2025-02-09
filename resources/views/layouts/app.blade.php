<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <!--<a class="navbar-brand" href="{{ url('/') }}">Mi Aplicación</a>-->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        <!-- 🔹 Enlace solo para administradores -->
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Usuarios</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Productos</a></li>
                        @endif

                        <!-- 🔹 Enlace solo para usuarios normales -->
         
                            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Mis Productos</a></li>
                            <!--<li class="nav-item"><a class="nav-link" href="{{ route('profile') }}">Perfil</a></li>-->
           
                    @endauth
                </ul>

                <ul class="navbar-nav">
                    @auth
                        <!-- 🔹 Mostrar nombre de usuario -->
                        <li class="nav-item">
                            @if(auth()->user()->profile_image)
                                <img src="{{ asset('storage/profiles/' . auth()->user()->profile_image) }}" 
                                    alt="Imagen de perfil" 
                                    class="rounded-circle me-2" 
                                    width="40" height="40">
                            @else
                                <!-- Imagen por defecto si no tiene imagen de perfil -->
                                <img src="{{ asset('storage/profiles/default.png') }}" 
                                    alt=" " 
                                    class="rounded-circle me-2" 
                                    width="40" height="40">
                            @endif



                            <a class="nav-link text-primary fw-bold" href="{{ route('profile') }}">
                                {{ auth()->user()->name }}
                            </a>
                        </li>

                        <!-- 🔹 Cerrar sesión -->
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
                            </form>
                        </li>
                    @else
                        <!-- 🔹 Enlaces para invitados -->
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Registrarse</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>    

    <div class="container mt-4">
        @yield('content')
    </div>

    <footer class="text-center mt-5">
        <p>© {{ date('Y') }} Mi Aplicación</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
