<?php
$codigoError = $_GET["error"] ?? "";
$mensajesError = [
    "peticion" => "La petición no es válida.",
    "datos_incorrectos" => "Debe completar los datos del registro correctamente.",
    "error_registro" => "No se pudo guardar el registro de uso."
];
$error = $mensajesError[$codigoError] ?? "";
$mensajeExito = ($_GET["exito"] ?? "") === "registro"
    ? "El registro de uso se guardó correctamente."
    : "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de uso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/formularios.css">
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
                    <li class="nav-item"><a class="nav-link active" href="registroUso.php">Registro de uso</a></li>
                    <li class="nav-item"><a class="nav-link" href="../cerrarSesion.php">Cerrar sesión</a></li>
                </ul>
            </nav>
        </section>
    </header>
    <main class="container-xl flex-grow-1 p-3 p-md-4">
        <?php if ($mensajeExito !== ""): ?><p class="alert alert-success"><?= htmlspecialchars($mensajeExito) ?></p><?php endif; ?>
        <?php if ($error !== ""): ?><p class="alert alert-danger"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <section class="seccionFormulario bg-white rounded p-3 p-md-4 mb-4">
            <h1 class="h3 mb-4">Registro de uso de laboratorio</h1>
            <form action="procesarAltaRegistroUso.php" method="post" id="formularioRegistroUso">
                <input type="hidden" name="csrfToken" value="<?= htmlspecialchars($_SESSION["csrfToken"]) ?>">
                <section class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="idLaboratorio" class="form-label">Laboratorio</label>
                        <select id="idLaboratorio" name="idLaboratorio" class="form-select" required>
                            <option value="" disabled selected>Seleccione un laboratorio</option>
                            <?php foreach ($laboratorios as $laboratorio): ?>
                                <option value="<?= htmlspecialchars($laboratorio["idLaboratorio"]) ?>"><?= htmlspecialchars($laboratorio["nombre"]) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="turno" class="form-label">Turno</label>
                        <select id="turno" name="turno" class="form-select" required>
                            <option value="" disabled selected>Seleccione un turno</option>
                            <?php foreach ($turnos as $turno): ?>
                                <option value="<?= htmlspecialchars($turno["codigo"]) ?>"><?= htmlspecialchars($turno["nombre"]) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="fechaHora" class="form-label">Fecha y hora</label>
                        <input type="datetime-local" id="fechaHora" name="fechaHora" class="form-control" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="nombreDocente" class="form-label">Docente</label>
                        <input type="text" id="nombreDocente" name="nombreDocente" class="form-control" placeholder="Ingrese el nombre del docente" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="grupo" class="form-label">Grupo</label>
                        <input type="text" id="grupo" name="grupo" class="form-control" placeholder="Ej.: 3° BD" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="asignatura" class="form-label">Asignatura</label>
                        <input type="text" id="asignatura" name="asignatura" class="form-control" placeholder="Nombre de la asignatura" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <p class="form-label mb-2">¿Se usaron máquinas?</p>
                        <section class="d-flex gap-3">
                            <div class="form-check"><input class="form-check-input" type="radio" name="usoMaquinas" value="SI" id="usoSi" required><label class="form-check-label" for="usoSi">Sí</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" name="usoMaquinas" value="NO" id="usoNo"><label class="form-check-label" for="usoNo">No</label></div>
                        </section>
                    </div>
                    <div class="col-12 col-md-6">
                        <p class="form-label mb-2">¿Hubo incidencias?</p>
                        <section class="d-flex gap-3">
                            <div class="form-check"><input class="form-check-input" type="radio" name="huboIncidencias" value="SI" id="incidenciaSi" required><label class="form-check-label" for="incidenciaSi">Sí</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" name="huboIncidencias" value="NO" id="incidenciaNo"><label class="form-check-label" for="incidenciaNo">No</label></div>
                        </section>
                    </div>
                    <div class="col-12"><button type="submit" class="botonOperacion">Guardar registro</button></div>
                </section>
            </form>
        </section>
        <section class="bg-white rounded p-3 p-md-4 panelTabla">
            <h2 class="h4 mb-3">Mis registros</h2>
            <section class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0">
                    <thead class="table-light"><tr><th>Laboratorio</th><th>Turno</th><th>Fecha y hora</th><th>Docente</th><th>Grupo</th><th>Asignatura</th><th>Máquinas</th><th>Incidencias</th></tr></thead>
                    <tbody>
                        <?php foreach ($registrosUso as $registro): ?>
                            <tr><td><?= htmlspecialchars($registro["laboratorio"]) ?></td><td><?= htmlspecialchars($registro["turno"]) ?></td><td><?= htmlspecialchars($registro["fechaHora"]) ?></td><td><?= htmlspecialchars($registro["nombreDocente"]) ?></td><td><?= htmlspecialchars($registro["grupo"]) ?></td><td><?= htmlspecialchars($registro["asignatura"]) ?></td><td><?= $registro["usoMaquinas"] ? "Sí" : "No" ?></td><td><?= $registro["huboIncidencias"] ? "Sí" : "No" ?></td></tr>
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
