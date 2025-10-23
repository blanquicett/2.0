<?php
    $servername = "localhost";
    $username = "root";
    $pass = "";
    $dbname = "desarrollolibre";

    $conexion = mysqli_connect($servername, $username, $pass, $dbname);
    if ($conexion -> connect_error){
        die ("conexion fallida" . $conexion->connect_error);
    }

    echo "conexion exisota"

?>