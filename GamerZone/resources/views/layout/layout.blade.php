<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamer Zone</title>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <!-- Box Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--JQuery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
    @php
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
            $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
        }
    @endphp
<body>
    <!-- Header -->
    <header>
        <!-- Navbar -->
        <div class="nav container">
            <div class="dropdown" id="user-dropdown">
                <!-- Contenido del dropdown -->
                <div class="dropdown-content" id="user-dropdown-content">
                    @if($usuario)
                        <a href="/logout">Cerrar Sesión</a>
                        
                    @else
                        <a href="/login">Iniciar Sesión</a>
                    @endif
                </div>
                <a href="/#" class="logo">GameZone</a>
                <i class='bx bx-user' id="user-icon">
                @if($usuario)
                    <span class="nav-link">{{{ $usuario }}}</span>
                    <a href="/carrito" class="texto"><i class='bx bx-shopping-bag' id="cart-icon">  Carrito</i></a>
                @else

                @endif
                </i>
            </div>
        </div>
    </header>

    @yield('content')

      <!-- JS -->
      <script src="{{ asset('js/main.js') }}"></script>
        <noscript>
            <meta http-equiv="refresh" content="0; url=/pagerror">
        </noscript>
</body>
</html>