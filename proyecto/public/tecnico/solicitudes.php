<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/verificarAcceso.php";
verificarAcceso("tecnico");
$vistaSolicitudes = RUTA_VISTA . "/tecnico/solicitudes.php";
require_once RUTA_CONTROLADOR . "/cargarSolicitudes.php";
