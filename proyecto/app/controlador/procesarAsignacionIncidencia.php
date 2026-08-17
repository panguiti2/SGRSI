<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosIncidencia.php";
require_once RUTA_MODELO . "/AccesoDatosCatalogo.php";

$datos = [
"idIncidencia" => trim($_POST["idIncidencia"] ?? ""), 
"estado" => trim($_POST["estado"] ?? ""),
"cedulaTecnico" => $_SESSION["cedula"]];
if (!preg_match("/^INC[A-Z0-9]{5}$/", $datos["idIncidencia"])) {
    header("Location: incidencias.php?error=datos_incorrectos"); exit;
}
$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();
$accesoDatosCatalogo = new AccesoDatosCatalogo($conexion);

if (!$accesoDatosCatalogo->existeEstadoTicket($datos["estado"])) {
    $conectorPDO->desconectar();
    header("Location: incidencias.php?error=datos_incorrectos");
    exit;
}

$resultado = (new AltaDatosIncidencia($conexion))->asignarIncidencia($datos);
$conectorPDO->desconectar();
header("Location: incidencias.php?" . ($resultado ? "exito=asignacion" : "error=asignacion")); exit;
