<?php

require_once __DIR__ . '/../migracion_api/app/modelo/PrestamoDAO.php';

// Dobles de PDO: esta prueba no se conecta ni modifica bases de datos.
class ConsultaPrestamoPrueba extends PDOStatement
{
    public array $parametros = [];
    private bool $activo;

    public function __construct(bool $activo)
    {
        $this->activo = $activo;
    }

    public function execute(?array $params = null): bool
    {
        $this->parametros = $params ?? [];
        return true;
    }

    public function fetchColumn(int $column = 0)
    {
        return $this->activo ? 1 : false;
    }
}

class ConexionPrestamoPrueba extends PDO
{
    public string $sql = '';
    public ConsultaPrestamoPrueba $consulta;

    public function __construct(bool $activo)
    {
        $this->consulta = new ConsultaPrestamoPrueba($activo);
    }

    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        $this->sql = $query;
        return $this->consulta;
    }
}

foreach ([true, false] as $activo) {
    $conexion = new ConexionPrestamoPrueba($activo);
    $dao = new PrestamoDAO($conexion);
    if ($dao->tienePrestamoActivo('90909090') !== $activo
        || $conexion->consulta->parametros !== ['cedulaSolicitante' => '90909090']
        || strpos($conexion->sql, "estado = 'ACTIVO'") === false
        || strpos($conexion->sql, 'cedulaSolicitante = :cedulaSolicitante') === false) {
        fwrite(STDERR, "Falló la comprobación de préstamo activo.\n");
        exit(1);
    }
}

echo "Correcto: préstamo activo detectado y ausencia de activo permitida; consulta parametrizada.\n";
