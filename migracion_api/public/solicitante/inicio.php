<?php

require_once __DIR__ . "/../protegerAcceso.php";
verificarRolPublico("solicitante");
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_VISTA . "/solicitante/inicio.html";
