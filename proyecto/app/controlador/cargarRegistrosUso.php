<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AccesoDatosRegistroUso.php";
$conectorPDO = new ConectorPDO("localhost", "root", "", "test");
$conexion = $conectorPDO->establecerConexion();
$registrosUso = (new AccesoDatosRegistroUso($conexion))->listarRegistros($cedulaSolicitante ?? null);
$conectorPDO->desconectar();
require_once $vistaRegistrosUso;
