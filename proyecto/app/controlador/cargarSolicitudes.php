<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AccesoDatosSolicitud.php";

$conectorPDO = new ConectorPDO("localhost", "root", "", "test");
$conexion = $conectorPDO->establecerConexion();
$accesoDatosSolicitud = new AccesoDatosSolicitud($conexion);
$solicitudes = $accesoDatosSolicitud->listarSolicitudes($cedulaSolicitante ?? null);
$conectorPDO->desconectar();

require_once $vistaSolicitudes;
