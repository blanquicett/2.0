<?php
include("../../conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fecha = $_POST['fecha'];
    $totalPagar = $_POST['totalPagar'];
    $idReserva = $_POST['idReserva'];

    if (!empty($fecha) && !empty($totalPagar) && !empty($idReserva)) {
        $sql = "INSERT INTO tiquetes (fecha, totalPagar, idReserva) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdi", $fecha, $totalPagar, $idReserva);

        if ($stmt->execute()) {
            echo "<script>alert('✅ Tiquete registrado correctamente');</script>";
        } else {
            echo "<script>alert('❌ Error al registrar el tiquete: " . $conn->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('⚠️ Todos los campos son obligatorios');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Tiquete</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f3;
            margin: 0;
        }

        /* NAVBAR */
        nav {
            background-color: #0078D7;
            padding: 12px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        nav h1 {
            margin: 0;
            font-size: 20px;
            letter-spacing: 1px;
        }

        .nav-links {
            display: flex;
            gap: 25px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: #ffcc00;
        }

        /* FORM */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 70px);
        }

        form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            width: 350px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #444;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #bbb;
            border-radius: 5px;
        }

        button {
            width: 100%;
            background: #0078D7;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #005ea3;
        }
    </style>
</head>
<body>

    <!-- 🔹 NAVBAR -->
    <nav>
        <h1>Desarrollo Libre ✈️</h1>
        <div class="nav-links">
            <a href="user.php">Inicio</a>
            <a href="vuelos.php">Reservas</a>
            <a href="disponibilidad.php">Disponibilidad</a>
            <a href="tiquete.php">Tiquetes</a>
        </div>
    </nav>

    <!-- 🔹 FORMULARIO DE TIQUETE -->
    <div class="container">
        <form method="POST" action="">
            <h2>Registrar Tiquete</h2>

            <label for="fecha">Fecha del tiquete:</label>
            <input type="date" name="fecha" id="fecha" required>

            <label for="totalPagar">Total a pagar:</label>
            <input type="number" name="totalPagar" id="totalPagar" step="0.01" required>

            <label for="idReserva">Reserva asociada:</label>
            <select name="idReserva" id="idReserva" required>
                <option value="">-- Selecciona una reserva --</option>
                <?php
                $query = $conn->query("SELECT idReserva FROM reservas");
                while ($row = $query->fetch_assoc()) {
                    echo "<option value='{$row['idReserva']}'>Reserva #{$row['idReserva']}</option>";
                }
                ?>
            </select>

            <button type="submit">Guardar Tiquete</button>
        </form>
    </div>

</body>
</html>
