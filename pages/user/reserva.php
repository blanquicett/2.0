<?php
include ("../../conexion.php");

// Verificar si se proporcionó un ID de vuelo
$idVuelo = isset($_GET['vuelo']) ? (int)$_GET['vuelo'] : 0;

// Obtener detalles del vuelo
$sql = "SELECT d.*, av.nombreAvion, a.nombreAerolinea, 
        (SELECT COUNT(*) FROM tiquetes WHERE idVuelo = d.idDisponibilidad) as asientosOcupados,
        ma.capacidad as capacidadTotal
        FROM disponibilidad d
        INNER JOIN aviones av ON d.idAvion = av.idAvion
        INNER JOIN aerolinea a ON av.idAerolinea = a.idAerolinea
        INNER JOIN modeloaviones ma ON av.idModeloA = ma.idModeloA
        WHERE d.idDisponibilidad = ?";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $idVuelo);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$vuelo = mysqli_fetch_assoc($resultado);

if (!$vuelo) {
    die('Vuelo no encontrado');
}

// Calcular asientos disponibles
$asientosDisponibles = $vuelo['capacidadTotal'] - $vuelo['asientosOcupados'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Vuelo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .passenger-form {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }
        
        .flight-info {
            background: linear-gradient(135deg, #0d6efd0d 0%, #0d6efd1a 100%);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .infant-form {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="mb-4">Reserva de Vuelo</h1>
        
        <!-- Información del vuelo -->
        <div class="flight-info">
            <h4 class="card-title"><?= htmlspecialchars($vuelo['nombreAerolinea']) ?> — <?= htmlspecialchars($vuelo['nombreAvion']) ?></h4>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Origen:</strong> <?= htmlspecialchars($vuelo['origen']) ?></p>
                    <p><strong>Destino:</strong> <?= htmlspecialchars($vuelo['destino']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Fecha:</strong> <?= htmlspecialchars($vuelo['fecha']) ?></p>
                    <p><strong>Horario:</strong> <?= htmlspecialchars($vuelo['horaSalida']) ?> - <?= htmlspecialchars($vuelo['horaLlegada']) ?></p>
                </div>
            </div>
            <p class="mb-0"><strong>Asientos disponibles:</strong> <?= $asientosDisponibles ?></p>
        </div>

        <form action="procesar_reserva.php" method="POST" id="reservaForm">
            <input type="hidden" name="idVuelo" value="<?= $idVuelo ?>">
            
            <div class="mb-4">
                <h3>Pasajeros</h3>
                <p class="text-muted">Puede reservar hasta 5 asientos por reserva</p>
            </div>

            <!-- Contenedor para formularios de pasajeros -->
            <div id="pasajerosContainer">
                <!-- El primer pasajero siempre se muestra -->
                <div class="passenger-form" id="pasajero1">
                    <h4>Pasajero 1</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombres</label>
                            <input type="text" class="form-control" name="pasajeros[0][nombres]" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Primer Apellido</label>
                            <input type="text" class="form-control" name="pasajeros[0][primerApellido]" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Segundo Apellido</label>
                            <input type="text" class="form-control" name="pasajeros[0][segundoApellido]">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" name="pasajeros[0][fechaNacimiento]" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Género</label>
                            <select class="form-select" name="pasajeros[0][genero]" required>
                                <option value="">Seleccione...</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tipo de Documento</label>
                            <select class="form-select" name="pasajeros[0][tipoDocumento]" required>
                                <option value="">Seleccione...</option>
                                <option value="CC">Cédula de Ciudadanía</option>
                                <option value="CE">Cédula de Extranjería</option>
                                <option value="TI">Tarjeta de Identidad</option>
                                <option value="PP">Pasaporte</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Número de Documento</label>
                            <input type="text" class="form-control" name="pasajeros[0][documento]" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Teléfono/Celular</label>
                            <input type="tel" class="form-control" name="pasajeros[0][celular]">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" name="pasajeros[0][email]" required>
                        </div>
                        
                        <!-- Sección para infante asociado -->
                        <div class="col-12">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="tieneInfante0" 
                                       onchange="toggleInfante(0)">
                                <label class="form-check-label" for="tieneInfante0">
                                    Viaja con infante (menor de 2 años)
                                </label>
                            </div>
                        </div>
                        
                        <div id="infanteForm0" style="display: none;" class="col-12 infant-form p-3">
                            <h5>Datos del Infante</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nombres del Infante</label>
                                    <input type="text" class="form-control infante-input" name="pasajeros[0][infante][nombres]">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Primer Apellido</label>
                                    <input type="text" class="form-control infante-input" name="pasajeros[0][infante][primerApellido]">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Segundo Apellido</label>
                                    <input type="text" class="form-control infante-input" name="pasajeros[0][infante][segundoApellido]">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control infante-input" name="pasajeros[0][infante][fechaNacimiento]">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Documento</label>
                                    <input type="text" class="form-control infante-input" name="pasajeros[0][infante][documento]">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de control -->
            <div class="mt-4 mb-5">
                <button type="button" class="btn btn-secondary" onclick="agregarPasajero()" 
                        id="btnAgregarPasajero">
                    Agregar Otro Pasajero
                </button>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="../vuelos.php" class="btn btn-outline-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary" onclick="return validarFormulario()">Continuar con la Reserva</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para validar todo el formulario antes de enviar
        function validarFormulario() {
            const forms = document.querySelectorAll('.passenger-form');
            
            for (let index = 0; index < forms.length; index++) {
                const form = forms[index];
                
                // Validar campos requeridos
                const requiredInputs = form.querySelectorAll('input[required], select[required]');
                for (let input of requiredInputs) {
                    if (!input.value.trim()) {
                        alert('Por favor complete todos los campos requeridos para el Pasajero ' + (index + 1));
                        input.focus();
                        return false;
                    }
                }

                // Validar correo electrónico
                const emailInput = form.querySelector('input[type="email"]');
                if (emailInput && !validarEmail(emailInput.value.trim())) {
                    alert('Por favor ingrese un correo electrónico válido para el Pasajero ' + (index + 1));
                    emailInput.focus();
                    return false;
                }

                // Validar que el documento solo contenga números
                const documentoInput = form.querySelector('input[name^="pasajeros["][name$="][documento]"]');
                if (documentoInput && !/^\d+$/.test(documentoInput.value.trim())) {
                    alert('El número de documento solo debe contener números para el Pasajero ' + (index + 1));
                    documentoInput.focus();
                    return false;
                }

                // Validar infante si está marcado
                const tieneInfante = form.querySelector('input[id^="tieneInfante"]');
                if (tieneInfante && tieneInfante.checked) {
                    const infanteInputs = form.querySelectorAll('#infanteForm' + index + ' input');
                    for (let input of infanteInputs) {
                        if (!input.value.trim()) {
                            alert('Por favor complete todos los campos del infante para el Pasajero ' + (index + 1));
                            input.focus();
                            return false;
                        }
                    }
                }
            }

            return isValid;
        }

        function validarEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    </script>
    <script>
        let contadorPasajeros = 1;
        const maxPasajeros = 5;

        function agregarPasajero() {
            if (contadorPasajeros >= maxPasajeros) {
                alert('Solo se permiten hasta 5 pasajeros por reserva');
                return;
            }

            const template = document.getElementById('pasajero1').cloneNode(true);
            template.id = 'pasajero' + contadorPasajeros;
            
            // Actualizar título
            template.querySelector('h4').textContent = 'Pasajero ' + (contadorPasajeros + 1);
            
            // Actualizar nombres de campos y requerimientos
            const inputs = template.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.name) {
                    // Mantener el atributo required si existía en el original
                    const wasRequired = input.hasAttribute('required');
                    input.name = input.name.replace('[0]', '[' + contadorPasajeros + ']');
                    if (wasRequired) {
                        input.required = true;
                    }
                }
                if (input.id && input.id.includes('tieneInfante')) {
                    input.id = 'tieneInfante' + contadorPasajeros;
                    input.checked = false;
                }
                // Limpiar el valor
                if (input.type !== 'checkbox' && input.type !== 'radio') {
                    input.value = '';
                }
            });
            
            // Actualizar formulario de infante
            const infanteForm = template.querySelector('[id^="infanteForm"]');
            infanteForm.id = 'infanteForm' + contadorPasajeros;
            infanteForm.style.display = 'none';
            
            // Actualizar el evento onChange del checkbox
            const checkbox = template.querySelector('[id^="tieneInfante"]');
            checkbox.setAttribute('onchange', `toggleInfante(${contadorPasajeros})`);
            
            // Limpiar valores
            template.querySelectorAll('input:not([type="checkbox"])').forEach(input => {
                input.value = '';
            });
            template.querySelectorAll('select').forEach(select => {
                select.selectedIndex = 0;
            });
            
            document.getElementById('pasajerosContainer').appendChild(template);
            contadorPasajeros++;
            
            // Actualizar visibilidad del botón
            if (contadorPasajeros >= maxPasajeros) {
                document.getElementById('btnAgregarPasajero').style.display = 'none';
            }
        }

        function toggleInfante(index) {
            const checkbox = document.getElementById('tieneInfante' + index);
            const infanteForm = document.getElementById('infanteForm' + index);
            
            if (checkbox.checked) {
                infanteForm.style.display = 'block';
                infanteForm.querySelectorAll('input').forEach(input => {
                    input.required = true;
                });
            } else {
                infanteForm.style.display = 'none';
                infanteForm.querySelectorAll('input').forEach(input => {
                    input.required = false;
                    input.removeAttribute('required');  // Asegurarse de que se elimine el atributo
                    input.value = '';
                });
            }
        }

        // Validar fecha de nacimiento para infantes
        document.querySelectorAll('[name$="[infante][fechaNacimiento]"]').forEach(input => {
            input.addEventListener('change', function() {
                const fechaNacimiento = new Date(this.value);
                const hoy = new Date();
                const edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
                
                if (edad >= 2) {
                    alert('El infante debe ser menor de 2 años');
                    this.value = '';
                }
            });
        });
    </script>
</body>
</html>
