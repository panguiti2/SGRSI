<?php

/** Controlador que recupera usuarios y carga la gestión administrativa. */

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AccesoDatosUsuario.php";

$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();

    $accesoDatosUsuario = new AccesoDatosUsuario($conexion);
    $usuarios = $accesoDatosUsuario->listarUsuarios();

$conectorPDO->desconectar();

require_once RUTA_VISTA . "/admin/gestionUsuario.php";
