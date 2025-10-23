<?php
session_start();
include("../../conexion.php");

// Verificar que lleguen asientos seleccionados por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['asientosSeleccionados'])) {
    header('Location: procesar_reserva.php');
    exit;
}

$asientosSeleccionados = json_decode($_POST['asientosSeleccionados'], true);
if (!is_array($asientosSeleccionados) || count($asientosSeleccionados) === 0) {
    header('Location: procesar_reserva.php');
    exit;
}

// Obtener datos de la reserva desde la sesión
if (!isset($_SESSION['datos_reserva'])) {
    header('Location: reserva.php');
    exit;
}

$datosReserva = $_SESSION['datos_reserva'];
$idVuelo = $datosReserva['idVuelo'];
$pasajeros = $datosReserva['pasajeros'];

// Validar que la cantidad de asientos coincida con la cantidad de pasajeros sin infante
$cantidadPasajeros = count(array_filter($pasajeros, function($p) { return !isset($p['infante']); }));
if (count($asientosSeleccionados) !== $cantidadPasajeros) {
    die('La cantidad de asientos seleccionados no coincide con la cantidad de pasajeros.');
}

// Mostrar resumen y formulario de pago (simulado)
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Confirmar Reserva / Pago</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h1>Resumen de Reserva</h1>
    <div class="card mb-3">
        <div class="card-body">
            <h5>Vuelo: </h5>
            <p>ID Vuelo: <?= htmlspecialchars($idVuelo) ?></p>
            <p>Asientos seleccionados: <?= htmlspecialchars(implode(', ', $asientosSeleccionados)) ?></p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Pasajeros</h5>
            <ul>
            <?php foreach ($pasajeros as $idx => $p): ?>
                <li>
                    <?= htmlspecialchars($p['nombres'] . ' ' . ($p['primerApellido'] ?? '') . ' ' . ($p['segundoApellido'] ?? '')) ?>
                    <?php if (isset($p['infante'])): ?>
                        <br><small>Infante: <?= htmlspecialchars($p['infante']['nombres'] ?? '') ?></small>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <h2>Pago (simulado)</h2>
    <form action="finalizar_reserva.php" method="POST">
        <input type="hidden" name="asientosSeleccionados" value='<?= htmlspecialchars(json_encode($asientosSeleccionados)) ?>'>
        <div class="mb-3">
            <label class="form-label">Nombre del Titular</label>
            <input type="text" name="titular" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Número de Tarjeta</label>
            <input type="text" name="tarjeta" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha de Expiración</label>
            <input type="text" name="exp" class="form-control" placeholder="MM/AA" required>
        </div>
        <div class="mb-3">
            <label class="form-label">CVV</label>
            <input type="text" name="cvv" class="form-control" required>
        </div>
        <div class="d-flex justify-content-between">
            <a href="procesar_reserva.php" class="btn btn-outline-secondary">Volver</a>
            <button type="submit" class="btn btn-success">Pagar y Finalizar</button>
        </div>
    </form>
</div>
</body>
</html>