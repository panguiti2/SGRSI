<?php

/**
 * Clase que simula una recuperación de datos de dispositivos correspondientes a la base de datos.
 */
class AccesoDatosDispositivo {
    private PDO $conexion;


    /**
     * Constructor parametrizado que recibe una conexión a la base de datos.
     * @param PDO $conexion La conexion a la base de datos. PRECONDICION: No debe ser NULL.
     */
    public function __construct (PDO $conexion) {
        $this->conexion = $conexion;
    }
    
    /**
     * Lista todos los dispositivos registrados en la base de datos.
     * @return array lista de dispositivos con sus roles.
     */
    public function listarDispositivos (): array {
        $sql = "
            SELECT
                d.idLaboratorio AS idLab,
                l.nombre AS laboratorio,
                d.numeroDispositivo,
                d.Modificaciones AS modificaciones,
                d.ultimoCambio,
                CASE
                WHEN d.estado = TRUE THEN 'Activo'
                ELSE 'Inactivo'
                END AS estado
            FROM DISPOSITIVO AS d
            INNER JOIN LABORATORIO AS l
                ON l.idLaboratorio = d.idLaboratorio
            ORDER BY d.idLaboratorio, d.numeroDispositivo
        ";  

        $consulta = $this->conexion->query($sql);

        $dispositivos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta = null;

        return $dispositivos;
    }

    /**
     * Recupera los laboratorios y talleres disponibles para los formularios.
     *
     * @return array Arreglo de laboratorios ordenados por identificador.
     */
    public function listarLaboratorios(): array
    {
        $sql = "
            SELECT idLaboratorio, nombre
            FROM LABORATORIO
            ORDER BY idLaboratorio
        ";

        $consulta = $this->conexion->query($sql);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recupera los dispositivos junto con el laboratorio al que pertenecen.
     *
     * @return array Arreglo usado por los formularios para filtrar dispositivos.
     */
    public function listarDispositivosParaFormulario(): array
    {
        $sql = "
            SELECT idLaboratorio, numeroDispositivo
            FROM DISPOSITIVO
            ORDER BY idLaboratorio, numeroDispositivo
        ";

        $consulta = $this->conexion->query($sql);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Comprueba que un dispositivo pertenezca al laboratorio recibido.
     *
     * @param string $idLaboratorio Identificador del laboratorio.
     * @param string $numeroDispositivo Identificador del dispositivo.
     * @return bool True si existe la combinación recibida.
     */
    public function existeDispositivo(string $idLaboratorio, string $numeroDispositivo): bool
    {
        $sql = "
            SELECT 1
            FROM DISPOSITIVO
            WHERE idLaboratorio = :idLaboratorio
                AND numeroDispositivo = :numeroDispositivo
        ";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([
            "idLaboratorio" => $idLaboratorio,
            "numeroDispositivo" => $numeroDispositivo
        ]);

        return $consulta->fetchColumn() !== false;
    }

    /**
     * Comprueba que exista un laboratorio o taller.
     *
     * @param string $idLaboratorio Identificador del laboratorio.
     * @return bool True si existe el laboratorio.
     */
    public function existeLaboratorio(string $idLaboratorio): bool
    {
        $sql = "SELECT 1 FROM LABORATORIO WHERE idLaboratorio = :idLaboratorio";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(["idLaboratorio" => $idLaboratorio]);

        return $consulta->fetchColumn() !== false;
    }

}

?>
