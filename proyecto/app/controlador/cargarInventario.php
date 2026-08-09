<?php

require_once __DIR__ . "/../modelo/ConectorPDO.php";
require_once __DIR__ . "/../modelo/AccesoDatosDispositivo.php";

$conectorPDO = new ConectorPDO ("localhost", "root", "", "test");
$conexion = $conectorPDO->establecerConexion();

$accesoDatosDispositivo = new AccesoDatosDispositivo($conexion);
$dispositivos = $accesoDatosDispositivo->listarDispositivos();

$conectorPDO->desconectar();

require_once __DIR__ . "/../vista/admin/inventario.php";
