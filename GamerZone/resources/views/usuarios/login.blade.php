<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <!-- CSS -->
    <link rel="stylesheet" href="/css/login.css">
</head>
<body>
<div class="contenedor">
    <h1>Iniciar Sesión</h1>
    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        @error('error')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <div>
            <input type="text" name="usuario" id="usuario" placeholder="Usuario" required value="{{ old('usuario') }}">
            @error('usuario')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <input type="password" name="contraseña" id="contraseña" placeholder="Contraseña" required>
            @error('contraseña')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Iniciar sesión</button>
    </form>
    <div>
        <a>¿No tienes cuenta? Únete a nosotros</a>
        <button type="submit" onclick="window.location='/signin'">Crear cuenta</button>
    </div>
</div>

</body>
</html>
