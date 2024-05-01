<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de compras</title>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/carrito.css') }}">
</head>
<body>

<div class="contenedor">
    <h1>Tu cesta</h1>
    @php
    $total = 0;
    @endphp
    @if(count($carrito))
        @foreach ($carrito as $producto)
            @php 
                $total += $producto['precio'] * $producto['cantidad'];
            @endphp
            <div class="producto">
                <img src="{{ asset('img/juegos/' . $producto['portada']) }}" alt="{{ $producto['nombre'] }}">
                <div class="info">
                    <h3>{{ $producto['nombre'] }}</h3>
                    <p>Precio: {{ $producto['precio'] }}€</p>
                    <p>Cantidad: {{ $producto['cantidad'] }}</p>
                    <!-- Formulario para eliminar el producto del carrito -->
                    <form action="/eliminarDelCarrito" method="POST">
                        @csrf
                        <input type="hidden" name="producto_id" value="{{ $producto['id'] }}">
                        <button type="submit">Eliminar</button>
                    </form>
                </div>
            </div>
        @endforeach
    @else
        <p>No hay productos en el carrito</p>
    @endif
    <!-- Total -->
    <div class="total">
        Total: ${{ $total }}
        @if(count($carrito))
            <form action="/realizarPedido" method="POST">
            @csrf
            <input type="hidden" name="total" value="{{ $total }}">
            <button type="submit">Realizar Pedido</button>
        </form>
        @endif
    </div>
        <button type="submit" onclick="window.location='/videojuegos'">Volver al catálogo</button>
</div>
</body>
</html>