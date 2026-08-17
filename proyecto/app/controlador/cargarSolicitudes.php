<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AccesoDatosSolicitud.php";
require_once RUTA_MODELO . "/AccesoDatosDispositivo.php";
require_once RUTA_MODELO . "/AccesoDatosCatalogo.php";

$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();
$accesoDatosSolicitud = new AccesoDatosSolicitud($conexion);
$solicitudes = $accesoDatosSolicitud->listarSolicitudes($cedulaSolicitante ?? null);
$accesoDatosDispositivo = new AccesoDatosDispositivo($conexion);
$laboratorios = $accesoDatosDispositivo->listarLaboratorios();
$dispositivosFormulario = $accesoDatosDispositivo->listarDispositivosParaFormulario();
$accesoDatosCatalogo = new AccesoDatosCatalogo($conexion);
$turnos = $accesoDatosCatalogo->listarTurnos();
$tiposServicio = $accesoDatosCatalogo->listarTiposServicio();
$estadosTicket = $accesoDatosCatalogo->listarEstadosTicket();
$conectorPDO->desconectar();

require_once $vistaSolicitudes;
