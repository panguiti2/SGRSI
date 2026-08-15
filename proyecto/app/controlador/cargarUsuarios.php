<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AccesoDatosUsuario.php";

$conectorPDO = new ConectorPDO ("localhost", "root", "", "test");
$conexion = $conectorPDO->establecerConexion();

    $accesoDatosUsuario = new AccesoDatosUsuario($conexion);
    $usuarios = $accesoDatosUsuario->listarUsuarios();

$conectorPDO->desconectar();

require_once RUTA_VISTA . "/gestionUsuario.php";
