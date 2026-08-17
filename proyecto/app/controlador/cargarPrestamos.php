<?php

require_once __DIR__ . "/../modelo/ConectorPDO.php";
require_once __DIR__ . "/../modelo/AccesoDatosPrestamo.php";
require_once __DIR__ . "/../modelo/AccesoDatosCatalogo.php";

$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();

$accesoDatosPrestamo = new AccesoDatosPrestamo($conexion);
$prestamos = $accesoDatosPrestamo->listarPrestamos();
$accesoDatosCatalogo = new AccesoDatosCatalogo($conexion);
$turnos = $accesoDatosCatalogo->listarTurnos();

$conectorPDO->desconectar();

require_once __DIR__ . "/../vista/tecnico/prestamos.php";
