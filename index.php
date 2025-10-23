<?php
    include 'conexion.php';

    // Obtener ciudades únicas para origen y destino
    $sql = "SELECT DISTINCT origen FROM disponibilidad UNION SELECT DISTINCT destino FROM disponibilidad ORDER BY origen";
    $result = mysqli_query($conexion, $sql);
    $ciudades = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $ciudades[] = $row['origen'];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">    

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SENASOFT - Vuelos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/coloresGblo.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg header">
        <div class="container">
            <a class="navbar-brand" href="#">SENAPORC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav ms-auto align-items-center">
                    <?php if (!empty($_SESSION['user_email'])): ?>
                        <li class="nav-item me-3"><span class="small-muted">Hola, <?= htmlspecialchars($_SESSION['user_email']) ?></span></li>
                        <li class="nav-item"><a class="btn btn-outline-secondary" href="logout.php">Cerrar sesión</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="pages/login.php">Iniciar sesión</a></li>
                        <li class="nav-item"><a class="nav-link" href="pages/register.php">Crear cuenta</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Nosotros</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <header class="py-5">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-7">
                    <h1 class="display-5 fw-bold">Encuentra vuelos al mejor precio</h1>
                    <p class="lead small-muted">Busca y compara vuelos nacionales e internacionales. Promociones actualizadas diariamente.</p>
                </div>

                <div class="col-lg-5">
                    <div class="form-card">
                        <form method="get" action="pages/vuelos.php" class="row g-2">
                            <div class="col-12 mb-3">
                                <div class="btn-group w-100" role="group" aria-label="Tipo de vuelo">
                                    <input type="radio" class="btn-check" name="tipo_vuelo" id="ida" value="ida" checked>
                                    <label class="btn btn-outline-primary" for="ida">Solo ida</label>

                                    <input type="radio" class="btn-check" name="tipo_vuelo" id="ida_vuelta" value="ida_vuelta">
                                    <label class="btn btn-outline-primary" for="ida_vuelta">Ida y vuelta</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Origen</label>
                                <select name="origen" class="form-select select2" required>
                                    <option value="">Seleccione ciudad origen</option>
                                    <?php foreach($ciudades as $ciudad): ?>
                                        <option value="<?= htmlspecialchars($ciudad) ?>"><?= htmlspecialchars($ciudad) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Destino</label>
                                <select name="destino" class="form-select select2" required>
                                    <option value="">Seleccione ciudad destino</option>
                                    <?php foreach($ciudades as $ciudad): ?>
                                        <option value="<?= htmlspecialchars($ciudad) ?>"><?= htmlspecialchars($ciudad) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12 col-sm-6">
                                <label class="form-label">Fecha ida</label>
                                <input type="date" name="fecha_ida" class="form-control" required 
                                       min="<?= date('Y-m-d') ?>">
                            </div>

                            <div class="col-12 col-sm-6" id="fecha_vuelta_container">
                                <label class="form-label">Fecha vuelta</label>
                                <input type="date" name="fecha_vuelta" class="form-control"
                                       min="<?= date('Y-m-d') ?>">
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100 mt-3">Buscar vuelos</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="py-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <h4>Destinos populares</h4>
                        <small class="small-muted">Explora los destinos más reservados</small>
                    </div>

                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        <?php if (!empty($destinos)): ?>
                            <?php foreach ($destinos as $d): ?>
                                <div class="col">
                                    <div class="card destino-card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-1"><?= htmlspecialchars($d['name']) ?></h5>
                                            <p class="card-text small-muted mb-1"><?= htmlspecialchars($d['country']) ?></p>
                                            <p class="card-text"><?= htmlspecialchars($d['short_description']) ?></p>
                                            <a href="resultados.php?destino=<?= urlencode($d['name']) ?>" class="stretched-link"></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php for ($i = 0; $i < 4; $i++): ?>
                                <div class="col">
                                    <div class="card destino-card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-1">Destino Ejemplo</h5>
                                            <p class="card-text small-muted mb-1">País Ejemplo</p>
                                            <p class="card-text">Descripción breve del destino. Información destacada y atractiva.</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <aside class="col-12 col-lg-4">
                    <div class="mb-4">
                        <h5>Promociones activas</h5>
                        <?php if (!empty($promociones)): ?>
                            <?php foreach ($promociones as $promo): ?>
                                <div class="card card-promo mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title"><?= htmlspecialchars($promo['title']) ?></h6>
                                        <p class="card-text small-muted"><?= htmlspecialchars($promo['subtitle']) ?></p>
                                        <p class="mb-0"><strong><?= htmlspecialchars($promo['discount_text']) ?></strong></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="card card-promo mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Promo de lanzamiento</h6>
                                    <p class="card-text small-muted">Descuentos exclusivos por tiempo limitado.</p>
                                    <p class="mb-0"><strong>Hasta 30% OFF</strong></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <h6>Información rápida</h6>
                        <p class="small-muted">Políticas de equipaje, cambios y asistencia 24/7. Consulta términos antes de viajar.</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar Select2 en los selects
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: $(this).data('placeholder'),
                allowClear: true
            });

            // Manejar visibilidad de fecha_vuelta según tipo de vuelo
            $('input[name="tipo_vuelo"]').change(function() {
                const fechaVueltaContainer = $('#fecha_vuelta_container');
                const fechaVueltaInput = $('input[name="fecha_vuelta"]');

                if ($(this).val() === 'ida') {
                    fechaVueltaContainer.hide();
                    fechaVueltaInput.prop('required', false);
                } else {
                    fechaVueltaContainer.show();
                    fechaVueltaInput.prop('required', true);
                }
            });

            // Trigger inicial para establecer estado correcto
            $('input[name="tipo_vuelo"]:checked').trigger('change');

            // Validar que destino != origen
            $('form').on('submit', function(e) {
                const origen = $('select[name="origen"]').val();
                const destino = $('select[name="destino"]').val();

                if (origen === destino) {
                    alert('El origen y destino no pueden ser iguales');
                    e.preventDefault();
                    return false;
                }

                // Validar que fecha_vuelta > fecha_ida si es ida_vuelta
                if ($('input[name="tipo_vuelo"]:checked').val() === 'ida_vuelta') {
                    const fechaIda = new Date($('input[name="fecha_ida"]').val());
                    const fechaVuelta = new Date($('input[name="fecha_vuelta"]').val());

                    if (fechaVuelta <= fechaIda) {
                        alert('La fecha de vuelta debe ser posterior a la fecha de ida');
                        e.preventDefault();
                        return false;
                    }
                }
            });
        });
    </script>
</body>

</html>