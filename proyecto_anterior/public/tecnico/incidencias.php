<?php
require_once __DIR__ . "/../protegerAcceso.php";
verificarRolPublico("tecnico");
require_once __DIR__ . "/../../config/config.php";
$vistaIncidencias = RUTA_VISTA . "/tecnico/incidencias.php";
require_once RUTA_CONTROLADOR . "/cargarIncidencias.php";
