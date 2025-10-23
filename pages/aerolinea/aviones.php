<?php
include "../../conexion.php";

$resultados=[];
$mostrarTabla=true;

if (isset($_POST['buscar'])) {
    $termino = trim($_POST['termino']);

    if (strlen($termino)>=1) {
        $termino = $conexion->real_escape_string($termino);
        
        $query = "SELECT * FROM aviones 
                  WHERE idAvion = '$termino' 
                  OR nombreAvion LIKE'%$termino%' 
                  OR idModeloA='$termino' 
                  OR idAerolinea='$termino'";
        $resultado=$conexion->query($query);

        if ($resultado->num_rows>0) {
            $resultados=$resultado->fetch_all(MYSQLI_ASSOC);
            $mostrarTabla=false;
        } else { ?>
            <script>alert("No se encontraron coincidencias con el término ingresado.");</script>
        <?php }
    } else { ?>
        <script>alert("Debe ingresar al menos 1 carácter para buscar.");</script>
    <?php }
}

$sql="SELECT 
            a.idAvion,
            a.nombreAvion,
            m.modelo AS modelo,
            m.capacidad AS capacidad,
            ae.nombreAerolinea AS aerolinea
        FROM aviones a
        INNER JOIN modeloaviones m ON a.idModeloA = m.idModeloA
        INNER JOIN aerolinea ae ON a.idAerolinea = ae.idAerolinea";

$query=mysqli_query($conexion, $sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/aviones.css">
    <title>CRUD Aviones</title>
</head>

<body>
    <div class="users-form">
        <div class="log-out-container">
            <div class="log-out">
                <button><a href="aerolinea.php">Atras</a></button>
            </div>
            <div class="log-out">
                <button><a href="../../src/log-out.php">Cerrar Sesión</a></button>
            </div>
        </div>

        <form action="php/insert-avion.php" method="POST">
            <h1>Registrar Avión</h1>
            <input type="number" name="idAvion" placeholder="ID del Avión" required>
            <input type="text" name="nombreAvion" placeholder="Nombre del Avión" required>
            <input type="number" name="idModeloA" placeholder="ID del Modelo" required>
            <input type="number" name="idAerolinea" placeholder="ID de la Aerolínea" required>
            <input type="submit" value="Guardar">
        </form>

        <div class="boton-buscar">
            <form id="searchForm" method="POST">
                <input type="text" name="termino" id="input-text" placeholder="Buscar avión..." required><br>
                <input type="submit" value="Buscar" id="input-buscar" name="buscar">
            </form>
        </div>
    </div>

    <?php if (!empty($resultados)): ?>
        <div class="users-table">
            <h2>Resultados de la búsqueda</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Avión</th>
                        <th>Nombre</th>
                        <th>Modelo</th>
                        <th>Capacidad</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $avion): ?>
                        <tr>
                            <td><?= $avion['idAvion']; ?></td>
                            <td><?= $avion['nombreAvion']; ?></td>
                            <td><?= $avion['modelo']; ?></td>
                            <td><?= $avion['capacidad']; ?></td>
                            <td><a href="../../src/aerolinea/editar-avion2.php?idAvion=<?= $avion['idAvion']; ?>" class="users-table--edit">Editar</a></td>
                            <td><a href="../../src/aerolinea/eliminar-avion.php?idAvion<?= $avion['idAvion']; ?>" class="users-table--delete">Eliminar</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <form method="POST">
                <input type="submit" class="users-table--delete" value="Mostrar Todos" name="mostrar_todos">
            </form>
        </div>
    <?php endif; ?>

    <?php if ($mostrarTabla): ?>
        <div class="users-table">
            <h2>Aviones Registrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Avión</th>
                        <th>Nombre</th>
                        <th>Modelo</th>
                        <th>Capacidad</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_array($query)): ?>
                        <tr>
                            <td><?= $row['idAvion'] ?></td>
                            <td><?= $row['nombreAvion'] ?></td>
                            <td><?= $row['modelo'] ?></td>
                            <td><?= $row['capacidad'] ?></td>
                            <td><a href="../../src/aerolinea/editar-avion2.php?idAvion=<?= $row['idAvion'] ?>" class="users-table--edit">Editar</a></td>
                            <td><a href="../../src/aerolinea/eliminar-avion.php?idAvion=<?= $row['idAvion'] ?>" class="users-table--delete">Eliminar</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</body>
</html>
