<?php

// Tabla explícita de rutas, sin librerías de enrutamiento.
$rutas = [
    'login' => ['LoginController', 'autenticar', ['POST']],
    'logout' => ['LoginController', 'cerrarSesion', ['POST']],
    'sesion' => ['LoginController', 'consultarSesion', ['GET']],
    'usuarios' => ['UsuarioController', 'gestionar', ['GET', 'POST', 'PUT', 'DELETE']],
    'dispositivos' => ['DispositivoController', 'gestionar', ['GET', 'POST', 'PUT', 'DELETE']],
    'historialDispositivos' => ['HistorialDispositivoController', 'gestionar', ['GET']],
    'prestamos' => ['PrestamoController', 'gestionar', ['GET', 'POST', 'PUT']],
    'tickets' => ['TicketController', 'gestionar', ['GET', 'POST', 'PUT']],
    'registrosUso' => ['RegistroUsoController', 'gestionar', ['GET', 'POST']],
    'metricas' => ['MetricasController', 'gestionar', ['GET']],
    'formulario' => ['FormularioController', 'gestionar', ['GET']]
];
if (!isset($rutas[$ruta])) throw new ErrorHttp('Ruta no encontrada', 404);
[$clase, $accion, $permitidos] = $rutas[$ruta];
Peticion::validarMetodo($metodo, $permitidos);
require_once RUTA_CONTROLADOR . '/' . $clase . '.php';
$controlador = new $clase();
$controlador->$accion($metodo);
