<?php

session_start();

if (!isset($_SESSION["cedula"])) {
    header("Location: ../login.php?error=sin_sesion");
    exit;
}

if ($_SESSION["solicitante"] !== true) {
    header("Location: ../login.php?error=no_autorizado");
    exit;
}

require_once __DIR__ . "/../../app/vista/solicitante/user.php";
