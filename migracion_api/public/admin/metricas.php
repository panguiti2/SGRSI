<?php

require_once __DIR__ . "/../protegerAcceso.php";
verificarRolPublico("administrador");
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_VISTA . "/admin/metricas.html";
