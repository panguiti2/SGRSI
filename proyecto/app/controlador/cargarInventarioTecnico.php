<?php

/** Controlador que recupera el inventario de consulta para el técnico. */

require_once __DIR__ . "/../modelo/ConectorPDO.php";
require_once __DIR__ . "/../modelo/AccesoDatosDispositivo.php";

$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();

$accesoDatosDispositivo = new AccesoDatosDispositivo($conexion);
$dispositivos = $accesoDatosDispositivo->listarDispositivos();

$conectorPDO->desconectar();

require_once __DIR__ . "/../vista/tecnico/inventario.php";
