<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incidencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/formularios.css">
</head>

<body class="d-flex flex-column min-vh-100 sgrsi-app">
    <header class="navbar navbar-expand-md navbar-dark sgrsi-navbar sticky-top">
        <section class="container-fluid">
            <a class="navbar-brand fw-bold" href="inicio.php">
                <img src="../assets/img/logoITI.png" alt="Logo ITI" class="sgrsi-navbar-logo">SGRSI
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal"
                aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>
            <nav class="collapse navbar-collapse" id="menuPrincipal">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="inicio.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="solicitudes.php">Solicitudes</a></li>
                    <li class="nav-item"><a class="nav-link active" href="incidencias.php">Incidencias</a></li>
                    <li class="nav-item"><a class="nav-link" href="registroUso.php">Registro de uso</a></li>
                    <li class="nav-item"><a class="nav-link" href="../cerrarSesion.php">Cerrar sesión</a></li>
                </ul>
            </nav>
        </section>
    </header>

    <main class="container-xl flex-grow-1 p-3 p-md-4">
        <section class="seccionFormulario bg-white rounded p-3 p-md-4 mb-4">
            <h1 class="h3 mb-4">Reportar incidencia</h1>
            <form action="procesarAltaIncidencia.php" method="post" class="row g-3" id="formularioIncidencia">
                <input type="hidden" name="csrfToken" value="<?= htmlspecialchars($_SESSION["csrfToken"]) ?>">
                <section class="col-12 col-md-6 cajaEntradaDeDatos">
                    <label class="form-label" for="turno">Turno</label>
                    <select class="form-select" id="turno" name="turno" required>
                        <option value="" selected disabled>Seleccione</option>
                        <?php foreach ($turnos as $turno): ?>
                            <option value="<?= htmlspecialchars($turno["codigo"]) ?>">
                                <?= htmlspecialchars($turno["nombre"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </section>
                <section class="col-12 col-md-6 cajaEntradaDeDatos">
                    <label class="form-label" for="nombreDocente">Docente</label>
                    <input class="form-control" id="nombreDocente" name="nombreDocente" placeholder="Ingrese su nombre" required>
                </section>
                <section class="col-12 col-md-6 cajaEntradaDeDatos">
                    <label class="form-label" for="grupo">Grupo</label>
                    <input class="form-control" id="grupo" name="grupo" placeholder="Ej.: 3° BD" required>
                </section>
                <section class="col-12 col-md-6 cajaEntradaDeDatos">
                    <label class="form-label" for="asignatura">Asignatura</label>
                    <input class="form-control" id="asignatura" name="asignatura" placeholder="Nombre de la asignatura" required>
                </section>
                <section class="col-12 col-md-6 cajaEntradaDeDatos">
                    <label class="form-label" for="idLaboratorio">Laboratorio</label>
                    <select class="form-select" id="idLaboratorio" name="idLaboratorio" required>
                        <option value="" selected disabled>Seleccione el laboratorio</option>
                        <?php foreach ($laboratorios as $laboratorio): ?>
                            <option value="<?= htmlspecialchars($laboratorio["idLaboratorio"]) ?>">
                                <?= htmlspecialchars($laboratorio["nombre"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </section>
                <section class="col-12 col-md-6 cajaEntradaDeDatos">
                    <label class="form-label" for="numeroDispositivo">Número de dispositivo</label>
                    <select class="form-select" id="numeroDispositivo" name="numeroDispositivo" disabled required>
                        <option value="" selected>Primero seleccione el laboratorio</option>
                    </select>
                </section>
                <fieldset class="col-12 col-md-6 cajaRadio">
                    <legend class="form-label mb-1">¿Reportó un alumno?</legend>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="reportoAlumnoSi" name="reportoAlumno" value="SI" required>
                        <label class="form-check-label" for="reportoAlumnoSi">Sí</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="reportoAlumnoNo" name="reportoAlumno" value="NO">
                        <label class="form-check-label" for="reportoAlumnoNo">No</label>
                    </div>
                </fieldset>
                <section class="col-12 col-md-6 cajaEntradaDeDatos">
                    <label class="form-label" for="nombreAlumno">Nombre del alumno</label>
                    <input class="form-control" id="nombreAlumno" name="nombreAlumno" placeholder="Ingrese el nombre del alumno" disabled>
                </section>
                <section class="col-12 cajaTextarea">
                    <label class="form-label" for="descripcion">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4"
                        placeholder="Describa la incidencia detectada" required></textarea>
                </section>
                <section class="col-12">
                    <button type="submit" class="btn btn-primary w-100">Registrar incidencia</button>
                </section>
            </form>
        </section>

        <section class="bg-white rounded p-3 p-md-4 panelTabla">
            <h2 class="h4 mb-3">Mis incidencias</h2>
            <section class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0 small">
                    <thead class="table-light">
                        <tr><th>ID</th><th>Apertura</th><th>Cierre</th><th>Grupo</th><th>Descripción</th><th>Diagnóstico</th><th>Solución</th><th>Estado</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($incidencias)): ?>
                            <tr><td colspan="8" class="text-center text-muted py-3">Aún no registraste incidencias.</td></tr>
                        <?php else: ?>
                            <?php foreach ($incidencias as $incidencia): ?>
                                <tr>
                                    <td><?= htmlspecialchars($incidencia["idIncidencia"]) ?></td>
                                    <td><?= htmlspecialchars($incidencia["fechaApertura"]) ?></td>
                                    <td><?= htmlspecialchars($incidencia["fechaCierre"] ?? "Pendiente") ?></td>
                                    <td><?= htmlspecialchars($incidencia["grupo"]) ?></td>
                                    <td><?= htmlspecialchars($incidencia["descripcion"]) ?></td>
                                    <td><?= htmlspecialchars($incidencia["diagnostico"] ?? "Pendiente") ?></td>
                                    <td><?= htmlspecialchars($incidencia["solucion"] ?? "Pendiente") ?></td>
                                    <td><?= htmlspecialchars($incidencia["estado"]) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </section>
    </main>

    <footer class="sgrsi-footer text-light mt-auto py-3 py-md-4">
        <address class="d-flex flex-column flex-md-row justify-content-center gap-2 gap-md-3 text-center mb-2">
            <a href="http://instagram.com" class="text-light text-decoration-none">@SGRSI</a>
            <a href="tel:+29043586" class="text-light text-decoration-none">+29043586</a>
            <a href="mailto:asistentesiti@gmail.com" class="text-light text-decoration-none">asistentesiti@gmail.com</a>
        </address>
        <p class="text-center mb-0">© 2026 SGRSI</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.dispositivosFormulario = <?= json_encode($dispositivosFormulario, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    </script>
    <script src="../assets/js/usuario.js"></script>
    <script>
        document.querySelectorAll('input[name="reportoAlumno"]').forEach((opcion) => {
            opcion.addEventListener('change', () => {
                const campo = document.getElementById('nombreAlumno');
                const reportoAlumno = opcion.value === 'SI';
                campo.disabled = !reportoAlumno;
                campo.required = reportoAlumno;
                if (!reportoAlumno) campo.value = '';
            });
        });
    </script>
</body>

</html>
