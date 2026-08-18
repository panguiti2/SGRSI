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

/**
 * Comprueba una sesión y un rol antes de cargar cualquier archivo de app.
 */
function verificarRolPublico(string $rol): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    generarTokenCsrf();

    if (!isset($_SESSION["cedula"])) {
        header("Location: ../login.php?error=sin_sesion");
        exit;
    }

    if (($_SESSION[$rol] ?? false) !== true) {
        header("Location: ../login.php?error=no_autorizado");
        exit;
    }

    generarTokenCsrf();
}

/** Genera el token CSRF de la sesión si todavía no existe. */
function generarTokenCsrf(): void
{
    if (!isset($_SESSION["csrfToken"])) {
        $_SESSION["csrfToken"] = bin2hex(random_bytes(32));
    }
}

/**
 * Rechaza una petición POST cuyo token no coincide con el token de sesión.
 * @param string $rutaError Ruta de retorno ante una solicitud rechazada.
 */
function validarTokenCsrf(): void
{
    $tokenRecibido = $_POST["csrfToken"] ?? "";

    if (!isset($_SESSION["csrfToken"]) || !hash_equals($_SESSION["csrfToken"], $tokenRecibido)) {
        http_response_code(403);
        exit("Solicitud Rechazada...");
    }
}
