<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosIncidencia.php";

$laboratorio = trim($_POST["laboratorio"] ?? "");
$turno = trim($_POST["turno"] ?? "");
$fechaEntrada = trim($_POST["fechaHora"] ?? "");
$docente = trim($_POST["docente"] ?? "");
$grupo = trim($_POST["grupo"] ?? "");
$asignatura = trim($_POST["asignatura"] ?? "");
$reportoAlumno = trim($_POST["reportoAlumno"] ?? "");
$nombreAlumno = trim($_POST["nombreAlumno"] ?? "");
$maquina = trim($_POST["maquina"] ?? "");
$recurso = trim($_POST["recurso"] ?? "");
$tipoIncidencia = trim($_POST["tipoIncidencia"] ?? "");
$descripcion = trim($_POST["descripcion"] ?? "");
$fechaHora = DateTime::createFromFormat("Y-m-d\\TH:i", $fechaEntrada);

$validos = $_SERVER["REQUEST_METHOD"] === "POST" && $fechaHora !== false
    && !in_array("", [$laboratorio, $turno, $docente, $grupo, $asignatura, $reportoAlumno, $recurso, $tipoIncidencia, $descripcion], true)
    && in_array($laboratorio, ["N/A", "LAB1", "LAB2", "LAB3", "LAB4", "LAB5", "LAB6"], true)
    && in_array($turno, ["MATUTINO", "VESPERTINO", "NOCTURNO"], true)
    && in_array($reportoAlumno, ["SI", "NO"], true)
    && in_array($recurso, ["MOUSE", "TECLADO", "MONITOR", "TORRE", "RED"], true)
    && in_array($tipoIncidencia, ["NO_FUNCIONA", "LENTO", "SIN_CONEXION", "DANO_FISICO", "SOFTWARE", "PERIFERICO", "OTRO"], true)
    && ($maquina === "" || filter_var($maquina, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) !== false);
if (!$validos) {
    header("Location: incidencias.php?error=datos_incorrectos");
    exit;
}

$datos = [
    "idIncidencia" => "INC" . strtoupper(substr(uniqid(), -5)), "cedulaSolicitante" => $_SESSION["cedula"],
    "laboratorio" => $laboratorio, "turno" => $turno, "fechaHora" => $fechaHora->format("Y-m-d H:i:s"),
    "docente" => $docente, "grupo" => $grupo, "asignatura" => $asignatura,
    "reportoAlumno" => $reportoAlumno === "SI" ? 1 : 0, "nombreAlumno" => $nombreAlumno === "" ? null : $nombreAlumno,
    "maquina" => $maquina === "" ? null : (int) $maquina, "recurso" => $recurso,
    "tipoIncidencia" => $tipoIncidencia, "descripcion" => $descripcion
];
$conectorPDO = new ConectorPDO("localhost", "root", "", "test");
$conexion = $conectorPDO->establecerConexion();
$resultado = (new AltaDatosIncidencia($conexion))->registrarIncidencia($datos);
$conectorPDO->desconectar();
header("Location: incidencias.php?" . ($resultado ? "exito=incidencia" : "error=incidencia"));
exit;
