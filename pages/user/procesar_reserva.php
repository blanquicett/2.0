<?php
session_start();
include ("../../conexion.php");

// Verificar si hay datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pasajeros'])) {
    // Guardar los datos de los pasajeros en la sesión
    $_SESSION['datos_reserva'] = [
        'idVuelo' => $_POST['idVuelo'],
        'pasajeros' => $_POST['pasajeros']
    ];
}

// Verificar si tenemos los datos necesarios en la sesión
if (!isset($_SESSION['datos_reserva'])) {
    header('Location: reserva.php');
    exit;
}

// Obtener información del vuelo y capacidad del avión
$sql = "SELECT d.*, av.nombreAvion, a.nombreAerolinea, ma.capacidad, ma.modelo
        FROM disponibilidad d
        INNER JOIN aviones av ON d.idAvion = av.idAvion
        INNER JOIN aerolinea a ON av.idAerolinea = a.idAerolinea
        INNER JOIN modeloaviones ma ON av.idModeloA = ma.idModeloA
        WHERE d.idDisponibilidad = ?";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['datos_reserva']['idVuelo']);
mysqli_stmt_execute($stmt);
$vuelo = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Obtener asientos ocupados
$sql = "SELECT asiento FROM tiquetes WHERE idVuelo = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['datos_reserva']['idVuelo']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$asientosOcupados = [];
while ($row = mysqli_fetch_assoc($result)) {
    $asientosOcupados[] = $row['asiento'];
}

