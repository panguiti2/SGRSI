<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/ModificarDatosUsuario.php";

$cedula = trim($_POST["cedula"] ?? "");
$nombre = trim($_POST["nombre"] ?? "");
$apellido = trim($_POST["apellido"] ?? "");
$rol = trim($_POST["rol"] ?? "");
$contrasena = $_POST["contrasena"] ?? "";
$confirmarContrasena = $_POST["confirmarContrasena"] ?? "";

if ($cedula === "" || $nombre === "" || $apellido === "" || $rol === "" || $contrasena === "" || $confirmarContrasena === "") {
    header("Location: usuarios.php?error=campos_vacios");
    exit;
}

if (!preg_match("/^[1-9][0-9]{7}$/", $cedula) || !in_array($rol, ["administrador", "tecnico", "solicitante"], true)) {
    header("Location: usuarios.php?error=datos_incorrectos");
    exit;
}

if (strlen($contrasena) < 12 || $contrasena !== $confirmarContrasena) {
    header("Location: usuarios.php?error=contraseña");
    exit;
}

$claveHash = password_hash($contrasena, PASSWORD_DEFAULT);

$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();
$resultado = (new ModificarDatosUsuario($conexion))->modificarUsuario($cedula, $nombre, $apellido, $claveHash, $rol);
$conectorPDO->desconectar();

header("Location: usuarios.php?" . ($resultado ? "exito=usuario_modificado" : "error=error_usuario"));
exit;
