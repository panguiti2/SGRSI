<?php

session_start();

// Comprueba que exista una sesión iniciada.
if (!isset($_SESSION["cedula"], $_SESSION["rol"])) {
    header(
        "Location: login.php?error=" .
        urlencode("Debe iniciar sesión para acceder.")
    );
    exit;
}

// Comprueba que el usuario sea administrador.
if ($_SESSION["rol"] !== "administrador") {
    header(
        "Location: login.php?error=" .
        urlencode("No tiene permisos para acceder al panel de administración.")
    );
    exit;
}

// Carga la vista del administrador.
require_once __DIR__ . "/../app/vista/administrador.php";