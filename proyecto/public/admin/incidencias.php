<?php
require_once __DIR__ . "/../protegerAcceso.php";
verificarRolPublico("administrador");
require_once __DIR__ . "/../../config/config.php";
$vistaIncidencias = RUTA_VISTA . "/admin/incidencias.php";
require_once RUTA_CONTROLADOR . "/cargarIncidencias.php";
