<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosSolicitud.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: solicitudes.php?error=peticion");
    exit;
}

$turno = trim($_POST["turno"] ?? "");
$nombreDocente = trim($_POST["nombreDocente"] ?? "");
$grupo = trim($_POST["grupo"] ?? "");
$asignatura = trim($_POST["asignatura"] ?? "");
$fechaAperturaEntrada = trim($_POST["fechaApertura"] ?? "");
$tipoServicio = trim($_POST["tipoServicio"] ?? "");
$descripcion = trim($_POST["descripcion"] ?? "");

$camposObligatorios = [$turno, $nombreDocente, $grupo, $asignatura, $fechaAperturaEntrada, $tipoServicio, $descripcion];
if (in_array("", $camposObligatorios, true)) {
    header("Location: solicitudes.php?error=campos_vacios");
    exit;
}

$fechaApertura = DateTime::createFromFormat("Y-m-d\\TH:i", $fechaAperturaEntrada);
$datosValidos = in_array($turno, ["MATUTINO", "VESPERTINO", "NOCTURNO"], true)
    && in_array($tipoServicio, ["INSTALACION", "ACTUALIZACION", "CONFIGURACION", "OTRO"], true)
    && $fechaApertura !== false;

if (!$datosValidos) {
    header("Location: solicitudes.php?error=datos_incorrectos");
    exit;
}

$solicitud = [
    "idSolicitud" => "SOL" . strtoupper(substr(uniqid(), -5)),
    "cedulaSolicitante" => $_SESSION["cedula"],
    "turno" => $turno,
    "nombreDocente" => $nombreDocente,
    "grupo" => $grupo,
    "asignatura" => $asignatura,
    "fechaApertura" => $fechaApertura->format("Y-m-d H:i:s"),
    "tipoServicio" => $tipoServicio,
    "descripcion" => $descripcion
];

$conectorPDO = new ConectorPDO("localhost", "root", "", "test");
$conexion = $conectorPDO->establecerConexion();
$altaDatosSolicitud = new AltaDatosSolicitud($conexion);
$resultado = $altaDatosSolicitud->registrarSolicitud($solicitud);
$conectorPDO->desconectar();

header("Location: solicitudes.php?" . ($resultado ? "exito=solicitud" : "error=error_solicitud"));
exit;
