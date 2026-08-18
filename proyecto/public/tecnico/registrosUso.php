<?php

require_once __DIR__ . "/../protegerAcceso.php";
verificarRolPublico("tecnico");

require_once __DIR__ . "/../../config/config.php";

$vistaRegistrosUso = RUTA_VISTA . "/tecnico/registrosUso.php";

require_once RUTA_CONTROLADOR . "/cargarRegistrosUso.php";
