<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <!-- CSS -->
    <link rel="stylesheet" href="/css/signin.css">
</head>
<body>
<div class="contenedor">
    <h1>Registro de Usuario</h1>
    <form method="POST" action="{{ route('signin.submit') }}">
        @csrf

        <div>
            <input type="text" name="email" id="email" placeholder="Email" required value="{{ old('email') }}">
            @error('email')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <input type="text" name="usuario" id="usuario" placeholder="Usuario" required value="{{ old('usuario') }}">
            @error('usuario')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <input type="password" name="contraseña" id="contraseña" placeholder="Contraseña" required>
            @error('contraseña')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit">Crear cuenta</button>
    </form>
</div>
</body>
</html>
