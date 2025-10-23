<?php
include '../conexion.php'; // Asegúrate de tener este archivo funcionando

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombres = $_POST['nombres'];
    $primerApellido = $_POST['primerApellido'];
    $segundoApellido = $_POST['segundoApellido'];
    $fechNacimiento = $_POST['fechNacimiento'];
    $genero = $_POST['genero'];
    $tipoDocumento = $_POST['tipoDocumento'];
    $documento = $_POST['documento'];
    $celular = $_POST['celular'];
    $email = $_POST['email'];
    $idRol=1;
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO pasajeros 
            (nombres, primerApellido, segundoApellido, fechNacimiento, genero, tipoDocumento, documento, celular, email,idRol, password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssssssis", $nombres, $primerApellido, $segundoApellido, $fechNacimiento, $genero, $tipoDocumento, $documento, $celular, $email,$idRol, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Registro exitoso.'); window.location.href = '../pages/login.php';</script>";
    } else {
        echo "<script>alert('Error al registrar.'); window.history.back();</script>";
    }

    $stmt->close();
    $conexion->close();
}
?>
