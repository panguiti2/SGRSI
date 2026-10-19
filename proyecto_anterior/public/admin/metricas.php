<?php

require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../protegerAcceso.php";

verificarRolPublico("administrador");

$vistaMetricas = RUTA_VISTA . "/admin/metricas.php";
require_once RUTA_CONTROLADOR . "/cargarMetricas.php";
