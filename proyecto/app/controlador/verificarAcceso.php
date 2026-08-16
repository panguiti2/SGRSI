<?php

/**
 * Verifica que la sesión pertenezca al rol requerido y redirige si no corresponde.
 * @param string $rolRequerido Rol necesario para acceder al recurso.
 * @return void
 */
function verificarAcceso(string $rolRequerido): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["cedula"])) {
        header("Location: ../login.php?error=sin_sesion");
        exit;
    }

    if (!isset($_SESSION[$rolRequerido]) || $_SESSION[$rolRequerido] !== true) {
        header("Location: ../login.php?error=no_autorizado");
        exit;
    }
}
