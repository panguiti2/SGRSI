<?php
$rolRequerido = "tecnico";
require_once __DIR__ . "/../../../app/controlador/verificarAcceso.php";
verificarAcceso($rolRequerido);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incidencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/global.css">
</head>
<body class="d-flex flex-column min-vh-100 sgrsi-app">
    <header class="navbar navbar-expand-md navbar-dark sgrsi-navbar sticky-top">
        <section class="container-fluid">
            <a class="navbar-brand fw-bold" href="inicio.php">SGRSI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
                <span class="navbar-toggler-icon"></span>
            </button>
            <nav class="collapse navbar-collapse" id="menuPrincipal">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="inicio.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="solicitudes.php">Solicitudes</a></li>
                    <li class="nav-item"><a class="nav-link active" href="incidencias.php">Incidencias</a></li>
                    <li class="nav-item"><a class="nav-link" href="inventario.php">Inventario</a></li>
                    <li class="nav-item"><a class="nav-link" href="prestamos.php">Préstamos</a></li>
                    <li class="nav-item"><a class="nav-link" href="../cerrarSesion.php">Cerrar sesión</a></li>
                </ul>
            </nav>
        </section>
    </header>
    <main class="container-xl flex-grow-1 p-3 p-md-4">
        <section class="bg-white rounded p-3 p-md-4 panelTabla">
            <h1 class="h3 mb-3">Incidencias registradas</h1>
            <section class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0 small">
                    <thead class="table-light">
                        <tr><th>ID</th><th>Apertura</th><th>Docente</th><th>Grupo</th><th>Alumno</th><th>Descripción</th><th>Estado</th><th>Gestión</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($incidencias)): ?>
                            <tr><td colspan="8" class="text-center text-muted py-3">No hay incidencias registradas.</td></tr>
                        <?php else: foreach ($incidencias as $incidencia): ?>
                            <tr>
                                <td><?= htmlspecialchars($incidencia["idIncidencia"]) ?></td>
                                <td><?= htmlspecialchars($incidencia["fechaApertura"]) ?></td>
                                <td><?= htmlspecialchars($incidencia["nombreDocente"]) ?></td>
                                <td><?= htmlspecialchars($incidencia["grupo"]) ?></td>
                                <td><?= htmlspecialchars($incidencia["nombreAlumno"] ?? "") ?></td>
                                <td><?= htmlspecialchars($incidencia["descripcion"]) ?></td>
                                <td><?= htmlspecialchars($incidencia["estado"]) ?></td>
                                <td><form action="procesarAsignacionIncidencia.php" method="post" class="d-flex gap-1">
                                    <input type="hidden" name="idIncidencia" value="<?= htmlspecialchars($incidencia["idIncidencia"]) ?>">
                                    <select name="estado" class="form-select form-select-sm"><option>PENDIENTE</option><option>EN PROCESO</option><option>RESUELTO</option></select>
                                    <button class="btn btn-sm btn-primary">Gestionar</button>
                                </form></td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </section>
        </section>
    </main>
    <footer class="sgrsi-footer text-light mt-auto py-3"><p class="text-center mb-0">© 2026 SGRSI</p></footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
