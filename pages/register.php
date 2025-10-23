<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear cuenta - Agencia de viajes</title>
  <link rel="stylesheet" href="../css/register.css">
</head>
<body>

  <div class="contenedor">
    
    <!-- FORMULARIO COMPLETO -->
    <form action="../src/insert.php" method="POST" class="formulario">
      <h2>Crear una cuenta</h2>

      <input type="text" name="nombres" placeholder="Nombres" required>
      <input type="text" name="primerApellido" placeholder="Primer Apellido" required>
      <input type="text" name="segundoApellido" placeholder="Segundo Apellido">
      <input type="date" name="fechNacimiento" required>

      <select name="genero" required>
        <option value="">Género</option>
        <option value="Masculino">Masculino</option>
        <option value="Femenino">Femenino</option>
      </select>

      <select name="tipoDocumento" required>
        <option value="">Tipo de Documento</option>
        <option value="CC">Cédula</option>
        <option value="TI">Tarjeta de Identidad</option>
      </select>

      <input type="text" name="documento" placeholder="Documento" required>
      <input type="tel" name="celular" placeholder="Celular" required>
      <input type="email" name="email" placeholder="Correo" required>
      <input type="password" name="password" placeholder="Contraseña" required>

      <!-- Botón dentro del formulario -->
      <div class="derecha">
        <button type="submit" class="btn-crear">Crear Cuenta</button>
        <p class="texto-condiciones">
          Debes aceptar nuestros <a href="#">términos y condiciones</a>
        </p>
        <p class="login-link">
          ¿Ya tienes una cuenta registrada? 
          <a href="./login.php" style="color: var(--color-primario); font-weight: bold;">Iniciar sesión</a>
        </p>
      </div>
    </form>

  </div>

</body>
</html>
