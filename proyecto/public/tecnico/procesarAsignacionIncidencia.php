<?php
require_once __DIR__ . "/../protegerAcceso.php";
verificarRolPublico("tecnico");
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: incidencias.php?error=peticion");
    exit;
}
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/procesarAsignacionIncidencia.php";
