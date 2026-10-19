<?php

// Configuración y dependencias propias del proyecto API.
define("RUTA_RAIZ", dirname(__DIR__));
define("RUTA_APP", RUTA_RAIZ . "/app");
define("RUTA_MODELO", RUTA_APP . "/modelo");
define("RUTA_CONTROLADOR", RUTA_APP . "/controlador");
define("RUTA_VISTA", RUTA_APP . "/vista");
define("RUTA_NUCLEO", RUTA_APP . "/nucleo");
define("RUTA_PUBLIC", RUTA_RAIZ . "/public");
define("RUTA_PROYECTO", RUTA_RAIZ);

require_once RUTA_PROYECTO . "/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(RUTA_PROYECTO);
$dotenv->load();
date_default_timezone_set("America/Montevideo");
require_once RUTA_NUCLEO . '/ErrorHttp.php';
require_once RUTA_NUCLEO . '/ErrorSql.php';
require_once RUTA_NUCLEO . '/Peticion.php';
require_once RUTA_NUCLEO . '/Conexion.php';
require_once RUTA_NUCLEO . '/Token.php';
require_once RUTA_NUCLEO . '/Sesion.php';
