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
        if (!isset($_SESSION["cedula"])) {
            RespuestaJson::error("Acceso denegado: sesión no iniciada", 401);
        }
        if (!($_SESSION["tecnico"] ?? false)) {
            RespuestaJson::error("Acceso denegado: rol incorrecto", 403);
        }

        match ($metodo) {
            "GET" => $this->listar(),
            "POST" => $this->alta(),
            "PATCH" => $this->devolucion(),
            default => RespuestaJson::error("Método no permitido", 405),
        };
    }

    /** @return void */
    private function listar(): void
    {
        $conexion = $this->conectar();
        $dao = new PrestamoDAO($conexion);
        RespuestaJson::exito($dao->listarPrestamos());
    }

    /** @return void */
    private function alta(): void
    {
        $this->verificarCsrf();
        $datos = $this->recibirDatos();

        $cedulaSolicitante = trim($datos["cedulaSolicitante"] ?? "");
        $turno = trim($datos["turno"] ?? "");
        $nombreSolicitante = trim($datos["nombreSolicitante"] ?? "");
        $numeroLaptop = trim($datos["numeroLaptop"] ?? "");
        $retiro = trim($datos["retiro"] ?? "");
        $devolucion = trim($datos["devolucion"] ?? "");

        if ($cedulaSolicitante === "" || $turno === ""
            || $nombreSolicitante === "" || $numeroLaptop === ""
            || $retiro === "" || $devolucion === "") {
            RespuestaJson::error("Existen campos vacíos", 422);
        }
        if (!preg_match("/^[1-9][0-9]{7}$/", $cedulaSolicitante)) {
            RespuestaJson::error("La cédula no es válida", 422);
        }

        $fechaRetiro = DateTime::createFromFormat("Y-m-d\\TH:i", $retiro);
        $fechaDevolucion = DateTime::createFromFormat("Y-m-d\\TH:i", $devolucion);

        if ($fechaRetiro === false || $fechaDevolucion === false
            || $fechaDevolucion <= $fechaRetiro) {
            RespuestaJson::error("Las fechas no son válidas", 422);
        }

        $conexion = $this->conectar();
        $dao = new PrestamoDAO($conexion);

        if (!$dao->existeTurno($turno)) {
            RespuestaJson::error("El turno no existe", 422);
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
        $this->verificarCsrf();
        $datos = $this->recibirDatos();

        $idPrestamo = trim($datos["idPrestamo"] ?? "");
        $fechaEntrada = trim($datos["fechaDevolucion"] ?? "");
        $fechaDevolucion = DateTime::createFromFormat("Y-m-d\\TH:i", $fechaEntrada);

        if ($idPrestamo === "" || $fechaDevolucion === false) {
            RespuestaJson::error("Los datos de la devolución no son válidos", 422);
        }

        $conexion = $this->conectar();
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

    /** @return array Datos JSON enviados por el cliente. */
    private function recibirDatos(): array
    {
        return json_decode(file_get_contents("php://input"), true) ?? [];
    }

    /** @return void */
    private function verificarCsrf(): void
    {
        $token = $_SERVER["HTTP_X_CSRF_TOKEN"] ?? "";
        if (!isset($_SESSION["csrfToken"])
            || !hash_equals($_SESSION["csrfToken"], $token)) {
            RespuestaJson::error("Solicitud rechazada", 403);
        }
    }

    /** @return PDO Conexión activa. */
    private function conectar(): PDO
    {
        $conector = new ConectorPDO(
            $_ENV["DB_HOST"],
            $_ENV["DB_USUARIO"],
            $_ENV["DB_CLAVE"],
            $_ENV["DB_NOMBRE"]
        );

        return $conector->establecerConexion();
    }
}

