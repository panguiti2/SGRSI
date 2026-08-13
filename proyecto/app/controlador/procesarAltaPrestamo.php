<?php

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosPrestamo.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: prestamos.php?error=peticion");
    exit;
}

$cedulaSolicitante = trim($_POST["cedulaSolicitante"] ?? "");
$turno = trim($_POST["turno"] ?? "");
$nombreSolicitante = trim($_POST["nombreSolicitante"] ?? "");
$numeroLaptop = trim($_POST["numeroLaptop"] ?? "");
$retiro = trim($_POST["retiro"] ?? "");
$devolucion = trim($_POST["devolucion"] ?? "");

if ($cedulaSolicitante === "" || $turno === "" || $nombreSolicitante === "" || $numeroLaptop === "" || $retiro === "" || $devolucion === "") {
    header("Location: prestamos.php?error=campos_vacios");
    exit;
}

if (!preg_match("/^[1-9][0-9]{7}$/", $cedulaSolicitante) || !in_array($turno, ["Matutino", "Vespertino", "Nocturno"], true)) {
    header("Location: prestamos.php?error=datos_incorrectos");
    exit;
}

$fechaRetiro = DateTime::createFromFormat("Y-m-d\\TH:i", $retiro);
$fechaDevolucion = DateTime::createFromFormat("Y-m-d\\TH:i", $devolucion);

if ($fechaRetiro === false || $fechaDevolucion === false || $fechaDevolucion <= $fechaRetiro) {
    header("Location: prestamos.php?error=datos_incorrectos");
    exit;
}

$idPrestamo = "PRE" . strtoupper(substr(uniqid(), -5));

$conectorPDO = new ConectorPDO("localhost", "root", "", "test");
$conexion = $conectorPDO->establecerConexion();
$altaDatosPrestamo = new AltaDatosPrestamo($conexion);

$resultado = $altaDatosPrestamo->registrarPrestamo(
    $idPrestamo,
    $cedulaSolicitante,
    $turno,
    $nombreSolicitante,
    $numeroLaptop,
    $fechaRetiro->format("Y-m-d H:i:s"),
    $fechaDevolucion->format("Y-m-d H:i:s")
);

$conectorPDO->desconectar();

if (!$resultado) {
    header("Location: prestamos.php?error=error_prestamo");
    exit;
}

header("Location: prestamos.php?exito=prestamo");
exit;
