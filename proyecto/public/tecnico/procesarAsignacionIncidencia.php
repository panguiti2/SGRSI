<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/verificarAcceso.php";
verificarAcceso("tecnico");
require_once RUTA_CONTROLADOR . "/procesarAsignacionIncidencia.php";
