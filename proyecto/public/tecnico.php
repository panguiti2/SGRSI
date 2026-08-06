<?php

session_start();

if (!isset($_SESSION["cedula"])) {
    header("Location: login.php?error=sin_sesion");
    exit;
}

if (!isset($_SESSION["tecnico"]) || $_SESSION["tecnico"] !== true) {
    header("Location: login.php?error=no_autorizado");
    exit;
}

require_once __DIR__ . "/../app/vista/tecnico/tecnico.php";
