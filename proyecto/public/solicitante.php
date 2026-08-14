<?php

session_start();

if (!isset($_SESSION["cedula"])) {
    header("Location: login.php?error=sin_sesion");
    exit;
}

if (!isset($_SESSION["solicitante"]) || $_SESSION["solicitante"] !== true) {
    header("Location: login.php?error=no_autorizado");
    exit;
}

require_once RUTA_VISTA . "/user.php";
exit;
