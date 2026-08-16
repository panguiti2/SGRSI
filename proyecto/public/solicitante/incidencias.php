<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/verificarAcceso.php";
verificarAcceso("solicitante");
$cedulaSolicitante = $_SESSION["cedula"];
$vistaIncidencias = RUTA_VISTA . "/solicitante/incidencias.php";
require_once RUTA_CONTROLADOR . "/cargarIncidencias.php";
