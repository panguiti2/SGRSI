<?php

session_start();

if (isset($_SESSION["cedula"])) {
    if (!empty($_SESSION["administrador"]) && !empty($_SESSION["tecnico"])) {
        header("Location: panelRoles.php");
        exit;
    }

    if (!empty($_SESSION["administrador"])) {
        header("Location: administrador.php");
        exit;
    }

    if (!empty($_SESSION["tecnico"])) {
        header("Location: tecnico.php");
        exit;
    }

    if (!empty($_SESSION["solicitante"])) {
        header("Location: solicitante.php");
        exit;
    }
}

require_once __DIR__ . "/../app/vista/login.php";
