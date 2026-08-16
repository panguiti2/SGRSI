<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosSolicitud.php";

$idSolicitud = trim($_POST["idSolicitud"] ?? "");
$estado = trim($_POST["estado"] ?? "");
if ($_SERVER["REQUEST_METHOD"] !== "POST"
    || !preg_match("/^SOL[A-Z0-9]{5}$/", $idSolicitud)
    || !in_array($estado, ["PENDIENTE", "EN PROCESO", "RESUELTO"], true)) {
    header("Location: solicitudes.php?error=datos_incorrectos");
    exit;
}

$conectorPDO = new ConectorPDO("localhost", "root", "", "test");
$conexion = $conectorPDO->establecerConexion();
$altaDatosSolicitud = new AltaDatosSolicitud($conexion);
$resultado = $altaDatosSolicitud->actualizarEstado($idSolicitud, $estado);
$conectorPDO->desconectar();

header("Location: solicitudes.php?" . ($resultado ? "exito=estado" : "error=estado"));
exit;
