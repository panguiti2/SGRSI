<?php

/** Devuelve las respuestas de la API en formato JSON. */
class RespuestaJson
{
    /**
     * Devuelve una respuesta exitosa y finaliza la petición.
     * @param mixed $datos Datos que se enviarán al cliente.
     * @param int $status Estado HTTP de la respuesta.
     * @return void
     */
    public static function exito($datos, int $status = 200): void
    {
        http_response_code($status);
        header("Content-Type: application/json");
        echo json_encode(["datos" => $datos]);
        exit;
    }

    /**
     * Devuelve un mensaje de error y finaliza la petición.
     * @param string $mensaje Mensaje que se enviará al cliente.
     * @param int $status Estado HTTP de la respuesta.
     * @return void
     */
    public static function error(string $mensaje, int $status): void
    {
        http_response_code($status);
        header("Content-Type: application/json");
        echo json_encode(["mensaje" => $mensaje]);
        exit;
    }
}
