<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AccesoDatosIncidencia.php";
require_once RUTA_MODELO . "/AccesoDatosDispositivo.php";
require_once RUTA_MODELO . "/AccesoDatosCatalogo.php";

$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();
$accesoDatosIncidencia = new AccesoDatosIncidencia($conexion);
$incidencias = $accesoDatosIncidencia->listarIncidencias($cedulaSolicitante ?? null);
$accesoDatosDispositivo = new AccesoDatosDispositivo($conexion);
$laboratorios = $accesoDatosDispositivo->listarLaboratorios();
$dispositivosFormulario = $accesoDatosDispositivo->listarDispositivosParaFormulario();
$accesoDatosCatalogo = new AccesoDatosCatalogo($conexion);
$turnos = $accesoDatosCatalogo->listarTurnos();
$conectorPDO->desconectar();
require_once $vistaIncidencias;
