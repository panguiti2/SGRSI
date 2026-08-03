<?php

session_start();

// Si ya existe una sesión, redirige según el rol.
if (isset($_SESSION["rol"])) {
    switch ($_SESSION["rol"]) {
        case "administrador":
            header("Location: administrador.php");
            exit;

        case "tecnico":
            header("Location: tecnico.php");
            exit;

        case "solicitante":
            header("Location: usuario.php");
            exit;
    }
}

// Carga la vista del login.
require_once __DIR__ . "/../app/vista/login.php";