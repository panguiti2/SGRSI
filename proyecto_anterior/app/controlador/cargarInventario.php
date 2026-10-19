<?php

/** Controlador que recupera el inventario para la vista de administración. */

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AccesoDatosDispositivo.php";
require_once RUTA_MODELO . "/AccesoDatosCatalogo.php";

$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();

$accesoDatosDispositivo = new AccesoDatosDispositivo($conexion);
$dispositivos = $accesoDatosDispositivo->listarDispositivos();
$laboratorios = $accesoDatosDispositivo->listarLaboratorios();
$accesoDatosCatalogo = new AccesoDatosCatalogo($conexion);
$modificacionesDispositivo = $accesoDatosCatalogo->listarModificacionesDispositivo();

$conectorPDO->desconectar();

require_once RUTA_VISTA . "/admin/inventario.php";
