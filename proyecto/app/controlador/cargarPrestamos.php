<?php

require_once __DIR__ . "/../modelo/ConectorPDO.php";
require_once __DIR__ . "/../modelo/AccesoDatosPrestamo.php";

$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();

$accesoDatosPrestamo = new AccesoDatosPrestamo($conexion);
$prestamos = $accesoDatosPrestamo->listarPrestamos();

$conectorPDO->desconectar();

require_once __DIR__ . "/../vista/tecnico/prestamos.php";
