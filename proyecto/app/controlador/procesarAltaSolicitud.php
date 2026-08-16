<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosSolicitud.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: solicitudes.php?error=peticion");
    exit;
}

$laboratorio = trim($_POST["laboratorio"] ?? "");
$turno = trim($_POST["turno"] ?? "");
$docente = trim($_POST["docente"] ?? "");
$asignatura = trim($_POST["asignatura"] ?? "");
$email = trim($_POST["email"] ?? "");
$fechaHoraEntrada = trim($_POST["fechaHora"] ?? "");
$tipoServicio = trim($_POST["tipoServicio"] ?? "");
$software = trim($_POST["software"] ?? "");
$todasMaquinas = trim($_POST["todasMaquinas"] ?? "");
$prioridad = trim($_POST["prioridad"] ?? "NORMAL");
$descripcion = trim($_POST["descripcion"] ?? "");

$camposObligatorios = [$laboratorio, $turno, $docente, $asignatura, $email, $fechaHoraEntrada, $tipoServicio, $todasMaquinas];
if (in_array("", $camposObligatorios, true)) {
    header("Location: solicitudes.php?error=campos_vacios");
    exit;
}

$fechaHora = DateTime::createFromFormat("Y-m-d\\TH:i", $fechaHoraEntrada);
$datosValidos = in_array($laboratorio, ["N/A", "LAB1", "LAB2", "LAB3", "LAB4", "LAB5", "LAB6"], true)
    && in_array($turno, ["MATUTINO", "VESPERTINO", "NOCTURNO"], true)
    && in_array($tipoServicio, ["INSTALACION", "ACTUALIZACION", "CONFIGURACION", "OTRO"], true)
    && in_array($todasMaquinas, ["SI", "NO"], true)
    && in_array($prioridad, ["NORMAL", "URGENTE"], true)
    && filter_var($email, FILTER_VALIDATE_EMAIL)
    && $fechaHora !== false;

if (!$datosValidos) {
    header("Location: solicitudes.php?error=datos_incorrectos");
    exit;
}

$solicitud = [
    "idSolicitud" => "SOL" . strtoupper(substr(uniqid(), -5)),
    "cedulaSolicitante" => $_SESSION["cedula"],
    "laboratorio" => $laboratorio,
    "turno" => $turno,
    "docente" => $docente,
    "asignatura" => $asignatura,
    "email" => $email,
    "fechaHora" => $fechaHora->format("Y-m-d H:i:s"),
    "tipoServicio" => $tipoServicio,
    "software" => $software === "" ? null : $software,
    "todasMaquinas" => $todasMaquinas === "SI" ? 1 : 0,
    "prioridad" => $prioridad,
    "descripcion" => $descripcion === "" ? null : $descripcion
];

$conectorPDO = new ConectorPDO("localhost", "root", "", "test");
$conexion = $conectorPDO->establecerConexion();
$altaDatosSolicitud = new AltaDatosSolicitud($conexion);
$resultado = $altaDatosSolicitud->registrarSolicitud($solicitud);
$conectorPDO->desconectar();

header("Location: solicitudes.php?" . ($resultado ? "exito=solicitud" : "error=error_solicitud"));
exit;
