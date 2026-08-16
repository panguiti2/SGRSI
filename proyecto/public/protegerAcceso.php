<?php

/**
 * Valida la sesión y carga una vista reservada para el rol indicado.
 * @param string $rol Rol autorizado para acceder a la vista.
 * @param string $vista Ruta del archivo de vista que se debe cargar.
 * @return void
 */
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
