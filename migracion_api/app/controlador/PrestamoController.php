<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/PrestamoDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

/** Atiende las peticiones de la API de préstamos. */
class PrestamoController
{
    /**
     * Selecciona la operación según el método HTTP recibido.
     * @param string $metodo Método HTTP de la petición.
     * @return void
     */
    public function gestionar(string $metodo): void
    {
        Sesion::verificarRoles($metodo === 'GET' ? ['administrador', 'tecnico'] : ['tecnico']);

        match ($metodo) {
            "GET" => $this->listar(),
            "POST" => $this->alta(),
            "PUT" => $this->devolucion(),
            default => RespuestaJson::error("Método no permitido", 405),
        };
    }

    /** @return void */
    private function listar(): void
    {
        $conexion = Conexion::conectar();
        $dao = new PrestamoDAO($conexion);
        RespuestaJson::exito($dao->listarPrestamos());
    }

    /** @return void */
    private function alta(): void
    {
        Token::verificarCsrf();
        $datos = Peticion::recibirDatos();

        $cedulaSolicitante = trim($datos["cedulaSolicitante"] ?? "");
        $turno = trim($datos["turno"] ?? "");
        $nombreSolicitante = trim($datos["nombreSolicitante"] ?? "");
        $numeroLaptop = trim($datos["numeroLaptop"] ?? "");
        $retiro = trim($datos["retiro"] ?? "");
        $devolucion = trim($datos["devolucion"] ?? "");

        Peticion::validarCamposObligatorios([$cedulaSolicitante, $turno, $nombreSolicitante, $numeroLaptop, $retiro, $devolucion]);
        Peticion::validarLongitudes($datos, ['nombreSolicitante' => 100, 'numeroLaptop' => 20]);
        if (!preg_match("/^[1-9][0-9]{7}$/", $cedulaSolicitante)) {
            RespuestaJson::error("La cédula no es válida", 422);
        }

        $fechaRetiro = Peticion::fecha($retiro);
        $fechaDevolucion = Peticion::fecha($devolucion);

        if ($fechaDevolucion <= $fechaRetiro) {
            RespuestaJson::error("Las fechas no son válidas", 422);
        }

        $conexion = Conexion::conectar();
        $dao = new PrestamoDAO($conexion);

        if (!$dao->existeTurno($turno)) {
            RespuestaJson::error("El turno no existe", 422);
        }

        if ($dao->tienePrestamoActivo($cedulaSolicitante)) {
            RespuestaJson::error("La cédula ya posee un préstamo activo", 409);
        }

        $idPrestamo = "PRE" . strtoupper(substr(uniqid(), -5));
        $resultado = $dao->registrarPrestamo(
            $idPrestamo,
            $cedulaSolicitante,
            $turno,
            $nombreSolicitante,
            $numeroLaptop,
            $fechaRetiro->format("Y-m-d H:i:s"),
            $fechaDevolucion->format("Y-m-d H:i:s")
        );

        if (!$resultado) {
            RespuestaJson::error("No se pudo registrar el préstamo", 400);
        }

        RespuestaJson::exito([
            "mensaje" => "Préstamo registrado exitosamente",
            "idPrestamo" => $idPrestamo
        ], 201);
    }

    /** @return void */
    private function devolucion(): void
    {
        Token::verificarCsrf();
        $datos = Peticion::recibirDatos();

        if (($datos['accion'] ?? '') === 'editar') {
            throw new ErrorHttp('No se permite editar los datos de tickets o préstamos', 405);
        }
        $idPrestamo = trim($datos["idPrestamo"] ?? "");
        $fechaEntrada = trim($datos["fechaDevolucion"] ?? "");
        Peticion::validarCamposObligatorios([$idPrestamo, $fechaEntrada]);
        $fechaDevolucion = Peticion::fecha($fechaEntrada);

        if (!preg_match("/^PRE[A-Z0-9]{5}$/", $idPrestamo)) {
            RespuestaJson::error("Los datos de la devolución no son válidos", 422);
        }

        $conexion = Conexion::conectar();
        $dao = new PrestamoDAO($conexion);
        $resultado = $dao->registrarDevolucion(
            $idPrestamo,
            $fechaDevolucion->format("Y-m-d H:i:s")
        );

        if (!$resultado) {
            RespuestaJson::error("No se pudo registrar la devolución", 400);
        }

        RespuestaJson::exito(["mensaje" => "Devolución registrada exitosamente"]);
    }


}
