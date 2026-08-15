<?php

require_once __DIR__ . "/../modelo/ConectorPDO.php";
require_once __DIR__ . "/../modelo/AccesoDatosPrestamo.php";

$conectorPDO = new ConectorPDO("localhost", "root", "", "test");
$conexion = $conectorPDO->establecerConexion();

$accesoDatosPrestamo = new AccesoDatosPrestamo($conexion);
$prestamos = $accesoDatosPrestamo->listarPrestamos();

$conectorPDO->desconectar();

require_once __DIR__ . "/../vista/tecnico/prestamos.php";
