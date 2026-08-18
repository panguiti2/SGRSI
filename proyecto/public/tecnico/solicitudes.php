<?php
require_once __DIR__ . "/../protegerAcceso.php";
verificarRolPublico("tecnico");
require_once __DIR__ . "/../../config/config.php";
$vistaSolicitudes = RUTA_VISTA . "/tecnico/solicitudes.php";
require_once RUTA_CONTROLADOR . "/cargarSolicitudes.php";
