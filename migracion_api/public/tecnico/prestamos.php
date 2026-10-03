<?php

require_once __DIR__ . "/../protegerAcceso.php";
verificarRolPublico("tecnico");
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_VISTA . "/tecnico/prestamos.html";
