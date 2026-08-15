<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosRegistroUso.php";
$fecha = DateTime::createFromFormat("Y-m-d\\TH:i", trim($_POST["fechaHora"] ?? ""));
$datos = ["idRegistro" => "REG" . strtoupper(substr(uniqid(), -5)), "cedulaSolicitante" => $_SESSION["cedula"],
    "laboratorio" => trim($_POST["laboratorio"] ?? ""), "turno" => trim($_POST["turno"] ?? ""),
    "fechaHora" => $fecha === false ? "" : $fecha->format("Y-m-d H:i:s"), "docente" => trim($_POST["docente"] ?? ""),
    "grupo" => trim($_POST["grupo"] ?? ""), "asignatura" => trim($_POST["asignatura"] ?? ""),
    "usoMaquinas" => ($_POST["usoMaquinas"] ?? "") === "SI" ? 1 : 0, "huboIncidencias" => ($_POST["incidencias"] ?? "") === "SI" ? 1 : 0];
$validos = $_SERVER["REQUEST_METHOD"] === "POST" && $fecha !== false && !in_array("", array_slice($datos, 2, 6), true)
    && in_array($datos["laboratorio"], ["N/A", "LAB1", "LAB2", "LAB3", "LAB4", "LAB5", "LAB6"], true)
    && in_array($datos["turno"], ["MATUTINO", "VESPERTINO", "NOCTURNO"], true)
    && in_array($_POST["usoMaquinas"] ?? "", ["SI", "NO"], true) && in_array($_POST["incidencias"] ?? "", ["SI", "NO"], true);
if (!$validos) { header("Location: registroUso.php?error=datos_incorrectos"); exit; }
$conectorPDO = new ConectorPDO("localhost", "root", "", "test"); $conexion = $conectorPDO->establecerConexion();
$resultado = (new AltaDatosRegistroUso($conexion))->registrar($datos); $conectorPDO->desconectar();
header("Location: registroUso.php?" . ($resultado ? "exito=registro" : "error=registro")); exit;
