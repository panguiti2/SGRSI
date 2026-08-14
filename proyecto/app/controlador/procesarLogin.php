<?php

require_once RUTA_MODELO . "/modelo/ConectorPDO.php";
require_once RUTA_MODELO . "/modelo/AccesoDatosUsuario.php";
require_once RUTA_MODELO . "/modelo/Usuario.php";
require_once RUTA_MODELO . "/modelo/Login.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php?error=peticion");
    exit;
}

$cedula = trim($_POST["cedula"] ?? "");
$clave = $_POST["clave"] ?? "";

if ($cedula === "" || $clave === "") {
    header("Location: login.php?error=credenciales");
    exit;
}


$conectorPDO = new ConectorPDO("localhost", "root", "", "test");
$conexion = $conectorPDO->establecerConexion();

$accesoDatosUsuario = new AccesoDatosUsuario($conexion);
$login = new Login($accesoDatosUsuario);

$usuario = $login->autenticar($cedula, $clave);
$conectorPDO->desconectar();

if ($usuario === null) {
    header("Location: login.php?error=" . $login->getCodigoError());
    exit;
}

session_start();
session_regenerate_id(true);

$_SESSION["cedula"] = $usuario->getCedula();
$_SESSION["administrador"] = $usuario->esAdministrador();
$_SESSION["tecnico"] = $usuario->esTecnico();
$_SESSION["solicitante"] = $usuario->esSolicitante();

if ($_SESSION["administrador"]) {
    header("Location: administrador.php");
} elseif ($_SESSION["tecnico"]) {
    header("Location: tecnico.php");
} elseif ($_SESSION["solicitante"]) {
    header("Location: solicitante.php");
} else {
    $_SESSION = [];
    session_destroy();
    header("Location: login.php?error=sin_roles");
}

exit;
