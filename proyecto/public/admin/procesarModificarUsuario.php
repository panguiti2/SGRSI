<?php

require_once __DIR__ . "/../../config/config.php";

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

require_once RUTA_CONTROLADOR . "/procesarModificarUsuario.php";
