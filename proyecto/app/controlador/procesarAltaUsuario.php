
<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosUsuario.php";


session_start();


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: administrador.php?error=peticion" );
    exit;
}

$cedula = trim($_POST["cedula"] ?? "");
$nombre = trim($_POST["nombre"] ?? "");
$apellido = trim($_POST["apellido"] ?? "");

$contraseña = $_POST["contraseña"] ?? "";
$confirmarContraseña = $_POST["confirmarContraseña"] ?? "";

$cargo = trim($_POST["cargo"] ?? "");

if ($cedula === "" || $nombre === "" || $apellido === "" || $contraseña === "" || $confirmarContraseña === "" || $cargo === "" ) {
    header("Location: administrador.php?error=campos_vacios");
    exit;
}

if (!preg_match("/^[1-9][0-9]{7}$/", $cedula)) {
    header("Location: administrador.php?error=cedula_incorrecta");
    exit;
}

if (strlen($contraseña) < 12) {
    header("Location: administrador.php?error=contraseña_corta");
    exit;
}

if ($contraseña !== $confirmarContraseña) {
    header("Location: administrador.php?error=contraseña");
    exit;
}

$claveHash = password_hash($contraseña, PASSWORD_DEFAULT);


$conectorPDO = new ConectorPDO("localhost", "root", "", "test");

$conexion = $conectorPDO->establecerConexion();

if ($conexion === null) {
    header("Location: administrador.php?error=conexion");
    exit;
}

$altaDatosUsuario = new AltaDatosUsuario($conexion);

$resultado = $altaDatosUsuario->registrarUsuario($cedula, $nombre, $apellido, $claveHash, $cargo);

$conectorPDO->desconectar();


if (!$resultado) {
    $mensaje = "No se pudo registrar el empleado.";

    header("Location: administrador.php?error=" . urlencode($mensaje));
    exit;
}

$mensaje = "Usuario ingresado exitosamente.";
header("Location: administrador.php?resultado=" . urlencode($mensaje));
exit;

?>