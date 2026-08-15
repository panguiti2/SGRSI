<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AccesoDatosDispositivo.php";

$conectorPDO = new ConectorPDO ("localhost", "root", "", "test");
$conexion = $conectorPDO->establecerConexion();

$accesoDatosDispositivo = new AccesoDatosDispositivo($conexion);
$dispositivos = $accesoDatosDispositivo->listarDispositivos();

$conectorPDO->desconectar();

require_once RUTA_VISTA . "/inventario.php";
