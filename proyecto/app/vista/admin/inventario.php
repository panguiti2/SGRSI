<?php

$codigoError = $_GET["error"] ?? "";
$mensajesError = [
    "peticion" => "La petición no es válida.",
    "campos_vacios" => "Debe completar todos los campos del dispositivo.",
    "datos_incorrectos" => "Los datos del dispositivo no son válidos.",
    "conexion" => "No se pudo establecer conexión con la base de datos.",
    "error_dispositivo" => "No se pudo registrar el dispositivo.",
    "dispositivo_en_uso" => "No se puede eliminar el dispositivo porque está asociado a un ticket.",
    "exito" => "El dispositivo se registró exitosamente."
];

$error = $mensajesError[$codigoError] ?? "";
$mensajeExito = ($_GET["exito"] ?? "") === "dispositivo" ? "El dispositivo se registró exitosamente." : "";

if (($_GET["exito"] ?? "") === "dispositivo_modificado") {
    $mensajeExito = "El dispositivo se modificó exitosamente.";
}

if (($_GET["exito"] ?? "") === "dispositivo_eliminado") {
    $mensajeExito = "El dispositivo se eliminó exitosamente.";
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/formularios.css">
</head>

<body class="d-flex flex-column min-vh-100 sgrsi-app">

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
                        <a class="nav-link" href="usuarios.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="incidencias.php">Incidencias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="metricas.php">Métricas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="inventario.php">Inventario</a>
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
                <h2 class="h4 m-0">Datos de dispositivo</h2>
                <section class="d-flex flex-column flex-sm-row align-items-sm-center gap-2">
                    <button class="btn btn-success px-3" id="btnAltaDispositivo" type="button">
                        Alta dispositivo
                    </button>
                    <form class="d-flex flex-column flex-sm-row gap-2">
                        <label class="visually-hidden" for="ordenInventarioAdmin">Ordenar por</label>
                        <select class="form-select form-select-sm" id="ordenInventarioAdmin">
                            <option value="" disabled selected>Seleccione</option>
                            <option>Laboratorio</option>
                        </select>
                        <button class="btn btn-primary btn-sm" type="submit">Ordenar</button>
                        <button class="btn btn-secondary btn-sm" type="button">Historial</button>
                    </form>
                </section>
            </section>


            <section class="table-responsive panelTabla">
                <table class="table table-bordered table-hover table-sm mb-0 small">
                    <caption class="d-none d-md-table-caption">Listado de dispositivos registrados</caption>
                    <thead class="table-light">
                        <tr>
                            <th >Laboratorio</th>
                            <th >N° Dispositivo</th>
                            <th >Modificaciones</th>
                            <th >Estado</th>
                            <th >Ultimo cambio</th>
                            <th >Operaciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTablaDispositivos">
                     <?php foreach ($dispositivos as $dispositivo): ?>
                            <tr>
                                <td><?= htmlspecialchars($dispositivo["laboratorio"]) ?></td>
                                <td><?= htmlspecialchars($dispositivo["numeroDispositivo"]) ?></td>
                                <td><?= htmlspecialchars($dispositivo["modificaciones"]) ?></td>
                                <td><?= htmlspecialchars($dispositivo["estado"]) ?></td>
                                <td><?= htmlspecialchars($dispositivo["ultimoCambio"]) ?></td>
                                <td>
                                    <div class="cajaOperaciones">
                                        <button type="button" class="btnOperacion btnModificarDispositivo"
                                            data-laboratorio="<?= htmlspecialchars($dispositivo["idLab"]) ?>"
                                            data-numero="<?= htmlspecialchars($dispositivo["numeroDispositivo"]) ?>"
                                            data-modificaciones="<?= htmlspecialchars($dispositivo["modificaciones"]) ?>"
                                            data-estado="<?= $dispositivo["estado"] === "Activo" ? "1" : "0" ?>"
                                            data-ultimo-cambio="<?= htmlspecialchars($dispositivo["ultimoCambio"]) ?>">Modificar</button>
                                        <form action="procesarBajaDispositivo.php" method="post" class="formEliminarDispositivo">
                                            <input type="hidden" name="csrfToken" value="<?= htmlspecialchars($_SESSION["csrfToken"]) ?>">
                                            <input type="hidden" name="idLab" value="<?= htmlspecialchars($dispositivo["idLab"]) ?>">
                                            <input type="hidden" name="numeroDispositivo" value="<?= htmlspecialchars($dispositivo["numeroDispositivo"]) ?>">
                                            <button type="submit" class="btnOperacion btnDesactivar">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>


                    </tbody>
                </table>
            </section>
        </section>

    </main>


    <dialog class="dialogAltaDispositivo seccionFormulario w-100 p-0 rounded-3 border-0" id="miDialogo"
        style="max-width: 600px;">
        <button class="btn-close position-absolute top-0 end-0 m-2" id="btnCerrarAltaDispositivo" type="button"
            aria-label="Cerrar"></button>

        <form action="procesarAltaDispositivo.php" method="post" id="formularioAltaDispositivo" class="p-4">
            <input type="hidden" name="csrfToken" value="<?= htmlspecialchars($_SESSION["csrfToken"]) ?>">
            <fieldset>
                <legend class="h4 mb-4" id="tituloFormularioDispositivo">Gestión de dispositivos</legend>

                <input type="hidden" id="idLabFijo" name="idLabFijo">

                <section class="row g-3 mb-4">

                    <div class="col-12 col-md-6 cajaEntradaDeDatos">
                        <label for="numeroDispositivo" class="form-label">N° Dispositivo</label>
                        <input type="text" id="numeroDispositivo" name="numeroDispositivo" class="form-control"
                            placeholder="Ej.: PC-001" required>
                    </div>

                    <div class="col-12 col-md-6 cajaEntradaDeDatos">
                        <label for="laboratorio" class="form-label">Laboratorio</label>
                        <select id="idLab" name="idLab" class="form-select" required>
                            <option value="" disabled selected>Seleccione laboratorio</option>
                            <?php foreach ($laboratorios as $laboratorio): ?>
                                <option value="<?= htmlspecialchars($laboratorio["idLaboratorio"]) ?>">
                                    <?= htmlspecialchars($laboratorio["nombre"]) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-6 cajaEntradaDeDatos" id="grupoModificaciones">
                        <label for="modificaciones" class="form-label">Modificaciones</label>
                        <select id="modificaciones" name="modificaciones" class="form-select" required>
                            <?php foreach ($modificacionesDispositivo as $modificacion): ?>
                                <option value="<?= htmlspecialchars($modificacion["codigo"]) ?>">
                                    <?= htmlspecialchars($modificacion["nombre"]) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-6 cajaEntradaDeDatos">
                        <label for="estado" class="form-label">Estado</label>
                        <select id="estado" name="estado" class="form-select" required>
                            <option disabled selected>Seleccione estado</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>


                    <div class="col-12 col-md-6 cajaEntradaDeDatos">
                        <label for="ultimoCambio" class="form-label">Ultimo cambio</label>
                        <input type="datetime-local" id="ultimoCambio" name="ultimoCambio" class="form-control"
                            required></input>
                    </div>
                </section>

                <button type="submit" class="btn btn-primary w-100" id="botonGuardarDispositivo">Guardar dispositivo</button>
            </fieldset>
        </form>
    </dialog>


    <footer class="sgrsi-footer text-light mt-auto py-3 py-md-4">
        <address class="d-flex flex-column flex-md-row justify-content-center gap-2 gap-md-3 text-center mb-2">
            <a href="http://instagram.com" class="text-light text-decoration-none">@SGRSI</a>
            <a href="tel:+29043586" class="text-light text-decoration-none">+29043586</a>
            <a href="mailto:asistentesiti@gmail.com" class="text-light text-decoration-none">asistentesiti@gmail.com</a>
        </address>
        <p class="text-center mb-0">© 2026 SGRSI</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/administrador.js"></script>
</body>

</html>



