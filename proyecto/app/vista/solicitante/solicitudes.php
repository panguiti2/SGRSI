<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/formularios.css">

</head>

<body class="d-flex flex-column min-vh-100 sgrsi-app" id="inicio">

    <header class="navbar navbar-expand-md navbar-dark sgrsi-navbar sticky-top">
        <section class="container-fluid">
            <a class="navbar-brand fw-bold" href="inicio.php"><img src="../assets/img/logoITI.png" alt="Logo ITI" class="sgrsi-navbar-logo">SGRSI</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal"
                aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <nav class="collapse navbar-collapse" id="menuPrincipal">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="inicio.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="solicitudes.php">Solicitudes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="incidencias.php">Incidencias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="registroUso.php">Registro de uso</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../cerrarSesion.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </nav>
        </section>
    </header>

    <main class="container-xl flex-grow-1 p-3 p-md-4">

        <section class="seccionFormulario bg-white rounded p-3 p-md-4 mb-4">
            <h1 class="h3 mb-4">Solicitar servicio</h1>

            <form action="procesarAltaSolicitud.php" method="post" id="formularioSolicitud">
                <input type="hidden" name="csrfToken" value="<?= htmlspecialchars($_SESSION["csrfToken"]) ?>">
                <fieldset>
                    <legend class="visually-hidden">Datos de la solicitud</legend>

                    <section class="row g-3 mb-4 ">
                        <div class="col-12 col-md-6 cajaEntradaDeDatos">
                            <label for="turno" class="form-label">Turno</label>
                            <select id="turno" name="turno" class="form-select" required>
                                <option value="" disabled selected>Seleccione</option>
                                <?php foreach ($turnos as $turno): ?>
                                    <option value="<?= htmlspecialchars($turno["codigo"]) ?>">
                                        <?= htmlspecialchars($turno["nombre"]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 cajaEntradaDeDatos">
                            <label for="nombreDocente" class="form-label">Docente</label>
                            <input type="text" id="nombreDocente" name="nombreDocente" class="form-control" required
                                placeholder="Ingrese su nombre">
                        </div>

                        <div class="col-12 col-md-6 cajaEntradaDeDatos">
                            <label for="asignatura" class="form-label">Asignatura</label>
                            <input type="text" id="asignatura" name="asignatura" class="form-control"
                                placeholder="Nombre de la asignatura" required>
                        </div>

                        <div class="col-12 col-md-6 cajaEntradaDeDatos">
                            <label for="grupo" class="form-label">Grupo</label>
                            <input type="text" id="grupo" name="grupo" class="form-control" placeholder="Ej.: 3° BD" required>
                        </div>

                        <div class="col-12 col-md-6 cajaEntradaDeDatos">
                            <label for="fechaEsperada" class="form-label">Fecha y hora esperada</label>
                            <input type="datetime-local" id="fechaEsperada" name="fechaEsperada" class="form-control" required>
                        </div>

                        <div class="col-12 col-md-6 cajaEntradaDeDatos">
                            <label for="tipoServicio" class="form-label">Tipo de servicio</label>
                            <select id="tipoServicio" name="tipoServicio" class="form-select" required>
                                <option value="" disabled selected>Seleccione</option>
                                <?php foreach ($tiposServicio as $tipoServicio): ?>
                                    <option value="<?= htmlspecialchars($tipoServicio["codigo"]) ?>">
                                        <?= htmlspecialchars($tipoServicio["nombre"]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 cajaEntradaDeDatos">
                            <label for="idLaboratorio" class="form-label">Laboratorio</label>
                            <select id="idLaboratorio" name="idLaboratorio" class="form-select" required>
                                <option value="" disabled selected>Seleccione el laboratorio</option>
                                <?php foreach ($laboratorios as $laboratorio): ?>
                                    <option value="<?= htmlspecialchars($laboratorio["idLaboratorio"]) ?>">
                                        <?= htmlspecialchars($laboratorio["nombre"]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 cajaEntradaDeDatos">
                            <label for="numeroDispositivo" class="form-label">Número de dispositivo</label>
                            <select id="numeroDispositivo" name="numeroDispositivo" class="form-select" disabled required>
                                <option value="" selected>Primero seleccione el laboratorio</option>
                            </select>
                        </div>

                        <div class="col-12 cajaTextarea">
                            <label for="descripcion" class="form-label">Descripción / uso en clase</label>
                            <textarea id="descripcion" name="descripcion" class="form-control" rows="4" required
                                placeholder="Describa su solicitud.."></textarea>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Solicitar servicio</button>
                        </div>
                    </section>
                </fieldset>
            </form>
        </section>

        <section class="bg-white rounded p-3 p-md-4 panelTabla">
            <h2 class="h4 mb-3">Mis solicitudes</h2>
            <section class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0 small">
                    <thead class="table-light"><tr><th>ID</th><th>Apertura</th><th>Fecha esperada</th><th>Cierre</th><th>Grupo</th><th>Dispositivo</th><th>Tipo</th><th>Estado</th></tr></thead>
                    <tbody>
                        <?php if (empty($solicitudes)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">Aún no registraste solicitudes.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($solicitudes as $solicitud): ?>
                                <tr>
                                    <td><?= htmlspecialchars($solicitud["idSolicitud"]) ?></td>
                                    <td><?= htmlspecialchars($solicitud["fechaApertura"]) ?></td>
                                    <td><?= htmlspecialchars($solicitud["fechaEsperada"] ?? "Sin fecha") ?></td>
                                    <td><?= htmlspecialchars($solicitud["fechaCierre"] ?? "Pendiente") ?></td>
                                    <td><?= htmlspecialchars($solicitud["grupo"]) ?></td>
                                    <td><?= htmlspecialchars($solicitud["idLaboratorio"] . " / " . $solicitud["numeroDispositivo"]) ?></td>
                                    <td><?= htmlspecialchars($solicitud["tipoServicio"]) ?></td>
                                    <td><?= htmlspecialchars($solicitud["estado"]) ?></td>
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
</body>

</html>

