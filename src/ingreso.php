<?php
include ("../conexion.php");
$email = $_POST["email"];
$password  = $_POST["password"];


if ($email === '' || $password === '') {
    echo "Por favor complete todos los campos.";
    exit();
}
$consulta="SELECT p.nombres, p.primerApellido, r.nombreRol, p.password 
           FROM pasajeros p 
           JOIN roles r ON p.idRol = r.idRol 
           WHERE p.email = ?";

$stmt=$conexion->prepare($consulta);
$stmt->bind_param("s",$email);
$stmt->execute();
$result=$stmt->get_result();

if ($result->num_rows===1) {
    $user=$result->fetch_assoc();
    if (
        password_verify($password, $user['password']) ||
        trim($password) === trim($user['password'])
    ) {
        switch ($user['nombreRol']) {
            case 'admin':
                header("Location: ../pages/admin/admin.php");
                exit();
            case 'aerolinea':
                header("Location: ../pages/aerolinea/aerolinea.php");
                exit();
            case 'pasajero':
                header("Location: ../pages/user/user.php");
                exit();
            default:
                die('Rol no reconocido.');
        }
    } else {
        die('Contraseña incorrecta.');
    }
}else {
    $sql_aero = "SELECT * FROM aerolinea WHERE email = ?";
    $stmt = $conexion->prepare($sql_aero);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result_aero = $stmt->get_result();

    if ($result_aero->num_rows === 1) {
        $aero = $result_aero->fetch_assoc();

        // Como las contraseñas NO están encriptadas, comparamos texto plano
        if (trim($password) === trim($aero['password'])) {
            header("Location: ../pages/aerolinea/aerolinea.php");
            exit();
        } else {
            die('Contraseña incorrecta para aerolínea.');
        }
    } else {
        die('Credenciales inválidas. El usuario o aerolínea no existen.');
    }

    $stmt->close();
} 

?>
