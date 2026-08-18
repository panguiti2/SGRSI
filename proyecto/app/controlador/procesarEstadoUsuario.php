<?php

/** Controlador que activa o desactiva un usuario. */

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/ModificarEstadoUsuario.php";

$cedula = trim($_POST["cedula"] ?? "");
$estado = $_POST["estado"] ?? "";

if (!preg_match("/^[1-9][0-9]{7}$/", $cedula) || !in_array($estado, ["0", "1"], true)) {
    header("Location: usuarios.php?error=datos_incorrectos");
    exit;
}

$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();
$resultado = (new ModificarEstadoUsuario($conexion))->cambiarEstado($cedula, $estado === "1");
$conectorPDO->desconectar();

header("Location: usuarios.php?" . ($resultado ? "exito=estado_usuario" : "error=error_usuario"));
exit;
