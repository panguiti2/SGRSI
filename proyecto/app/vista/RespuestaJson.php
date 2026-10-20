<?php

/** Serializa respuestas: los errores se resuelven en la entrada común. */
class RespuestaJson
{
    public static function exito($datos, int $status = 200): void
    {
        $mensaje = is_array($datos) && isset($datos['mensaje']) ? $datos['mensaje'] : 'Operación realizada correctamente';
        if (is_array($datos)) unset($datos['mensaje']);
        self::enviar(['status' => $status, 'message' => $mensaje, 'data' => $datos], $status);
    }

    public static function enviarError(string $mensaje, int $status): void
    {
        self::enviar(['status' => $status, 'message' => $mensaje, 'data' => null], $status);
    }

    private static function enviar(array $contenido, int $status): void
    {
        // Convertir antes de enviar cabeceras: si falla, la entrada común responde el error.
        $json = json_encode($contenido, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        header('X-Content-Type-Options: nosniff');
        echo $json;
    }

    public static function error(string $mensaje, int $status): void
    {
        throw new ErrorHttp($mensaje, $status);
    }
}