// Contar pasajeros (excluyendo infantes)
$cantidadPasajeros = count(array_filter($_SESSION['datos_reserva']['pasajeros'], function($p) {
    return !isset($p['infante']);
}));

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selección de Asientos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .asientos-container {
            display: grid;
            gap: 10px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            margin: 20px 0;
        }

        .asiento {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: white;
        }

        .asiento:hover:not(.ocupado):not(.seleccionado) {
            background-color: #e9ecef;
            transform: scale(1.05);
        }

        .asiento.ocupado {
            background-color: #dc3545;
            color: white;
            cursor: not-allowed;
        }

        .asiento.seleccionado {
            background-color: #198754;
            color: white;
            border-color: #198754;
        }

        .pasillo {
            width: 30px;
        }

        .leyenda-item {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }

        .leyenda-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            margin-right: 8px;
        }

        .flight-info {
            background: linear-gradient(135deg, #0d6efd0d 0%, #0d6efd1a 100%);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background: #dee2e6;
            z-index: 1;
        }

        .step {
            position: relative;
            z-index: 2;
            background: white;
            padding: 0 10px;
        }

        .step.active {
            color: #0d6efd;
            font-weight: bold;
        }

        .step.completed {
            color: #198754;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <!-- Pasos del proceso -->
        <div class="progress-steps">
            <div class="step completed">1. Búsqueda</div>
            <div class="step completed">2. Datos de Pasajeros</div>
            <div class="step active">3. Selección de Asientos</div>
            <div class="step">4. Pago</div>
            <div class="step">5. Confirmación</div>
        </div>

        <!-- Información del vuelo -->
        <div class="flight-info">
            <h4 class="card-title"><?= htmlspecialchars($vuelo['nombreAerolinea']) ?> — <?= htmlspecialchars($vuelo['nombreAvion']) ?></h4>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Origen:</strong> <?= htmlspecialchars($vuelo['origen']) ?></p>
                    <p><strong>Destino:</strong> <?= htmlspecialchars($vuelo['destino']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Fecha:</strong> <?= htmlspecialchars($vuelo['fecha']) ?></p>
                    <p><strong>Horario:</strong> <?= htmlspecialchars($vuelo['horaSalida']) ?> - <?= htmlspecialchars($vuelo['horaLlegada']) ?></p>
                </div>
            </div>
        </div>

        <h2 class="mb-4">Selección de Asientos</h2>
        <p>Por favor, seleccione <?= $cantidadPasajeros ?> asiento(s) para los pasajeros.</p>

        <!-- Leyenda de asientos -->
        <div class="d-flex mb-4">
            <div class="leyenda-item">
                <div class="leyenda-color" style="background-color: white; border: 2px solid #dee2e6"></div>
                <span>Disponible</span>
            </div>
            <div class="leyenda-item">
                <div class="leyenda-color" style="background-color: #198754"></div>
                <span>Seleccionado</span>
            </div>
            <div class="leyenda-item">
                <div class="leyenda-color" style="background-color: #dc3545"></div>
                <span>Ocupado</span>
            </div>
        </div>

        <form action="confirmar_reserva.php" method="POST" id="asientosForm">
            <input type="hidden" name="asientosSeleccionados" id="asientosSeleccionados">
            
            <!-- Contenedor de asientos -->
            <div class="asientos-container" id="asientosContainer">
                <!-- Los asientos se generarán dinámicamente con JavaScript -->
            </div>

            <!-- Lista de pasajeros y sus asientos -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Asientos Seleccionados</h5>
                    <div id="asignacionAsientos">
                        <?php foreach ($_SESSION['datos_reserva']['pasajeros'] as $index => $pasajero): ?>
                            <?php if (!isset($pasajero['infante'])): ?>
                            <div class="mb-2">
                                <span>Pasajero <?= $index + 1 ?>: <?= htmlspecialchars($pasajero['nombres']) ?> <?= htmlspecialchars($pasajero['primerApellido']) ?></span>
                                <span class="asiento-asignado text-primary">- Pendiente de asignar</span>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="reserva.php" class="btn btn-outline-secondary">Volver</a>
                <button type="submit" class="btn btn-primary" id="btnContinuar" disabled>
                    Continuar al Pago
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuración del mapa de asientos
        const capacidad = <?= $vuelo['capacidad'] ?>;
        const asientosOcupados = <?= json_encode($asientosOcupados) ?>;
        const cantidadPasajeros = <?= $cantidadPasajeros ?>;
        let asientosSeleccionados = [];

        // Configuración del layout del avión
        const filas = Math.ceil(capacidad / 6); // 6 asientos por fila (3-3)
        const container = document.getElementById('asientosContainer');
        
        // Establecer el grid basado en el número de columnas (6 asientos + 1 pasillo)
        container.style.gridTemplateColumns = 'repeat(7, 1fr)';

        // Generar asientos
        let asientoActual = 1;
        for (let fila = 0; fila < filas; fila++) {
            // Letras para las filas
            const letraFila = String.fromCharCode(65 + fila);
            
            for (let col = 0; col < 7; col++) {
                if (col === 3) {
                    // Crear pasillo
                    const pasillo = document.createElement('div');
                    pasillo.className = 'pasillo';
                    container.appendChild(pasillo);
                    continue;
                }

                if (asientoActual <= capacidad) {
                    const asiento = document.createElement('div');
                    const numeroAsiento = `${letraFila}${col < 3 ? col + 1 : col}`;
                    asiento.className = 'asiento';
                    asiento.textContent = numeroAsiento;
                    
                    // Verificar si el asiento está ocupado
                    if (asientosOcupados.includes(numeroAsiento)) {
                        asiento.className += ' ocupado';
                    } else {
                        asiento.addEventListener('click', () => seleccionarAsiento(asiento, numeroAsiento));
                    }
                    
                    container.appendChild(asiento);
                    asientoActual++;
                }
            }
        }

        function seleccionarAsiento(elemento, numeroAsiento) {
            if (elemento.classList.contains('ocupado')) return;
            
            if (elemento.classList.contains('seleccionado')) {
                // Deseleccionar asiento
                elemento.classList.remove('seleccionado');
                asientosSeleccionados = asientosSeleccionados.filter(a => a !== numeroAsiento);
            } else if (asientosSeleccionados.length < cantidadPasajeros) {
                // Seleccionar asiento
                elemento.classList.add('seleccionado');
                asientosSeleccionados.push(numeroAsiento);
            } else {
                alert('Ya ha seleccionado el máximo de asientos permitidos');
                return;
            }

            // Actualizar asignación de asientos
            actualizarAsignacionAsientos();
            
            // Actualizar estado del botón continuar
            document.getElementById('btnContinuar').disabled = asientosSeleccionados.length !== cantidadPasajeros;
            
            // Actualizar campo oculto con asientos seleccionados
            document.getElementById('asientosSeleccionados').value = JSON.stringify(asientosSeleccionados);
        }

        function actualizarAsignacionAsientos() {
            const asignaciones = document.querySelectorAll('.asiento-asignado');
            asignaciones.forEach((elem, index) => {
                if (asientosSeleccionados[index]) {
                    elem.textContent = ` - Asiento ${asientosSeleccionados[index]}`;
                    elem.classList.remove('text-primary');
                    elem.classList.add('text-success');
                } else {
                    elem.textContent = ' - Pendiente de asignar';
                    elem.classList.remove('text-success');
                    elem.classList.add('text-primary');
                }
            });
        }

        // Prevenir envío del formulario si no se han seleccionado todos los asientos
        document.getElementById('asientosForm').addEventListener('submit', function(e) {
            if (asientosSeleccionados.length !== cantidadPasajeros) {
                e.preventDefault();
                alert('Por favor, seleccione todos los asientos necesarios antes de continuar');
            }
        });
    </script>
</body>
</html>
