<?php

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosDispositivo.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: inventario.php?error=peticion" );
    exit;
}

$idLab = trim($_POST["idLab"] ?? "");
$numeroDispositivo = trim($_POST["numeroDispositivo"] ?? "");
$modificaciones = trim($_POST["modificaciones"] ?? "");
$estado = trim($_POST["estado"] ?? "");
$ultimoCambio = trim($_POST["ultimoCambio"] ?? "");


if ($idLab === "" || $numeroDispositivo === "" || $modificaciones === "" || $estado === "" || $ultimoCambio === "" ) {
    header("Location: inventario.php?error=campos_vacios");
    exit;
}

$laboratoriosPermitidos = ["LAB01", "LAB02", "LAB03", "LAB04", "LAB05", "LAB06"];

if (!in_array($idLab, $laboratoriosPermitidos, true) || !in_array($modificaciones, ["N/A", "Reparado", "Actualizado"], true) || !in_array($estado, ["1", "0"], true)) {
    header("Location: inventario.php?error=datos_incorrectos");
    exit;
}

$fecha = DateTime::createFromFormat("Y-m-d\\TH:i", $ultimoCambio);

if ($fecha === false) {
    header("Location: inventario.php?error=datos_incorrectos");
    exit;
}


$conectorPDO = new ConectorPDO("localhost", "root", "", "test");

$conexion = $conectorPDO->establecerConexion();

if ($conexion === null) {
    header("Location: inventario.php?error=conexion");
    exit;
}

$altaDatosDispositivo = new AltaDatosDispositivo($conexion);

$resultado = $altaDatosDispositivo->registrarDispositivo(
    $idLab,
    $numeroDispositivo,
    $modificaciones,
    $fecha->format("Y-m-d H:i:s"),
    $estado === "1"
);

$conectorPDO->desconectar();


if (!$resultado) {
    header("Location: inventario.php?error=error_dispositivo");
    exit;
}

header("Location: inventario.php?exito=dispositivo");
exit;

?>
