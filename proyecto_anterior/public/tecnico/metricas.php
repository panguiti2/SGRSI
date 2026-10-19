<?php

require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../protegerAcceso.php";

verificarRolPublico("tecnico");

$vistaMetricas = RUTA_VISTA . "/tecnico/metricas.php";
require_once RUTA_CONTROLADOR . "/cargarMetricas.php";
