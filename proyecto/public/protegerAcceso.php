<?php

function cargarVistaProtegida(string $rol, string $vista): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["cedula"])) {
        header("Location: ../login.php?error=sin_sesion");
        exit;
    }

    if ($_SESSION[$rol] !== true) {
        header("Location: ../login.php?error=no_autorizado");
        exit;
    }

    require_once $vista;
}
