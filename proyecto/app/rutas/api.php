<?php

// Tabla explícita de rutas, sin librerías de enrutamiento.
$rutas = [
    'login' => ['LoginController', 'autenticar'],
    'logout' => ['LoginController', 'cerrarSesion'],
    'sesion' => ['LoginController', 'consultarSesion'],
    'usuarios' => ['UsuarioController', 'gestionar'],
    'dispositivos' => ['DispositivoController', 'gestionar'],
    'prestamos' => ['PrestamoController', 'gestionar'],
    'tickets' => ['TicketController', 'gestionar'],
    'registrosUso' => ['RegistroUsoController', 'gestionar'],
    'metricas' => ['MetricasController', 'gestionar'],
    'formulario' => ['FormularioController', 'gestionar']
];
if (!isset($rutas[$ruta])) throw new ErrorHttp('Ruta no encontrada', 404);
[$clase, $accion] = $rutas[$ruta];
require_once RUTA_CONTROLADOR . '/' . $clase . '.php';
$controlador = new $clase();
$controlador->$accion($metodo);
