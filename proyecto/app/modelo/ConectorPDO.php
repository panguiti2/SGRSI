<?php

/** Una instancia y una conexión PDO por petición. */
class ConectorPDO
{
    private static ?self $instancia = null;
    private ?PDO $conexion = null;

    private function __construct() {}
    private function __clone() {}

    public function __serialize(): array
    {
        throw new LogicException('El conector no puede serializarse');
    }

    public function __unserialize(array $datos): void
    {
        throw new LogicException('El conector no puede deserializarse');
    }

    public static function obtenerInstancia(): self
    {
        if (self::$instancia === null) self::$instancia = new self();
        return self::$instancia;
    }

    public function establecerConexion(): PDO
    {
        if ($this->conexion === null) {
            $puerto = (int) ($_ENV['DB_PUERTO'] ?? 3306);
            $dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';port=' . $puerto
                . ';dbname=' . $_ENV['DB_NOMBRE'] . ';charset=utf8mb4';
            $this->conexion = new PDO($dsn, $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        }
        return $this->conexion;
    }

    public function desconectar(): void
    {
        try {
            if ($this->conexion !== null && $this->conexion->inTransaction()) $this->conexion->rollBack();
        } catch (PDOException $error) {
            error_log('SGRSI: no se pudo revertir la transacción durante la desconexión');
        } finally {
            $this->conexion = null;
        }
    }
}
