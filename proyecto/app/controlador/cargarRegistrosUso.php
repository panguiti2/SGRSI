<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AccesoDatosRegistroUso.php";
require_once RUTA_MODELO . "/AccesoDatosDispositivo.php";
require_once RUTA_MODELO . "/AccesoDatosCatalogo.php";

$conectorPDO = new ConectorPDO(
    $_ENV["DB_HOST"],
    $_ENV["DB_USUARIO"],
    $_ENV["DB_CLAVE"],
    $_ENV["DB_NOMBRE"]
);
$conexion = $conectorPDO->establecerConexion();

$accesoDatosRegistroUso = new AccesoDatosRegistroUso($conexion);
$registrosUso = $accesoDatosRegistroUso->listarRegistros($cedulaSolicitante ?? null);

if (isset($cargarFormularioRegistroUso) && $cargarFormularioRegistroUso) {
    $accesoDatosDispositivo = new AccesoDatosDispositivo($conexion);
    $laboratorios = $accesoDatosDispositivo->listarLaboratorios();

    $accesoDatosCatalogo = new AccesoDatosCatalogo($conexion);
    $turnos = $accesoDatosCatalogo->listarTurnos();
}

$conectorPDO->desconectar();

require_once $vistaRegistrosUso;
