<?php

require_once __DIR__ . "/../modelo/ConectorPDO.php";
require_once __DIR__ . "/../modelo/AccesoDatosUsuario.php";
require_once __DIR__ . "/../modelo/Usuario.php";
require_once __DIR__ . "/../modelo/Login.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $mensaje = "Acceso Denegado: Petición incorrecta";
    header("Location: login.php?" . "error=" . $mensaje);
    exit;
}


$cedula = trim($_POST["cedula"] ?? "");
$clave = $_POST["clave"] ?? "";


$conectorPDO = new ConectorPDO ("localhost", "leandro", "123", "test");
$conexion = $conectorPDO->establecerConexion();

    $accesoDatosUsuario = new AccesoDatosUsuario($conexion);
    $login = new Login($accesoDatosUsuario);

$conectorPDO->desconectar();

$usuario = $login->autenticar($cedula, $clave);

if ($usuario === null) {
  $mensaje = "Acceso Denegado: La cédula o la contraseña son incorrectas.";
    header("Location: login.php?" . "error=" . $mensaje);
    exit;
}

session_start();
session_regenerate_id(true);


$_SESSION["cedula"] = $usuario->getCedula();
$_SESSION["nombre"] = $usuario->getNombre();
$_SESSION["rol"] = $usuario->getRol();


switch ($usuario->getRol()) {
    case "administrador":
        header("Location: ../../public/admin.php");
        exit;

    case "tecnico":
        header("Location: ../../public/tecnico.php");
        exit;

    case "solicitante":
        header("Location: ../../public/solicitante.php");
        exit;

    default:
        session_destroy();

        $mensaje = "Acceso Denegado: El usuario no tiene un rol válido.";
        header("Location: login.php?" . "error=" . $mensaje);
        exit;
}