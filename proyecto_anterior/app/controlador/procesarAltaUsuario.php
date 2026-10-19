
<?php

/** Controlador que valida y registra un usuario. */

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosUsuario.php";

$cedula = trim($_POST["cedula"] ?? "");
$nombre = trim($_POST["nombre"] ?? "");
$apellido = trim($_POST["apellido"] ?? "");
$contrasena = $_POST["contrasena"] ?? "";
$confirmarContrasena = $_POST["confirmarContrasena"] ?? "";
$rol = trim($_POST["rol"] ?? "");
validarCamposObligatorios([$cedula, $nombre, $apellido, $contrasena, $confirmarContrasena, $rol], "usuarios.php");

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

$consultaUsuario = $conexion->prepare("SELECT 1 FROM USUARIO WHERE cedula = :cedula");
$consultaUsuario->execute(["cedula" => $cedula]);
if ($consultaUsuario->fetchColumn() !== false) {
    $conectorPDO->desconectar();
    header("Location: usuarios.php?error=cedula_duplicada");
    exit;
}

$resultado = $altaDatosUsuario->registrarUsuario($cedula, $nombre, $apellido, $claveHash, $rol);

$conectorPDO->desconectar();


if (!$resultado) {
    header("Location: usuarios.php?error=error_usuario");
    exit;
}

header("Location: usuarios.php?exito=usuario");
exit;

?>
