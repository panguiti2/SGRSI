<?php

/** Lectura y validación común de entradas, independiente del módulo. */
class Peticion
{
    public static function recibirDatos(): array
    {
        $tipo = strtolower(explode(';', $_SERVER['CONTENT_TYPE'] ?? '')[0]);
        if ($tipo !== 'application/json') throw new ErrorHttp('La petición debe enviar JSON', 415);
        $entrada = file_get_contents('php://input', false, null, 0, 65537);
        if (strlen($entrada) > 65536) throw new ErrorHttp('La petición excede el tamaño permitido', 413);
        if (!str_starts_with(ltrim($entrada), '{')) throw new ErrorHttp('Los datos enviados no son válidos', 400);
        try {
            $datos = json_decode($entrada, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $error) {
            throw new ErrorHttp('Los datos enviados no son válidos', 400);
        }
        if (!is_array($datos)) throw new ErrorHttp('Los datos enviados no son válidos', 400);
        foreach ($datos as $clave => $valor) {
            if (!is_string($clave) || !is_string($valor) || str_contains($valor, "\0")) {
                throw new ErrorHttp('Los datos enviados no son válidos');
            }
        }
        return $datos;
    }

    public static function validarCamposObligatorios(array $campos): void
    {
        foreach ($campos as $campo) {
            if (trim((string) $campo) === '') throw new ErrorHttp('Existen campos vacios');
        }
    }

    public static function validarLongitudes(array $datos, array $limites): void
    {
        foreach ($limites as $campo => $limite) {
            if (mb_strlen($datos[$campo] ?? '', 'UTF-8') > $limite) {
                throw new ErrorHttp('Los datos exceden la longitud permitida');
            }
        }
    }

    public static function fecha(string $entrada): DateTime
    {
        self::validarCamposObligatorios([$entrada]);
        $fecha = DateTime::createFromFormat('!Y-m-d\\TH:i', $entrada);
        if (!$fecha || $fecha->format('Y-m-d\\TH:i') !== $entrada) throw new ErrorHttp('La fecha no es válida');
        return $fecha;
    }

    public static function recibirConsulta(array $campos): array
    {
        $datos = [];
        foreach ($campos as $campo) {
            $valor = $_GET[$campo] ?? '';
            if (!is_string($valor)) throw new ErrorHttp('Los datos enviados no son válidos');
            $datos[$campo] = trim($valor);
        }
        self::validarCamposObligatorios($datos);
        return $datos;
    }

    public static function validarCedula(string $cedula): void
    {
        self::validarCamposObligatorios([$cedula]);
        if (!preg_match('/^[1-9][0-9]{7}$/', $cedula)) throw new ErrorHttp('La cédula no es válida');
    }

    public static function validarClave(string $clave, string $confirmacion): void
    {
        self::validarCamposObligatorios([$clave, $confirmacion]);
        if (mb_strlen($clave, 'UTF-8') < 12) throw new ErrorHttp('La contraseña debe contener al menos 12 caracteres');
        // PASSWORD_DEFAULT usa bcrypt: nunca se aceptan bytes que serían truncados.
        if (strlen($clave) > 72) throw new ErrorHttp('La contraseña excede la longitud permitida');
        if ($clave !== $confirmacion) throw new ErrorHttp('Las contraseñas ingresadas no coinciden');
    }
}
