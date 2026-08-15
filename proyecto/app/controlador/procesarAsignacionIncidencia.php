<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosIncidencia.php";
$vencimientoEntrada = trim($_POST["vencimiento"] ?? "");
$vencimiento = DateTime::createFromFormat("Y-m-d\\TH:i", $vencimientoEntrada);
$datos = ["idIncidencia" => trim($_POST["idIncidencia"] ?? ""), "estado" => trim($_POST["estado"] ?? ""),
    "urgencia" => trim($_POST["urgencia"] ?? ""), "tecnicoAsignado" => trim($_POST["tecnicoAsignado"] ?? ""),
    "vencimiento" => $vencimiento === false ? "" : $vencimiento->format("Y-m-d H:i:s")];
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !preg_match("/^INC[A-Z0-9]{5}$/", $datos["idIncidencia"])
    || $vencimiento === false || $datos["tecnicoAsignado"] === ""
    || !in_array($datos["estado"], ["PENDIENTE", "EN PROCESO", "RESUELTO"], true)
    || !in_array($datos["urgencia"], ["ALTA", "MEDIA", "BAJA"], true)) {
    header("Location: incidencias.php?error=datos_incorrectos"); exit;
}
$conectorPDO = new ConectorPDO("localhost", "root", "", "test");
$conexion = $conectorPDO->establecerConexion();
$resultado = (new AltaDatosIncidencia($conexion))->asignarIncidencia($datos);
$conectorPDO->desconectar();
header("Location: incidencias.php?" . ($resultado ? "exito=asignacion" : "error=asignacion")); exit;
