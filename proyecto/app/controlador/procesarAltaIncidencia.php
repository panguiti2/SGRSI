<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosIncidencia.php";

$turno = trim($_POST["turno"] ?? "");
$fechaEntrada = trim($_POST["fechaApertura"] ?? "");
$nombreDocente = trim($_POST["nombreDocente"] ?? "");
$grupo = trim($_POST["grupo"] ?? "");
$asignatura = trim($_POST["asignatura"] ?? "");
$idLaboratorio = trim($_POST["idLaboratorio"] ?? "");
$numeroDispositivo = trim($_POST["numeroDispositivo"] ?? "");
$reportoAlumno = trim($_POST["reportoAlumno"] ?? "");
$nombreAlumno = trim($_POST["nombreAlumno"] ?? "");
$descripcion = trim($_POST["descripcion"] ?? "");
$fechaHora = DateTime::createFromFormat("Y-m-d\\TH:i", $fechaEntrada);

$validos = $_SERVER["REQUEST_METHOD"] === "POST" && $fechaHora !== false
    && !in_array("", [$turno, $nombreDocente, $grupo, $asignatura, $idLaboratorio, $numeroDispositivo, $reportoAlumno, $descripcion], true)
    && in_array($turno, ["MATUTINO", "VESPERTINO", "NOCTURNO"], true)
    && in_array($reportoAlumno, ["SI", "NO"], true)
    && (($reportoAlumno === "SI" && $nombreAlumno !== "") || ($reportoAlumno === "NO" && $nombreAlumno === ""));
if (!$validos) {
    header("Location: incidencias.php?error=datos_incorrectos");
    exit;
}

$datos = [
    "idIncidencia" => "INC" . strtoupper(substr(uniqid(), -5)), "cedulaSolicitante" => $_SESSION["cedula"],
    "turno" => $turno, "fechaApertura" => $fechaHora->format("Y-m-d H:i:s"),
    "nombreDocente" => $nombreDocente, "grupo" => $grupo, "asignatura" => $asignatura,
    "idLaboratorio" => $idLaboratorio, "numeroDispositivo" => $numeroDispositivo,
    "reportoAlumno" => $reportoAlumno === "SI" ? 1 : 0, "nombreAlumno" => $nombreAlumno === "" ? null : $nombreAlumno,
    "descripcion" => $descripcion
];
$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();
$resultado = (new AltaDatosIncidencia($conexion))->registrarIncidencia($datos);
$conectorPDO->desconectar();
header("Location: incidencias.php?" . ($resultado ? "exito=incidencia" : "error=incidencia"));
exit;
