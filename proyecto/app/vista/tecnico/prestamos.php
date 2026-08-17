<?php
$rolRequerido = "tecnico";
require_once __DIR__ . "/../../../app/controlador/verificarAcceso.php";
verificarAcceso($rolRequerido);

$codigoError = $_GET["error"] ?? "";
$mensajesError = [
    "peticion" => "La petición no es válida.",
    "campos_vacios" => "Debe completar todos los campos del préstamo.",
    "datos_incorrectos" => "Los datos del préstamo no son válidos.",
    "error_prestamo" => "No se pudo registrar el préstamo.",
    "error_devolucion" => "No se pudo registrar la devolución."
];

$error = $mensajesError[$codigoError] ?? "";
$mensajeExito = ($_GET["exito"] ?? "") === "prestamo" ? "El préstamo se registró exitosamente." : "";

if (($_GET["exito"] ?? "") === "devolucion") {
    $mensajeExito = "La devolución se registró y el préstamo fue cerrado.";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prestamos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/formularios.css">
</head>

<body class="d-flex flex-column min-vh-100 sgrsi-app" id="inicio">

    <header class="navbar navbar-expand-md navbar-dark sgrsi-navbar sticky-top">
        <section class="container-fluid">
            <a class="navbar-brand fw-bold" href="inicio.php"><img src="../assets/img/logoITI.png" alt="Logo ITI"
                    class="sgrsi-navbar-logo">SGRSI</a>

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
                        <a class="nav-link" href="solicitudes.php">Solicitudes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="incidencias.php">Incidencias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="metricas.php">Métricas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="inventario.php">Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="prestamos.php">Préstamos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../cerrarSesion.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </nav>
        </section>
    </header>

    <main class="flex-grow-1 p-2 p-md-3 p-lg-4">

        <?php if ($mensajeExito !== ""): ?>
            <p class="alert alert-success"><?= htmlspecialchars($mensajeExito) ?></p>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <p class="alert alert-danger"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <section class="bg-white rounded mb-4 p-3 p-md-4">
            <section class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                <h2 class="h4 m-0">Préstamos</h2>
            </section>
            <button class="botonOperacion" id="btnRegistrarPrestamo"> Registrar Préstamo</button>
       
       
            <section class="table-responsive panelTabla">
                <table class="table table-bordered table-hover table-sm mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th >CI</th>
                            <th >Turno</th>
                            <th >Nombre</th>
                            <th >Laptop</th>
                            <th >Retiro</th>
                            <th >Devolución esperada</th>
                            <th >Devolución real</th>
                            <th >Estado</th>
                            <th >Operaciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTablaPrestamos">
                        <?php foreach ($prestamos as $prestamo): ?>
                            <tr>
                                <td><?= htmlspecialchars($prestamo["cedulaSolicitante"]) ?></td>
                                <td><?= htmlspecialchars($prestamo["turno"]) ?></td>
                                <td><?= htmlspecialchars($prestamo["nombreSolicitante"]) ?></td>
                                <td><?= htmlspecialchars($prestamo["numeroLaptop"]) ?></td>
                                <td><?= htmlspecialchars($prestamo["fechaRetiro"]) ?></td>
                                <td><?= htmlspecialchars($prestamo["fechaDevolucion"]) ?></td>
                                <td><?= htmlspecialchars($prestamo["fechaDevolucionReal"] ?? "Pendiente") ?></td>
                                <td><?= htmlspecialchars($prestamo["estado"]) ?></td>
                                <td>
                                    <?php if ($prestamo["estado"] === "ACTIVO"): ?>
                                        <button type="button" class="btnOperacion btnRegistrarDevolucion"
                                            data-id="<?= htmlspecialchars($prestamo["idPrestamo"]) ?>">Registrar devolución</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </section>

        <dialog class="dialogPrestamos seccionFormulario w-100 p-0 rounded-3 border-0" style="max-width: 600px;">
            <button class="btn-close position-absolute top-0 end-0 m-2" id="btnCerrarRegistarPrestamo" type="button"
                aria-label="Cerrar"></button>

            <form action="procesarAltaPrestamo.php" method="post" class="formPrestamo p-4" id="formularioPrestamos">
                <fieldset>
                    <legend class="h4 mb-4">Solicitud de Préstamo de Equipo</legend>

                    <section class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="cedulaSolicitante" class="form-label">Cédula</label>
                            <input type="text" id="cedulaSolicitante" name="cedulaSolicitante" class="form-control"
                                pattern="[1-9][0-9]{7}" maxlength="8" required placeholder="Ej.: 12345678">
                        </div>

                        <div class="col-12 col-md-6 cajaEntradaDeDatos">
                            <label for="turno" class="form-label">Turno</label>
                            <select id="turno" name="turno" class="form-select" required>
                                <option value="" disabled selected>Seleccione</option>
                                <option value="Matutino">Matutino</option>
                                <option value="Vespertino">Vespertino</option>
                                <option value="Nocturno">Nocturno</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 cajaEntradaDeDatos">
                            <label for="nombreSolicitante" class="form-label">Nombre</label>
                            <input type="text" id="nombreSolicitante" name="nombreSolicitante" class="form-control"
                                required placeholder="Nombre completo de quien solicita">
                        </div>

                        <div class="col-12 col-md-6 cajaEntradaDeDatos">
                            <label for="numeroLaptop" class="form-label">N° de laptop</label>
                            <input type="text" id="numeroLaptop" name="numeroLaptop" class="form-control"
                                placeholder="Ej.: LT-001" required>
                        </div>

                        <div class="col-12 col-md-6 cajaEntradaDeDatos">
                            <label for="retiro" class="form-label">Fecha y hora de retiro</label>
                            <input type="datetime-local" id="retiro" name="retiro" class="form-control" required>
                        </div>

                        <div class="col-12 cajaEntradaDeDatos">
                            <label for="devolucion" class="form-label">Fecha y hora esperada de devolución</label>
                            <input type="datetime-local" id="devolucion" name="devolucion" class="form-control" required>
                        </div>

                    </section>

                    <button type="submit" class="btn btn-primary w-100">Guardar</button>
                </fieldset>
            </form>
        </dialog>

        <dialog class="dialogDevolucionPrestamo seccionFormulario w-100 p-0 rounded-3 border-0" style="max-width: 600px;">
            <button class="btn-close position-absolute top-0 end-0 m-2" id="btnCerrarDevolucionPrestamo" type="button"
                aria-label="Cerrar"></button>

            <form action="procesarDevolucionPrestamo.php" method="post" id="formularioDevolucionPrestamo" class="p-4">
                <fieldset>
                    <legend class="h4 mb-4">Registrar devolución</legend>
                    <input type="hidden" name="idPrestamo" id="idPrestamoDevolucion">
                    <div class="mb-4 cajaEntradaDeDatos">
                        <label for="fechaDevolucion" class="form-label">Fecha y hora de devolución</label>
                        <input type="datetime-local" id="fechaDevolucion" name="fechaDevolucion" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Cerrar préstamo</button>
                </fieldset>
            </form>
        </dialog>

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
    <script src="../assets/js/tecnico.js"></script>
</body>

</html>


