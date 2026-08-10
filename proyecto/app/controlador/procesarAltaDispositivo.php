<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosDispositivo.php";


session_start();


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: administrador.php?error=peticion" );
    exit;
}

$laboratorio = trim($_POST["laboratorio"] ?? "");
$numeroDispositivo = trim($_POST["numeroDispositivo"] ?? "");
$modificaciones = trim($_POST["modificaciones"] ?? "");
$estado = trim($_POST["estado"] ?? "");
$ultimoCambio = trim($_POST["ultimoCambio"] ?? "");


if ($laboratorio === "" || $numeroDispositivo === "" || $modificaciones === "" || $estado === "" || $ultimoCambio   === "" ) {
    header("Location: administrador.php?error=campos_vacios");
    exit;
}


$conectorPDO = new ConectorPDO("localhost", "root", "", "test");

$conexion = $conectorPDO->establecerConexion();

if ($conexion === null) {
    header("Location: administrador.php?error=conexion");
    exit;
}

$altaDatosDispositivo = new AltaDatosDispositivo($conexion);

$resultado = $altaDatosDispositivo->registrarDispositivo($numeroDispositivo, $laboratorio, $modificaciones, $estado, $ultimoCambio);

$conectorPDO->desconectar();


if (!$resultado) {
    header("Location: administrador.php?error=error_dispositivo");
    exit;
}

header("Location: administrador.php?resultado=exito");
exit;

?>