<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AccesoDatosSolicitud.php";

$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();
$accesoDatosSolicitud = new AccesoDatosSolicitud($conexion);
$solicitudes = $accesoDatosSolicitud->listarSolicitudes($cedulaSolicitante ?? null);
$conectorPDO->desconectar();

require_once $vistaSolicitudes;
