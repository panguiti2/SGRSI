<?php

require_once RUTA_MODELO . '/HistorialDispositivoDAO.php';

class HistorialDispositivoController
{
    public function gestionar(string $metodo): void
    {
        Sesion::verificarRoles(['administrador', 'tecnico']);
        Peticion::validarMetodo($metodo, ['GET']);
        RespuestaJson::exito((new HistorialDispositivoDAO(Conexion::conectar()))->listar());
    }
}
