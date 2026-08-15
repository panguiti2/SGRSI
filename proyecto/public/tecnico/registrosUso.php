<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/verificarAcceso.php";
verificarAcceso("tecnico");
$vistaRegistrosUso = RUTA_VISTA . "/tecnico/registrosUso.php";
require_once RUTA_CONTROLADOR . "/cargarRegistrosUso.php";
