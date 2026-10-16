<?php

/** Serializa respuestas: los errores se resuelven en la entrada común. */
class RespuestaJson
{
    public static function exito($datos, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        header('X-Content-Type-Options: nosniff');
        echo json_encode(['datos' => $datos], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    public static function error(string $mensaje, int $status): void
    {
        throw new ErrorHttp($mensaje, $status);
    }
}
