<?php

include ("../../conexion.php");

$codigoReserva = isset($_GET['codigo']) ? mysqli_real_escape_string($conexion, $_GET['codigo']) : '';


// Obtener información del vuelo y capacidad del avión
$sql = "SELECT 
    d.idDisponibilidad,
    d.origen,
    d.destino,
    d.fecha,
    d.horaSalida,
    d.horaLlegada,
    d.asiento as asientoDisponible,
    a.nombreAvion,
    ae.nombreAerolinea,
    ma.capacidad as capacidadTotal,
    t.asiento,
    t.precio,
    t.fechaCompra,
    t.codigoReserva,
    t.totalPagar,
    p.nombres,
    p.primerApellido,
    p.segundoApellido,
    p.email,
    r.iva,
    r.descuento,
    r.subtotal,
    (SELECT COUNT(*) FROM tiquetes WHERE idVuelo = d.idDisponibilidad) as asientosOcupados
FROM tiquetes t
INNER JOIN disponibilidad d ON t.idVuelo = d.idDisponibilidad
INNER JOIN aviones a ON d.idAvion = a.idAvion
INNER JOIN aerolinea ae ON a.idAerolinea = ae.idAerolinea
INNER JOIN modeloaviones ma ON a.idModeloA = ma.idModeloA
INNER JOIN pasajeros p ON t.idPasajero = p.idPasajero
INNER JOIN reservas r ON t.idReserva = r.idReserva
WHERE t.codigoReserva = ?";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $codigoReserva);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$tiquetes = [];
$infoGeneral = null;

while ($row = mysqli_fetch_assoc($result)) {
    $tiquetes[] = $row;
    if (!$infoGeneral) {
        $infoGeneral = $row;
    }
}

