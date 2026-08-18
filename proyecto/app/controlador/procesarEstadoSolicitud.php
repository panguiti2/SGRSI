<?php

/** Controlador que actualiza el estado de una solicitud. */

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosSolicitud.php";
require_once RUTA_MODELO . "/AccesoDatosCatalogo.php";

$idSolicitud = trim($_POST["idSolicitud"] ?? "");
$estado = trim($_POST["estado"] ?? "");
if (!preg_match("/^SOL[A-Z0-9]{5}$/", $idSolicitud)) {
    header("Location: solicitudes.php?error=datos_incorrectos");
    exit;
}

$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();
$accesoDatosCatalogo = new AccesoDatosCatalogo($conexion);

if (!$accesoDatosCatalogo->existeEstadoTicket($estado)) {
    $conectorPDO->desconectar();
    header("Location: solicitudes.php?error=datos_incorrectos");
    exit;
}

$altaDatosSolicitud = new AltaDatosSolicitud($conexion);
$resultado = $altaDatosSolicitud->actualizarEstado($idSolicitud, $estado);
$conectorPDO->desconectar();

header("Location: solicitudes.php?" . ($resultado ? "exito=estado" : "error=estado"));
exit;
