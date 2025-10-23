<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <title>Iniciar Sesión</title>
</head>
<body>
  <div class="contenedor-principal">
    <h1 class="p-3 text-center">INGRESA TUS CREDENCIALES</h1>
    <form class="mx-auto" style="width: 400px;" action="../src/ingreso.php" method="POST">
      <div class="col-12">
        <label class="form-label">Correo electrónico</label>
        <input type="email" class="form-control mb-3" name="email" required>
        <label class="form-label">Contraseña</label>
        <input type="password" class="form-control mb-3" name="password" required>
        <div class="text-center mb-3">
          <a href="#">¿Olvidaste tu contraseña?</a>
        </div>
        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
      </div>
    </form>
    <h5 class="text-center mt-3">¿No estás registrado? <a href="./register.php">Crear cuenta</a></h5>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
