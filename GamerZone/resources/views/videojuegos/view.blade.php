@extends('layout.layout')

@section('content')
    <div class="container">
        @if(isset($videojuego))
            <div class="game-details">
                <div class="image-container">
                    <img src="{{ asset('img/juegos/' . $videojuego->portada) }}" alt="Portada del videojuego" class="game-image" style="height: 517px;">
                </div>
                <div class="details-container">
                    <h1>{{ $videojuego->nombre }}</h1>
                    <p class="price">Precio: {{ $videojuego->precio }} €</p>
                    <br>
                    <p class="description">{{ $videojuego->descripcion }}</p>
                    <br>
                    <button onclick="addProductToCart({{ $videojuego->id }})"  id="comprar-btn" class="comprar-btn">Comprar</button>
                </div>
            </div>
        @else
            <p>Error: El videojuego no está disponible.</p>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/main.js') }}"></script>
@endsection