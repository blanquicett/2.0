<?php
    include "../../conexion.php"; 
    if (isset($_GET['idAvion'])) {
        $idAvion = $_GET['idAvion'];
        $sql = "DELETE FROM aviones WHERE idAvion = '$idAvion'";
        $result = mysqli_query($conexion, $sql);

        if ($result) {
            echo "Estudiante eliminado correctamente.";
            header("Location: ../../pages/aerolinea/aviones.php");
        } else {
            echo "Error al eliminar el Avion";
        }
    } else {
        echo "Error: ID no especificado.";
    }
?>
