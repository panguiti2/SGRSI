<?php

require_once __DIR__ . "/../protegerAcceso.php";
verificarRolPublico("tecnico");
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: solicitudes.php?error=peticion");
    exit;
}
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/procesarEstadoSolicitud.php";
