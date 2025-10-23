<?php 
include "../../conexion.php";

$idAvion=$_GET['idAvion'];

$sql = "SELECT * FROM aviones WHERE idAvion='$idAvion'";
$query = mysqli_query($conexion,$sql);
$row = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Avión</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .users-form {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .users-form input[type="text"],
        .users-form input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            color: #333;
            font-size: 16px;
        }
        .users-form input[type="text"]::placeholder,
        .users-form input[type="number"]::placeholder {
            color: #aaa;
        }
        .users-form input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            font-size: 16px;
            width: 100%;
        }
        .users-form input[type="submit"]:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="users-form">
        <h2>Editar Avión</h2>
        <form action="editar-avion.php" method="POST">
            <input type="hidden" name="idAvion" value="<?= $row['idAvion'] ?>">

            <input type="text" name="nombreAvion" placeholder="Nombre del avión" 
                   value="<?= $row['nombreAvion'] ?>" required>

            <input type="number" name="idModeloA" placeholder="ID del modelo" 
                   value="<?= $row['idModeloA'] ?>" required>

            <input type="number" name="idAerolinea" placeholder="ID de la aerolínea" 
                   value="<?= $row['idAerolinea'] ?>" required>

            <input type="submit" value="Actualizar" name="actualizar">
        </form>
    </div>
</body>
</html>
