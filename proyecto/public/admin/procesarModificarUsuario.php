<?php

require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../protegerAcceso.php";

session_start();

if (!isset($_SESSION["cedula"])) {
    header("Location: ../login.php?error=sin_sesion");
    exit;
}

if ($_SESSION["administrador"] !== true) {
    header("Location: ../login.php?error=no_autorizado");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: usuarios.php?error=peticion");
    exit;
}
validarTokenCsrf();

require_once RUTA_CONTROLADOR . "/procesarModificarUsuario.php";
