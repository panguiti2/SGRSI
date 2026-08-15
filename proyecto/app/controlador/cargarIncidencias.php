<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AccesoDatosIncidencia.php";

$conectorPDO = new ConectorPDO("localhost", "root", "", "test");
$conexion = $conectorPDO->establecerConexion();
$accesoDatosIncidencia = new AccesoDatosIncidencia($conexion);
$incidencias = $accesoDatosIncidencia->listarIncidencias($cedulaSolicitante ?? null);
$conectorPDO->desconectar();
require_once $vistaIncidencias;
