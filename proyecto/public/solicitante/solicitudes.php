<?php
require_once __DIR__ . "/../protegerAcceso.php";
verificarRolPublico("solicitante");
require_once __DIR__ . "/../../config/config.php";
$cedulaSolicitante = $_SESSION["cedula"];
$vistaSolicitudes = RUTA_VISTA . "/solicitante/solicitudes.php";
require_once RUTA_CONTROLADOR . "/cargarSolicitudes.php";
