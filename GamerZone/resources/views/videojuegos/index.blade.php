@extends('layout.layout')
@section('content')
<!-- Tienda -->
<section class="shop container">
    <h2 class="section-title">Tienda</h2>
    <!-- Buscador -->
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Buscar por nombre...">
        <button onclick="filterProducts()">Buscar</button>
    </div>
    <div class="shop-content" id="shopContent">
</div>
</section>
@endsection
@section('scripts')
<script src="{{ asset('js/main.js') }}"></script>
@endsection