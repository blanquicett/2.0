<?php
  include('../../conexion.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>bienvenido admin</h1>

    <nav class="navbar navbar-expand-lg header">
        <div class="container">
            <a class="navbar-brand" href="#">SENAPORC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav ms-auto align-items-center">
                    <?php if (!empty($_SESSION['user_email'])): ?>
                        <li class="nav-item me-3"><span class="small-muted">Hola, <?= htmlspecialchars($_SESSION['user_email']) ?></span></li>
                        <li class="nav-item"><a class="btn btn-outline-secondary" href="logout.php">Cerrar sesión</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="pages/login.php">Iniciar sesión</a></li>
                        <li class="nav-item"><a class="nav-link" href="pages/register.php">Crear cuenta</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Nosotros</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
</body>
</html>