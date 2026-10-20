<?php

class Token
{
    public static function generarTokenCSRF(): string
    {
        if (!isset($_SESSION['csrfToken'])) $_SESSION['csrfToken'] = bin2hex(random_bytes(32));
        return $_SESSION['csrfToken'];
    }

    public static function verificarCsrf(): void
    {
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!isset($_SESSION['csrfToken']) || !hash_equals($_SESSION['csrfToken'], $token)) {
            throw new ErrorHttp('El token de seguridad es inválido o ya no está disponible. Recargue la página e intente nuevamente', 403);
        }
    }
}
