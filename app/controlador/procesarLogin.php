<?php

require_once __DIR__ . "/../modelo/Usuario.php";
require_once __DIR__ . "/../modelo/ConsultaUsuario.php";
require_once __DIR__ . "/../modelo/Login.php";

// Solo permite solicitudes enviadas por POST.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../public/login.php");
    exit;
}

// Recupera los datos del formulario.
$cedula = trim($_POST["cedula"] ?? "");
$clave = $_POST["clave"] ?? "";

// Comprueba que se hayan completado los campos.
if ($cedula === "" || $clave === "") {
    header(
        "Location: ../../public/login.php?error=" .
        urlencode("Debe completar todos los campos.")
    );
    exit;
}

// Crea los objetos del modelo.
$consultaUsuario = new ConsultaUsuario();
$login = new Login($consultaUsuario);

// Intenta autenticar al usuario.
$usuario = $login->autenticar($cedula, $clave);

if ($usuario === null) {
    header(
        "Location: ../../public/login.php?error=" .
        urlencode("La cédula o la contraseña son incorrectas.")
    );
    exit;
}

// Inicia la sesión.
session_start();
session_regenerate_id(true);

// Guarda los datos necesarios.
$_SESSION["cedula"] = $usuario->getCedula();
$_SESSION["nombre"] = $usuario->getNombre();
$_SESSION["rol"] = $usuario->getRol();

// Redirige según el rol.
switch ($usuario->getRol()) {
    case "administrador":
        header("Location: ../../public/administrador.php");
        exit;

    case "tecnico":
        header("Location: ../../public/tecnico.php");
        exit;

    case "solicitante":
        header("Location: ../../public/usuario.php");
        exit;

    default:
        session_destroy();

        header(
            "Location: ../../public/login.php?error=" .
            urlencode("El usuario no tiene un rol válido.")
        );

        exit;
}