<?php

class Sesion
{
    public const TABLAS_ROL = [
        'administrador' => 'ADMINISTRADOR',
        'tecnico' => 'TECNICO',
        'solicitante' => 'SOLICITANTE'
    ];

    public static function validarRol(string $rol): void
    {
        Peticion::validarCamposObligatorios([$rol]);
        if (!isset(self::TABLAS_ROL[$rol])) throw new ErrorHttp('El rol no es válido');
    }
    public static function preparar(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax',
                'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
            session_start();
        }
    }

    public static function iniciar(array $usuario): void
    {
        session_regenerate_id(true);
        $_SESSION = ['cedula' => $usuario['cedula']];
        foreach (['administrador', 'tecnico', 'solicitante'] as $rol) {
            $_SESSION[$rol] = (bool) $usuario[$rol];
        }
        Token::generarTokenCSRF();
    }

    public static function verificarRoles(array $roles): void
    {
        if (!isset($_SESSION['cedula'])) throw new ErrorHttp('Acceso denegado: sesión no iniciada', 401);
        foreach ($roles as $rol) {
            if ($_SESSION[$rol] ?? false) return;
        }
        throw new ErrorHttp('Acceso denegado: rol incorrecto', 403);
    }

    public static function datos(): array
    {
        self::verificarRoles(['administrador', 'tecnico', 'solicitante']);
        $roles = array_filter(['administrador', 'tecnico', 'solicitante'], fn($rol) => $_SESSION[$rol] ?? false);
        return ['cedula' => $_SESSION['cedula'], 'roles' => array_values($roles),
            'csrfToken' => Token::generarTokenCSRF()];
    }

    public static function cerrar(): void
    {
        $_SESSION = [];
        $parametros = session_get_cookie_params();
        setcookie(session_name(), '', ['expires' => time() - 42000, 'path' => $parametros['path'],
            'domain' => $parametros['domain'], 'secure' => $parametros['secure'],
            'httponly' => true, 'samesite' => 'Lax']);
        session_destroy();
    }
}
