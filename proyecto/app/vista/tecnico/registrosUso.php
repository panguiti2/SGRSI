<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros de uso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/global.css">
</head>
<body class="d-flex flex-column min-vh-100 sgrsi-app" id="inicio">
    <header class="navbar navbar-expand-md navbar-dark sgrsi-navbar sticky-top">
        <section class="container-fluid">
            <a class="navbar-brand fw-bold" href="inicio.php"><img src="../assets/img/logoITI.png" alt="Logo ITI" class="sgrsi-navbar-logo">SGRSI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal" aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir menú"><span class="navbar-toggler-icon"></span></button>
            <nav class="collapse navbar-collapse" id="menuPrincipal">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="inicio.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="solicitudes.php">Solicitudes</a></li>
                    <li class="nav-item"><a class="nav-link" href="incidencias.php">Incidencias</a></li>
                    <li class="nav-item"><a class="nav-link" href="metricas.php">Métricas</a></li>
                    <li class="nav-item"><a class="nav-link" href="inventario.php">Inventario</a></li>
                    <li class="nav-item"><a class="nav-link" href="prestamos.php">Préstamos</a></li>
                    <li class="nav-item"><a class="nav-link active" href="registrosUso.php">Registros de uso</a></li>
                    <li class="nav-item"><a class="nav-link" href="../cerrarSesion.php">Cerrar sesión</a></li>
                </ul>
            </nav>
        </section>
    </header>
    <main class="container-xl flex-grow-1 p-3 p-md-4">
        <section class="bg-white rounded p-3 p-md-4 panelTabla">
            <h1 class="h3 mb-3">Registros de uso de laboratorios</h1>
            <section class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0">
                    <thead class="table-light"><tr><th>Solicitante</th><th>Laboratorio</th><th>Turno</th><th>Fecha y hora</th><th>Docente</th><th>Grupo</th><th>Asignatura</th><th>Máquinas</th><th>Incidencias</th></tr></thead>
                    <tbody>
                        <?php foreach ($registrosUso as $registro): ?>
                            <tr><td><?= htmlspecialchars($registro["solicitante"]) ?></td><td><?= htmlspecialchars($registro["laboratorio"]) ?></td><td><?= htmlspecialchars($registro["turno"]) ?></td><td><?= htmlspecialchars($registro["fechaHora"]) ?></td><td><?= htmlspecialchars($registro["nombreDocente"]) ?></td><td><?= htmlspecialchars($registro["grupo"]) ?></td><td><?= htmlspecialchars($registro["asignatura"]) ?></td><td><?= $registro["usoMaquinas"] ? "Sí" : "No" ?></td><td><?= $registro["huboIncidencias"] ? "Sí" : "No" ?></td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </section>
    </main>
    <footer class="sgrsi-footer text-light mt-auto py-3 py-md-4"><p class="text-center mb-0">© 2026 SGRSI</p></footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
