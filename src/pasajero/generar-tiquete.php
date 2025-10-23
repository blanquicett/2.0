<?php
require('../../fpdf/fpdf.php');
include "../conexion.php";

if (!isset($_GET['idTiquete'])) {
    die("Error: No se especificó un tiquete.");
}

$idTiquete = intval($_GET['idTiquete']);

$query = "
SELECT 
    t.idTiquete,
    t.fecha AS fechaTiquete,
    t.totalPagar,
    r.idReserva,
    r.condicionInfante,
    r.iva,
    r.descuento,
    r.subtotal,
    d.fecha AS fechaVuelo,
    d.origen,
    d.destino,
    d.horaSalida,
    d.horaLlegada,
    a.nombreAvion,
    ma.modelo,
    ma.capacidad,
    ae.nombreAerolinea,
    p.nombres,
    p.primerApellido,
    p.segundoApellido,
    p.documento,
    p.email,
    p.celular
FROM tiquetes t
INNER JOIN reservas r ON t.idReserva = r.idReserva
INNER JOIN disponibilidad d ON r.idDisponibilidad = d.idDisponibilidad
INNER JOIN aviones a ON d.idAvion = a.idAvion
INNER JOIN modeloaviones ma ON a.idModeloA = ma.idModeloA
INNER JOIN aerolinea ae ON a.idAerolinea = ae.idAerolinea
INNER JOIN pasajeros p ON r.idPasajeros = p.idPasajero
WHERE t.idTiquete = '$idTiquete';
";

$result = $conexion->query($query);
if ($result->num_rows == 0) {
    die("No se encontró el tiquete.");
}

$tiquete = $result->fetch_assoc();
if (isset($_GET['formato']) && $_GET['formato'] === 'json') {
    header('Content-Type: application/json');
    echo json_encode($tiquete, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 10, utf8_decode('Tiquete de Reserva Aérea'), 0, 1, 'C');
$pdf->Ln(8);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'Código de Tiquete: ' . $tiquete['idTiquete'], 0, 1);
$pdf->Cell(0, 8, 'Fecha de Emisión: ' . $tiquete['fechaTiquete'], 0, 1);
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 8, utf8_decode('Información del Vuelo:'), 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, 'Aerolinea: ' . utf8_decode($tiquete['nombreAerolinea']), 0, 1);
$pdf->Cell(0, 8, 'Avión: ' . utf8_decode($tiquete['nombreAvion'] . ' - Modelo ' . $tiquete['modelo']), 0, 1);
$pdf->Cell(0, 8, 'Ruta: ' . utf8_decode($tiquete['origen'] . ' → ' . $tiquete['destino']), 0, 1);
$pdf->Cell(0, 8, 'Fecha de vuelo: ' . $tiquete['fechaVuelo'], 0, 1);
$pdf->Cell(0, 8, 'Hora salida: ' . $tiquete['horaSalida'] . ' | Hora llegada: ' . $tiquete['horaLlegada'], 0, 1);
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 8, utf8_decode('Pasajero:'), 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, utf8_decode($tiquete['nombres'] . ' ' . $tiquete['primerApellido'] . ' ' . $tiquete['segundoApellido']), 0, 1);
$pdf->Cell(0, 8, 'Documento: ' . $tiquete['documento'], 0, 1);
$pdf->Cell(0, 8, 'Email: ' . $tiquete['email'], 0, 1);
$pdf->Cell(0, 8, 'Celular: ' . $tiquete['celular'], 0, 1);
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 8, 'Resumen de Pago:', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, 'Subtotal: $' . number_format($tiquete['subtotal'], 0, ',', '.'), 0, 1);
$pdf->Cell(0, 8, 'IVA (19%): $' . number_format($tiquete['iva'] * $tiquete['subtotal'], 0, ',', '.'), 0, 1);
$pdf->Cell(0, 8, 'Descuento: $' . number_format($tiquete['descuento'] * $tiquete['subtotal'], 0, ',', '.'), 0, 1);
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Total a pagar: $' . number_format($tiquete['totalPagar'], 0, ',', '.'), 0, 1);

$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->MultiCell(0, 8, utf8_decode("Este tiquete confirma su reserva en el sistema de Desarrollo Libre. Presentar en el aeropuerto junto con su documento de identidad. No es reembolsable salvo políticas de la aerolínea."));

$pdf->Output('I', 'Tiquete_' . $tiquete['idTiquete'] . '.pdf');
?>
