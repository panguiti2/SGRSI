<?php

// La API tiene sus propias capas, pero comparte la configuración de conexión.
define("RUTA_RAIZ", dirname(__DIR__));
define("RUTA_APP", RUTA_RAIZ . "/app");
define("RUTA_MODELO", RUTA_APP . "/modelo");
define("RUTA_CONTROLADOR", RUTA_APP . "/controlador");
define("RUTA_VISTA", RUTA_APP . "/vista");
define("RUTA_PUBLIC", RUTA_RAIZ . "/public");
define("RUTA_PROYECTO", dirname(RUTA_RAIZ) . "/proyecto");

require_once RUTA_PROYECTO . "/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(RUTA_PROYECTO);
$dotenv->load();
date_default_timezone_set("America/Montevideo");
