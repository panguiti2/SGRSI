<?php
// Servidor de verificación: jamás usa el nombre ni las credenciales de producción.
$ruta = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (isset($_GET['dialogo']) && in_array($ruta, ['/admin/usuarios.html','/admin/inventario.html','/tecnico/prestamos.html','/tecnico/incidencias.html','/tecnico/solicitudes.html'], true)) {
    $html = file_get_contents(__DIR__ . '/../proyecto/public' . $ruta);
    $mostrar = '<script>formularioListo.then(async listo => {
        if (!listo) return;
        await actualizarTabla();
        if (typeof abrirAltaUsuario === "function") abrirAltaUsuario();
        else if (typeof abrirAltaDispositivo === "function") abrirAltaDispositivo();
        else {
            const boton = [...document.querySelectorAll("#btnRegistrarPrestamo, .btnGestionarIncidencia, .btnGestionarSolicitud")].find(actual => !actual.disabled);
            if (boton) boton.click();
        }
    });</script>';
    header('Content-Type: text/html; charset=utf-8');
    echo str_replace('</body>', $mostrar . '</body>', $html);
    return true;
}
if ($ruta === '/_derPrueba.svg') {
    header('Content-Type: image/svg+xml');
    readfile(__DIR__ . '/../baseDeDatos/DER-implementado.svg');
    return true;
}
if (!in_array($ruta, ['/index.php', '/_sesionPrueba'], true)) return false;
require_once __DIR__ . '/../proyecto/config/config.php';
$_ENV['DB_HOST'] = '127.0.0.1';
$_ENV['DB_PUERTO'] = '3317';
$_ENV['DB_USUARIO'] = 'root';
$_ENV['DB_CLAVE'] = '';
$_ENV['DB_NOMBRE'] = 'sgrsi_api_prueba';
if ($ruta === '/_sesionPrueba') {
    $roles = ['administrador' => '11111111', 'tecnico' => '22222222', 'solicitante' => '33333333'];
    $rol = $_GET['rol'] ?? '';
    $pagina = $_GET['pagina'] ?? 'inicio';
    $paginas = ['inicio', 'usuarios', 'inventario', 'incidencias', 'metricas', 'prestamos', 'registrosUso', 'solicitudes', 'registroUso'];
    if (!isset($roles[$rol]) || !in_array($pagina, $paginas, true)) { http_response_code(404); return true; }
    require_once RUTA_MODELO . '/UsuarioDAO.php';
    Sesion::preparar();
    Sesion::iniciar((new UsuarioDAO(Conexion::conectar()))->listarUsuario($roles[$rol]));
    Conexion::desconectar();
    $carpeta = $rol === 'administrador' ? 'admin' : $rol;
    $dialogo = isset($_GET['dialogo']) ? '?dialogo=prueba' : '';
    header('Location: /' . $carpeta . '/' . $pagina . '.html' . $dialogo);
    return true;
}
require __DIR__ . '/../proyecto/public/index.php';
