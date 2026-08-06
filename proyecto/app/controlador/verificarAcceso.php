<?php

function verificarAcceso(string $rolRequerido): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["cedula"])) {
        header("Location: login.php?error=sin_sesion");
        exit;
    }

    if (!isset($_SESSION[$rolRequerido]) || $_SESSION[$rolRequerido] !== true) {
        header("Location: login.php?error=no_autorizado");
        exit;
    }
}
