
<?php

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosUsuario.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: usuarios.php?error=peticion" );
    exit;
}

$cedula = trim($_POST["cedula"] ?? "");
$nombre = trim($_POST["nombre"] ?? "");
$apellido = trim($_POST["apellido"] ?? "");
$contrasena = $_POST["contrasena"] ?? "";
$confirmarContrasena = $_POST["confirmarContrasena"] ?? "";
$rol = trim($_POST["rol"] ?? "");

if ($cedula === "" || $nombre === "" || $apellido === "" || $contrasena === "" || $confirmarContrasena === "" || $rol === "" ) {
    header("Location: usuarios.php?error=campos_vacios");
    exit;
}

if (!preg_match("/^[1-9][0-9]{7}$/", $cedula)) {
    header("Location: usuarios.php?error=cedula_incorrecta");
    exit;
}

if (strlen($contrasena) < 12) {
    header("Location: usuarios.php?error=contraseña_corta");
    exit;
}

if ($contrasena !== $confirmarContrasena) {
    header("Location: usuarios.php?error=contraseña");
    exit;
}

$rolesPermitidos = ["administrador", "tecnico", "solicitante"];

if (!in_array($rol, $rolesPermitidos, true)) {
    header("Location: usuarios.php?error=rol_incorrecto");
    exit;
}

$claveHash = password_hash($contrasena, PASSWORD_DEFAULT);


$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);

$conexion = $conectorPDO->establecerConexion();

if ($conexion === null) {
    header("Location: usuarios.php?error=conexion");
    exit;
}

$altaDatosUsuario = new AltaDatosUsuario($conexion);

$resultado = $altaDatosUsuario->registrarUsuario($cedula, $nombre, $apellido, $claveHash, $rol);

$conectorPDO->desconectar();


if (!$resultado) {
    header("Location: usuarios.php?error=error_usuario");
    exit;
}

header("Location: usuarios.php?exito=usuario");
exit;

?>
