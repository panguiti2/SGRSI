<?php

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/verificarAcceso.php";
verificarAcceso("solicitante");
require_once RUTA_CONTROLADOR . "/procesarAltaSolicitud.php";
