<?php

session_start();

if (!isset($_SESSION["cedula"])) {
    header("Location: login.php?error=sin_sesion");
    exit;
}

if (($_SESSION["administrador"] ?? false) === true) {
    header("Location: admin/inicio.php");
} elseif (($_SESSION["tecnico"] ?? false) === true) {
    header("Location: tecnico/inicio.php");
} elseif (($_SESSION["solicitante"] ?? false) === true) {
    header("Location: solicitante/inicio.php");
} else {
    header("Location: login.php?error=sin_roles");
}

exit;
