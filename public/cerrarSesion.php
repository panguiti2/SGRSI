<?php

session_start();

// Elimina todas las variables de sesión.
$_SESSION = [];

// Elimina la cookie de sesión si existe.
if (ini_get("session.use_cookies")) {
    $parametros = session_get_cookie_params();

    setcookie(
        session_name(),
        "",
        time() - 42000,
        $parametros["path"],
        $parametros["domain"],
        $parametros["secure"],
        $parametros["httponly"]
    );
}

// Destruye la sesión.
session_destroy();

// Redirige al login.
header("Location: login.php");
exit;

?>