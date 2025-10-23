<?php
include ("../conexion.php");

// Recibir parámetros
$origen = isset($_GET['origen']) ? trim($_GET['origen']) : '';
$destino = isset($_GET['destino']) ? trim($_GET['destino']) : '';
$fecha_ida = isset($_GET['fecha_ida']) ? trim($_GET['fecha_ida']) : '';
$fecha_vuelta = isset($_GET['fecha_vuelta']) ? trim($_GET['fecha_vuelta']) : '';

$errors = [];
if ($origen === '') $errors[] = 'Origen no especificado.';
if ($destino === '') $errors[] = 'Destino no especificado.';
if ($fecha_ida === '') $errors[] = 'Fecha de ida no especificada.';

?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Vuelos - Resultados</title>
    <link href="../css/index.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
<?php
if (!empty($errors)){
    echo "<h3>Errores en la búsqueda</h3><ul>";
    foreach ($errors as $e) echo "<li>".htmlspecialchars($e)."</li>";
    echo "</ul><p><a href=\"../index.php\">Volver</a></p>";
    exit;
}

// Consulta preparada
$sql = "SELECT d.idDisponibilidad, d.fecha, d.origen, d.destino, d.horaSalida, d.horaLlegada, av.nombreAvion, a.nombreAerolinea
        FROM disponibilidad d
        LEFT JOIN aviones av ON d.idAvion = av.idAvion
        LEFT JOIN aerolinea a ON av.idAerolinea = a.idAerolinea
        WHERE d.origen LIKE ? AND d.destino LIKE ? AND d.fecha = ?";

if ($stmt = mysqli_prepare($conexion, $sql)){
    $origen_param = "%" . $origen . "%";
    $destino_param = "%" . $destino . "%";
    mysqli_stmt_bind_param($stmt, 'sss', $origen_param, $destino_param, $fecha_ida);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    echo "<h2>Resultados de búsqueda</h2>";
    echo "<p>De <strong>".htmlspecialchars($origen)."</strong> a <strong>".htmlspecialchars($destino)."</strong> (".htmlspecialchars($fecha_ida).")</p>";

    if ($res && mysqli_num_rows($res) > 0){
        echo "<div class=\"row g-3\">";
        while ($row = mysqli_fetch_assoc($res)){
            echo "<div class=\"col-12\"><div class=\"card mb-2\"><div class=\"card-body\">";
            echo "<h5 class=\"card-title\">".htmlspecialchars($row['nombreAerolinea'])." — ".htmlspecialchars($row['nombreAvion'])."</h5>";
            echo "<p class=\"mb-1\"><strong>Fecha:</strong> ".htmlspecialchars($row['fecha'])."</p>";
            echo "<p class=\"mb-1\"><strong>Salida:</strong> ".htmlspecialchars($row['horaSalida'])." — <strong>Llegada:</strong> ".htmlspecialchars($row['horaLlegada'])."</p>";
            echo "<p class=\"mb-0\">".htmlspecialchars($row['origen'])." → ".htmlspecialchars($row['destino'])."</p>";
            echo "<div class=\"mt-3\">
                    <a href=\"user/reserva.php?vuelo=".htmlspecialchars($row['idDisponibilidad'])."\" 
                       class=\"btn btn-primary\">Reservar Vuelo</a>
                  </div>";
            echo "</div></div></div>";
        }
        echo "</div>";
    } else {
        echo "<p>No se encontraron vuelos para los criterios indicados.</p>";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<p>Error al preparar la consulta.</p>";
}

?>
<p><a href="../index.php">Volver a buscar</a></p>
<p><a href="user/asientos.php">Asientos</a></p>



</div>
</body>
</html>