if (empty($tiquetes)) {
    die('Reserva no encontrada');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva Confirmada - <?= htmlspecialchars($codigoReserva) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .success-icon {
            font-size: 80px;
            color: #198754;
            animation: scaleIn 0.5s ease-out;
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }
        
        .tiquete {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .tiquete::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, #0d6efd, #198754);
        }
        
        .codigo-reserva {
            font-size: 32px;
            font-weight: bold;
            color: #0d6efd;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
        }
        
        .info-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
        }
        
        .asiento-ticket {
            display: inline-block;
            background: #0d6efd;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 20px;
            font-weight: bold;
            margin: 5px;
        }
        
        .boarding-pass {
            border: 2px dashed #dee2e6;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .qr-placeholder {
            width: 150px;
            height: 150px;
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin: 0 auto;
        }
        
        @media print {
            .no-print {
                display: none;
            }
            
            body {
                background: white;
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <!-- Mensaje de éxito -->
        <div class="text-center mb-4">
            <div class="success-icon">✅</div>
            <h1 class="mt-3">¡Reserva Confirmada!</h1>
            <p class="text-muted">Tu reserva ha sido procesada exitosamente</p>
        </div>
        
        <!-- Código de reserva destacado -->
        <div class="text-center mb-4">
            <div class="alert alert-success d-inline-block">
                <h5 class="mb-2">Código de Reserva</h5>
                <div class="codigo-reserva"><?= htmlspecialchars($codigoReserva) ?></div>
                <small class="text-muted">Guarda este código para futuras consultas</small>
            </div>
        </div>
        
        <!-- Detalles del vuelo -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="tiquete">
                    <h3 class="mb-4">Detalles del Vuelo</h3>
                    
                    <!-- Ruta -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="text-center">
                            <h2><?= htmlspecialchars($infoGeneral['origen']) ?></h2>
                            <small class="text-muted">Origen</small>
                        </div>
                        <div class="text-center">
                            <div style="font-size: 40px;">✈️</div>
                        </div>
                        <div class="text-center">
                            <h2><?= htmlspecialchars($infoGeneral['destino']) ?></h2>
                            <small class="text-muted">Destino</small>
                        </div>
                    </div>
                    
                    <!-- Información del vuelo -->
                    <div class="info-section">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>📅 Fecha:</strong><br>
                                <?= date('d/m/Y', strtotime($infoGeneral['fecha'])) ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>🕐 Horario:</strong><br>
                                <?= htmlspecialchars($infoGeneral['horaSalida']) ?> - <?= htmlspecialchars($infoGeneral['horaLlegada']) ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>✈️ Aerolínea:</strong><br>
                                <?= htmlspecialchars($infoGeneral['nombreAerolinea']) ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>🛫 Avión:</strong><br>
                                <?= htmlspecialchars($infoGeneral['nombreAvion']) ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pasajero -->
                    <div class="info-section">
                        <strong>👤 Pasajero:</strong><br>
                        <?= htmlspecialchars($infoGeneral['nombres'] . ' ' . $infoGeneral['primerApellido'] . ' ' . $infoGeneral['segundoApellido']) ?><br>
                        <small class="text-muted">📧 <?= htmlspecialchars($infoGeneral['email']) ?></small>
                    </div>
                    
                    <!-- Información de Asientos -->
                    <div class="info-section">
                        <h4 class="mb-3">💺 Información de Asientos</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h5>Capacidad Total</h5>
                                    <div class="display-4"><?= htmlspecialchars($infoGeneral['capacidadTotal']) ?></div>
                                    <small class="text-muted">asientos</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h5>Asientos Ocupados</h5>
                                    <div class="display-4"><?= htmlspecialchars($infoGeneral['asientosOcupados']) ?></div>
                                    <small class="text-muted">reservados</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h5>Asientos Disponibles</h5>
                                    <div class="display-4"><?= htmlspecialchars($infoGeneral['capacidadTotal'] - $infoGeneral['asientosOcupados']) ?></div>
                                    <small class="text-muted">libres</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Visualización de asientos disponibles -->
                        <div class="mt-4">
                            <h5>Asientos Disponibles:</h5>
                            <div class="asiento-ticket">
                                <?= htmlspecialchars($infoGeneral['asientoDisponible']) ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Desglose de precio -->
                    <div class="info-section">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong>$<?= number_format($infoGeneral['subtotal'], 0, ',', '.') ?></strong>
                        </div>
                        <?php if ($infoGeneral['descuento'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Descuento:</span>
                            <strong>-$<?= number_format($infoGeneral['descuento'], 0, ',', '.') ?></strong>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span>IVA (19%):</span>
                            <strong>$<?= number_format($infoGeneral['iva'], 0, ',', '.') ?></strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total Pagado:</strong>
                            <strong class="text-success" style="font-size: 24px;">
                                $<?= number_format($infoGeneral['totalPagar'], 0, ',', '.') ?>
                            </strong>
                        </div>
                    </div>
                    
                    <!-- Boarding Pass -->
                    <div class="boarding-pass">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5>Pase de Abordar</h5>
                                <p class="mb-1"><strong>Código:</strong> <?= htmlspecialchars($codigoReserva) ?></p>
                                <p class="mb-1"><strong>Fecha de compra:</strong> <?= date('d/m/Y', strtotime($infoGeneral['fechaCompra'])) ?></p>
                                <p class="mb-0"><strong>Asientos:</strong> 
                                    <?php 
                                    $asientos = array_column($tiquetes, 'asiento');
                                    echo htmlspecialchars(implode(', ', $asientos));
                                    ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <div class="qr-placeholder">
                                    <span class="text-muted">Código QR</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Instrucciones -->
                    <div class="alert alert-info">
                        <h6><strong>📋 Instrucciones Importantes:</strong></h6>
                        <ul class="mb-0">
                            <li>Presenta tu código de reserva en el mostrador de la aerolínea</li>
                            <li>Llega al aeropuerto al menos 2 horas antes del vuelo</li>
                            <li>El check-in cierra 45 minutos antes de la salida</li>
                            <li>Verifica los requisitos de equipaje de la aerolínea</li>
                            <li>Conserva este comprobante para futuras referencias</li>
                        </ul>
                    </div>
                    
                    <!-- Botones de acción -->
                    <div class="row g-2 mt-4 no-print">
                        <div class="col-md-4">
                            <button onclick="window.print()" class="btn btn-primary w-100">
                                🖨️ Imprimir Tiquete
                            </button>
                        </div>
                        <div class="col-md-4">
                            <a href="descargar_pdf.php?codigo=<?= htmlspecialchars($codigoReserva) ?>" 
                               class="btn btn-success w-100">
                                📄 Descargar PDF
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="mis_reservas.php" class="btn btn-outline-primary w-100">
                                📋 Mis Reservas
                            </a>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3 no-print">
                        <a href="../../index.php" class="btn btn-outline-secondary">
                            🏠 Volver al Inicio
                        </a>
                    </div>
                </div>
                
                <!-- Información adicional -->
                <div class="alert alert-success no-print">
                    <h5>✅ Confirmación enviada</h5>
                    <p class="mb-0">
                        Se ha enviado un correo de confirmación a <strong><?= htmlspecialchars($infoGeneral['email']) ?></strong> 
                        con todos los detalles de tu reserva.
                    </p>
                </div>
                
                <!-- Políticas -->
                <div class="card no-print">
                    <div class="card-body">
                        <h5>📋 Políticas y Términos</h5>
                        
                        <div class="accordion" id="accordionPoliticas">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" 
                                            data-bs-toggle="collapse" data-bs-target="#collapseUno">
                                        Política de Cancelación
                                    </button>
                                </h2>
                                <div id="collapseUno" class="accordion-collapse collapse" 
                                     data-bs-parent="#accordionPoliticas">
                                    <div class="accordion-body">
                                        <p>Puedes cancelar tu reserva hasta 24 horas antes del vuelo:</p>
                                        <ul>
                                            <li>Más de 7 días: Reembolso del 90%</li>
                                            <li>3-7 días: Reembolso del 70%</li>
                                            <li>24-72 horas: Reembolso del 50%</li>
                                            <li>Menos de 24 horas: Sin reembolso</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" 
                                            data-bs-toggle="collapse" data-bs-target="#collapseDos">
                                        Política de Equipaje
                                    </button>
                                </h2>
                                <div id="collapseDos" class="accordion-collapse collapse" 
                                     data-bs-parent="#accordionPoliticas">
                                    <div class="accordion-body">
                                        <p><strong>Equipaje de mano:</strong> 1 pieza de hasta 10kg</p>
                                        <p><strong>Equipaje documentado:</strong> 1 pieza de hasta 23kg (incluido)</p>
                                        <p><strong>Equipaje adicional:</strong> Consultar tarifas con la aerolínea</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" 
                                            data-bs-toggle="collapse" data-bs-target="#collapseTres">
                                        Cambios de Vuelo
                                    </button>
                                </h2>
                                <div id="collapseTres" class="accordion-collapse collapse" 
                                     data-bs-parent="#accordionPoliticas">
                                    <div class="accordion-body">
                                        <p>Los cambios de fecha o destino están sujetos a:</p>
                                        <ul>
                                            <li>Disponibilidad de asientos</li>
                                            <li>Pago de diferencia tarifaria</li>
                                            <li>Cargo por cambio: $50.000 COP</li>
                                        </ul>
                                        <p>Contacta con nuestro centro de servicio al cliente para realizar cambios.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Soporte -->
                <div class="text-center mt-4 no-print">
                    <h5>¿Necesitas ayuda?</h5>
                    <p class="text-muted">Contáctanos en cualquier momento</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="mailto:soporte@senaporc.com" class="btn btn-outline-primary">
                            📧 Email
                        </a>
                        <a href="tel:+573001234567" class="btn btn-outline-primary">
                            📞 Teléfono
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            💬 Chat en vivo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Efecto confetti al cargar (opcional)
        window.addEventListener('load', function() {
            // Puedes agregar una librería de confetti aquí
            console.log('Reserva confirmada exitosamente!');
        });
    </script>
</body>
</html>