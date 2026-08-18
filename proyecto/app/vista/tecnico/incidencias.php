<?php

$codigoError = $_GET["error"] ?? "";
$mensajesError = [
    "datos_incorrectos" => "Los datos de gestión no son válidos.",
    "asignacion" => "No se pudo gestionar la incidencia."
];
$error = $mensajesError[$codigoError] ?? "";
$mensajeExito = ($_GET["exito"] ?? "") === "asignacion"
    ? "La incidencia se gestionó exitosamente."
    : "";
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
                    <li class="nav-item"><a class="nav-link" href="registrosUso.php">Registros de uso</a></li>
                    <li class="nav-item"><a class="nav-link" href="../cerrarSesion.php">Cerrar sesión</a></li>
                </ul>
            </nav>
        </section>
    </header>
    <main class="container-xl flex-grow-1 p-3 p-md-4">
        <?php if ($mensajeExito !== ""): ?>
            <p class="alert alert-success"><?= htmlspecialchars($mensajeExito) ?></p>
        <?php endif; ?>
        <?php if ($error !== ""): ?>
            <p class="alert alert-danger"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <section class="bg-white rounded p-3 p-md-4 panelTabla">
            <h1 class="h3 mb-3">Incidencias registradas</h1>
            <section class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0 small">
                    <thead class="table-light">
                        <tr><th>ID</th><th>Solicitante</th><th>Apertura</th><th>Gestión</th><th>Cierre</th><th>Turno</th><th>Docente</th><th>Grupo</th><th>Asignatura</th><th>Laboratorio</th><th>Dispositivo</th><th>Reportó alumno</th><th>Alumno</th><th>Descripción</th><th>Diagnóstico</th><th>Solución</th><th>Estado</th><th>Operación</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($incidencias)): ?>
                            <tr><td colspan="18" class="text-center text-muted py-3">No hay incidencias registradas.</td></tr>
                        <?php else: foreach ($incidencias as $incidencia): ?>
                            <tr>
                                <td><?= htmlspecialchars($incidencia["idIncidencia"]) ?></td>
                                <td><?= htmlspecialchars($incidencia["cedulaSolicitante"]) ?></td>
                                <td><?= htmlspecialchars($incidencia["fechaApertura"]) ?></td>
                                <td><?= htmlspecialchars($incidencia["fechaGestion"] ?? "Pendiente") ?></td>
                                <td><?= htmlspecialchars($incidencia["fechaCierre"] ?? "Pendiente") ?></td>
                                <td><?= htmlspecialchars($incidencia["turno"]) ?></td>
                                <td><?= htmlspecialchars($incidencia["nombreDocente"]) ?></td>
                                <td><?= htmlspecialchars($incidencia["grupo"]) ?></td>
                                <td><?= htmlspecialchars($incidencia["asignatura"]) ?></td>
                                <td><?= htmlspecialchars($incidencia["idLaboratorio"] ?? "No especificado") ?></td>
                                <td><?= htmlspecialchars($incidencia["numeroDispositivo"] ?? "No especificado") ?></td>
                                <td><?= $incidencia["reportoAlumno"] ? "Sí" : "No" ?></td>
                                <td><?= htmlspecialchars($incidencia["nombreAlumno"] ?? "No corresponde") ?></td>
                                <td><?= htmlspecialchars($incidencia["descripcion"]) ?></td>
                                <td><?= htmlspecialchars($incidencia["diagnostico"] ?? "Pendiente") ?></td>
                                <td><?= htmlspecialchars($incidencia["solucion"] ?? "Pendiente") ?></td>
                                <td><?= htmlspecialchars($incidencia["estado"]) ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary btnGestionarIncidencia"
                                        data-id="<?= htmlspecialchars($incidencia["idIncidencia"]) ?>"
                                        data-estado="<?= htmlspecialchars($incidencia["estado"]) ?>"
                                        data-diagnostico="<?= htmlspecialchars($incidencia["diagnostico"] ?? "") ?>"
                                        data-solucion="<?= htmlspecialchars($incidencia["solucion"] ?? "") ?>">
                                        Gestionar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </section>
        </section>

        <dialog id="dialogGestionarIncidencia" class="seccionFormulario w-100 p-0 rounded-3 border-0" style="max-width: 600px;">
            <form action="procesarAsignacionIncidencia.php" method="post" class="p-4" id="formularioGestionarIncidencia">
                <input type="hidden" name="csrfToken" value="<?= htmlspecialchars($_SESSION["csrfToken"]) ?>">
                <input type="hidden" name="idIncidencia" id="idIncidenciaGestionar">
                <fieldset>
                    <section class="d-flex justify-content-between align-items-start gap-3 mb-4">
                        <legend class="h4 mb-0">Gestionar incidencia</legend>
                        <button class="btn-close" type="button" id="btnCerrarGestionIncidencia" aria-label="Cerrar"></button>
                    </section>
                    <section class="mb-3">
                        <label for="diagnosticoIncidencia" class="form-label">Diagnóstico</label>
                        <textarea id="diagnosticoIncidencia" name="diagnostico" class="form-control" rows="3"
                            placeholder="Describa el diagnóstico realizado"></textarea>
                    </section>
                    <section class="mb-3">
                        <label for="solucionIncidencia" class="form-label">Solución</label>
                        <textarea id="solucionIncidencia" name="solucion" class="form-control" rows="3"
                            placeholder="Describa la solución aplicada"></textarea>
                    </section>
                    <section class="mb-4">
                        <label for="estadoIncidencia" class="form-label">Estado</label>
                        <select id="estadoIncidencia" name="estado" class="form-select" required>
                            <?php foreach ($estadosTicket as $estadoTicket): ?>
                                <option value="<?= htmlspecialchars($estadoTicket["codigo"]) ?>">
                                    <?= htmlspecialchars($estadoTicket["nombre"]) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </section>
                    <button type="submit" class="btn btn-primary w-100">Guardar gestión</button>
                </fieldset>
            </form>
        </dialog>
    </main>
    <footer class="sgrsi-footer text-light mt-auto py-3"><p class="text-center mb-0">© 2026 SGRSI</p></footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const dialogGestionarIncidencia = document.getElementById("dialogGestionarIncidencia");
        const idIncidenciaGestionar = document.getElementById("idIncidenciaGestionar");
        const diagnosticoIncidencia = document.getElementById("diagnosticoIncidencia");
        const solucionIncidencia = document.getElementById("solucionIncidencia");
        const estadoIncidencia = document.getElementById("estadoIncidencia");

        const actualizarCamposCierre = () => {
            const esResuelto = estadoIncidencia.value === "RESUELTO";
            diagnosticoIncidencia.required = esResuelto;
            solucionIncidencia.required = esResuelto;
        };

        document.querySelectorAll(".btnGestionarIncidencia").forEach((boton) => {
            boton.addEventListener("click", () => {
                idIncidenciaGestionar.value = boton.dataset.id;
                estadoIncidencia.value = boton.dataset.estado;
                diagnosticoIncidencia.value = boton.dataset.diagnostico;
                solucionIncidencia.value = boton.dataset.solucion;
                actualizarCamposCierre();
                dialogGestionarIncidencia.showModal();
            });
        });

        estadoIncidencia.addEventListener("change", actualizarCamposCierre);
        document.getElementById("btnCerrarGestionIncidencia").addEventListener("click", () => dialogGestionarIncidencia.close());
    </script>
</body>
</html>
