<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/verificarAcceso.php";
verificarAcceso("tecnico");
$vistaIncidencias = RUTA_VISTA . "/tecnico/incidencias.php";
require_once RUTA_CONTROLADOR . "/cargarIncidencias.php";
