<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosRegistroUso.php";
require_once RUTA_MODELO . "/AccesoDatosDispositivo.php";
require_once RUTA_MODELO . "/AccesoDatosCatalogo.php";

$idLaboratorio = trim($_POST["idLaboratorio"] ?? "");
$turno = trim($_POST["turno"] ?? "");
$fechaHoraEntrada = trim($_POST["fechaHora"] ?? "");
$nombreDocente = trim($_POST["nombreDocente"] ?? "");
$grupo = trim($_POST["grupo"] ?? "");
$asignatura = trim($_POST["asignatura"] ?? "");
$usoMaquinas = $_POST["usoMaquinas"] ?? "";
$huboIncidencias = $_POST["huboIncidencias"] ?? "";

$fechaHora = DateTime::createFromFormat("Y-m-d\\TH:i", $fechaHoraEntrada);
$camposObligatorios = [
    $idLaboratorio, $turno, $fechaHoraEntrada, $nombreDocente,
    $grupo, $asignatura, $usoMaquinas, $huboIncidencias
];

if ($fechaHora === false
    || in_array("", $camposObligatorios, true)
    || !in_array($usoMaquinas, ["SI", "NO"], true)
    || !in_array($huboIncidencias, ["SI", "NO"], true)) {
    header("Location: registroUso.php?error=datos_incorrectos");
    exit;
}

$conectorPDO = new ConectorPDO(
    $_ENV["DB_HOST"],
    $_ENV["DB_USUARIO"],
    $_ENV["DB_CLAVE"],
    $_ENV["DB_NOMBRE"]
);
$conexion = $conectorPDO->establecerConexion();
$accesoDatosDispositivo = new AccesoDatosDispositivo($conexion);
$accesoDatosCatalogo = new AccesoDatosCatalogo($conexion);

if (!$accesoDatosDispositivo->existeLaboratorio($idLaboratorio)
    || !$accesoDatosCatalogo->existeTurno($turno)) {
    $conectorPDO->desconectar();
    header("Location: registroUso.php?error=datos_incorrectos");
    exit;
}

$registro = [
    "idRegistro" => "REG" . strtoupper(substr(uniqid(), -5)),
    "cedulaSolicitante" => $_SESSION["cedula"],
    "idLaboratorio" => $idLaboratorio,
    "turno" => $turno,
    "fechaHora" => $fechaHora->format("Y-m-d H:i:s"),
    "nombreDocente" => $nombreDocente,
    "grupo" => $grupo,
    "asignatura" => $asignatura,
    "usoMaquinas" => $usoMaquinas === "SI" ? 1 : 0,
    "huboIncidencias" => $huboIncidencias === "SI" ? 1 : 0
];

$altaDatosRegistroUso = new AltaDatosRegistroUso($conexion);
$resultado = $altaDatosRegistroUso->registrar($registro);
$conectorPDO->desconectar();

header("Location: registroUso.php?" . ($resultado ? "exito=registro" : "error=error_registro"));
exit;
