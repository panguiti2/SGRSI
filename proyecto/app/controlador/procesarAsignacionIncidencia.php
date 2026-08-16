<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosIncidencia.php";
$datos = ["idIncidencia" => trim($_POST["idIncidencia"] ?? ""), "estado" => trim($_POST["estado"] ?? ""),
    "cedulaTecnico" => $_SESSION["cedula"]];
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !preg_match("/^INC[A-Z0-9]{5}$/", $datos["idIncidencia"])
    || !in_array($datos["estado"], ["PENDIENTE", "EN PROCESO", "RESUELTO"], true)) {
    header("Location: incidencias.php?error=datos_incorrectos"); exit;
}
$conectorPDO = new ConectorPDO("localhost", "root", "", "test");
$conexion = $conectorPDO->establecerConexion();
$resultado = (new AltaDatosIncidencia($conexion))->asignarIncidencia($datos);
$conectorPDO->desconectar();
header("Location: incidencias.php?" . ($resultado ? "exito=asignacion" : "error=asignacion")); exit;
