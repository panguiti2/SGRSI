<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosSolicitud.php";
require_once RUTA_MODELO . "/AccesoDatosDispositivo.php";

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
$idLaboratorio = trim($_POST["idLaboratorio"] ?? "");
$numeroDispositivo = trim($_POST["numeroDispositivo"] ?? "");
$descripcion = trim($_POST["descripcion"] ?? "");

$camposObligatorios = [$turno, $nombreDocente, $grupo, $asignatura, $fechaAperturaEntrada, $tipoServicio, $idLaboratorio, $numeroDispositivo, $descripcion];
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
    "idLaboratorio" => $idLaboratorio,
    "numeroDispositivo" => $numeroDispositivo,
    "descripcion" => $descripcion
];

$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();
$accesoDatosDispositivo = new AccesoDatosDispositivo($conexion);

if (!$accesoDatosDispositivo->existeDispositivo($idLaboratorio, $numeroDispositivo)) {
    $conectorPDO->desconectar();
    header("Location: solicitudes.php?error=datos_incorrectos");
    exit;
}

$altaDatosSolicitud = new AltaDatosSolicitud($conexion);
$resultado = $altaDatosSolicitud->registrarSolicitud($solicitud);
$conectorPDO->desconectar();

header("Location: solicitudes.php?" . ($resultado ? "exito=solicitud" : "error=error_solicitud"));
exit;
