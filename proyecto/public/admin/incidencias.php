<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/verificarAcceso.php";
verificarAcceso("administrador");
$vistaIncidencias = RUTA_VISTA . "/admin/incidencias.php";
require_once RUTA_CONTROLADOR . "/cargarIncidencias.php";
