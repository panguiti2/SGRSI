<?php

/** Recupera los indicadores y carga la vista del rol correspondiente. */

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AccesoDatosMetricas.php";

$conectorPDO = new ConectorPDO(
    $_ENV["DB_HOST"],
    $_ENV["DB_USUARIO"],
    $_ENV["DB_CLAVE"],
    $_ENV["DB_NOMBRE"]
);
$conexion = $conectorPDO->establecerConexion();

$accesoDatosMetricas = new AccesoDatosMetricas($conexion);
$metricas = $accesoDatosMetricas->obtenerMetricas();

$conectorPDO->desconectar();

require_once $vistaMetricas;

