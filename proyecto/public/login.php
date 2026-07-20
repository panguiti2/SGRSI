<?php

session_start();


if (isset($_SESSION["rol"])) {
    switch ($_SESSION["rol"]) {
        case "administrador":
            header("Location: administrador.php");
            exit;

        case "tecnico":
            header("Location: tecnico.php");
            exit;

        case "solicitante":
            header("Location: solicitante.php");
            exit;
    }
}

require_once __DIR__ . "/../app/vista/login.php";