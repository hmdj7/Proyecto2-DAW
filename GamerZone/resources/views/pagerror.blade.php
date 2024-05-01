<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Redirección por desactivación de JavaScript</title>
</head>
<body>
  <div style="text-align: center; padding: 20px;">
      <h1>¡Error!</h1>
      <p>JavaScript está desactivado en tu navegador. Por favor, actívalo para continuar.</p>
  </div>
<script>
// Redirigir si JavaScript está activo
  if (document.querySelector) {
    window.location.href = '/'; 
  }
</script>
</body>
</html>