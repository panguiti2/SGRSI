<?php
require_once __DIR__ . "/../protegerAcceso.php";
verificarRolPublico("solicitante");
require_once __DIR__ . "/../../config/config.php";
$cedulaSolicitante = $_SESSION["cedula"];
$vistaIncidencias = RUTA_VISTA . "/solicitante/incidencias.php";
require_once RUTA_CONTROLADOR . "/cargarIncidencias.php";
