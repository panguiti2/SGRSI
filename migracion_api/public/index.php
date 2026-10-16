<?php

require_once __DIR__ . '/../app/nucleo/ErrorHttp.php';
require_once __DIR__ . '/../app/vista/RespuestaJson.php';

try {
    if (!isset($_GET['ruta'])) {
        $camino = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        if (!in_array(basename($camino), ['', 'index.php'], true)) {
            throw new ErrorHttp('Página no encontrada. Abra index.html desde la raíz pública del servidor', 404);
        }
        header('Location: index.html');
        exit;
    }
    require_once __DIR__ . '/../config/config.php';
    $ruta = $_GET['ruta'];
    if (!is_string($ruta) || trim($ruta) === '') throw new ErrorHttp('La ruta es inválida', 400);
    $metodo = $_SERVER['REQUEST_METHOD'];
    Sesion::preparar();
    // El rol almacenado no autoriza cuentas que luego fueron desactivadas.
    if (isset($_SESSION['cedula']) && $ruta !== 'login' && $ruta !== 'logout') {
        require_once RUTA_MODELO . '/UsuarioDAO.php';
        $usuarioSesion = (new UsuarioDAO(Conexion::conectar()))->listarUsuario($_SESSION['cedula']);
        if (!$usuarioSesion || !$usuarioSesion['estado']) {
            Sesion::cerrar();
            throw new ErrorHttp('La sesión ya no está habilitada', 401);
        }
        foreach (['administrador', 'tecnico', 'solicitante'] as $rol) {
            $_SESSION[$rol] = (bool) $usuarioSesion[$rol];
        }
    }
    require RUTA_APP . '/rutas/api.php';
} catch (Throwable $error) {
    $previsto = $error instanceof ErrorHttp;
    if (!$previsto) {
        $rutaError = is_string($_GET['ruta'] ?? null) ? $_GET['ruta'] : 'ruta inválida';
        error_log('SGRSI ' . $rutaError . ': ' . $error->getMessage());
    }
    http_response_code($previsto ? $error->getCode() : 500);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    header('X-Content-Type-Options: nosniff');
    echo json_encode(['mensaje' => $previsto ? $error->getMessage() : 'Error interno del servidor'], JSON_UNESCAPED_UNICODE);
} finally {
    unset($controlador, $usuarioSesion);
    if (class_exists('Conexion')) Conexion::desconectar();
}
